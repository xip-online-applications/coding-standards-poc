<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\User\Infrastructure\Http\Request;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use XIP\Shared\Infrastructure\Http\Request\AbstractRequest;
use XIP\User\Application\Validator\Constraint\EmailUnique;
use XIP\User\Application\Validator\Constraint\RolesExist;
use XIP\User\Infrastructure\Http\Request\UserRequest;

/**
 * @covers \XIP\User\Infrastructure\Http\Request\UserRequest
 */
class UserRequestTest extends TestCase
{
    private MockObject|RequestStack $requestStackMock;

    private UserRequest $userRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestStackMock = $this->createMock(RequestStack::class);
        $validatorMock = $this->createMock(ValidatorInterface::class);
        $this->userRequest = new UserRequest($this->requestStackMock, $validatorMock);
    }

    /**
     * @covers \XIP\User\Infrastructure\Http\Request\UserRequest
     */
    public function testInstance(): void
    {
        $this->assertInstanceOf(AbstractRequest::class, $this->userRequest);
    }

    /**
     * @covers \XIP\User\Infrastructure\Http\Request\UserRequest::constraints
     */
    public function testConstraints(): void
    {
        // Execute
        $result = $this->userRequest->constraints();

        // Validate
        $this->assertEquals(
            [
                'name' => [
                    new Required([
                        new NotBlank(),
                    ]),
                ],
                'email' => [
                    new Required([
                        new NotBlank(),
                        new Email(),
                        new EmailUnique(),
                    ]),
                ],
                'roles' => [
                    new Required([
                        new Type('array'),
                        new Count(['min' => 1]),
                        new RolesExist(),
                    ])
                ],
            ],
            $result
        );
    }

    /**
     * @covers \XIP\User\Infrastructure\Http\Request\UserRequest::constraints
     */
    public function testConstraintsWithUserId(): void
    {
        $request = new Request([], [], ['userId' => 1]);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        // Execute
        $result = $this->userRequest->constraints();

        // Validate
        $this->assertEquals(
            [
                'name' => [
                    new Required([
                        new NotBlank(),
                    ]),
                ],
                'email' => [
                    new Required([
                        new NotBlank(),
                        new Email(),
                        new EmailUnique(),
                    ]),
                ],
                'roles' => [
                    new Required([
                        new Type('array'),
                        new Count(['min' => 1]),
                        new RolesExist(),
                    ])
                ],
                'password' => [
                    new Required([
                        new Type('string'),
                        new Length(['min' => 8]),
                    ])
                ]
            ],
            $result
        );
    }

    /**
     * @covers \XIP\User\Infrastructure\Http\Request\UserRequest::validationData
     */
    public function testValidationData(): void
    {
        // Setup
        $data = ['email' => 'patrick.batenburg@x-ip.nl'];
        $this->mockGetCurrentRequest($data);

        // Execute
        $result = $this->userRequest->validationData();

        // Validate
        $this->assertSame($data, $result);
    }

    /**
     * @covers \XIP\User\Infrastructure\Http\Request\UserRequest::getName
     */
    public function testGetName(): void
    {
        // Setup
        $name = 'Patrick Batenburg';
        $this->mockGetCurrentRequest(['name' => $name]);
        
        // Execute
        $result = $this->userRequest->getName();
        
        // Validate
        $this->assertSame($name, $result);
    }

    /**
     * @covers \XIP\User\Infrastructure\Http\Request\UserRequest::getEmail
     */
    public function testGetEmail(): void
    {
        // Setup
        $email = 'patrick.batenburg@x-ip.nl';
        $this->mockGetCurrentRequest(['email' => $email]);

        // Execute
        $result = $this->userRequest->getEmail();

        // Validate
        $this->assertSame($email, $result);
    }
    
    public function passwordScenarioProvider(): array
    {
        return [
            'Default password' => [
                'expectedPassword' => null,
                'data' => [],
            ],
            'A password' => [
                'expectedPassword' => 'superSecret',
                'data' => ['password' => 'superSecret'],
            ],
        ];
    }

    /**
     * @dataProvider passwordScenarioProvider
     * @covers \XIP\User\Infrastructure\Http\Request\UserRequest::getPassword
     */
    public function testGetPassword(?string $expectedPassword, array $data): void
    {
        // Setup
        $this->mockGetCurrentRequest($data);

        // Execute
        $result = $this->userRequest->getPassword();

        // Validate
        $this->assertSame($expectedPassword, $result);
    }

    public function rolesScenarioProvider(): array
    {
        return [
            'Default roles' => [
                'expectedRoles' => [],
                'data' => [],
            ],
            'Roles' => [
                'expectedRoles' => [1, 2, 3],
                'data' => ['roles' => ['1', '2', '3']],
            ],
        ];
    }
    
    /**
     * @dataProvider rolesScenarioProvider
     * @covers \XIP\User\Infrastructure\Http\Request\UserRequest::getRoles
     */
    public function testGetRoles(array $expectedRoles, array $data): void
    {
        // Setup
        $this->mockGetCurrentRequest($data);

        // Execute
        $result = $this->userRequest->getRoles();

        // Validate
        $this->assertSame($expectedRoles, $result);
    }
    
    private function mockGetCurrentRequest(array $data): void
    {
        $request = new Request($data);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
    }
}
