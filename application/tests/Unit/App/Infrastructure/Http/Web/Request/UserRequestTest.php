<?php

declare(strict_types=1);

namespace App\Tests\Unit\App\Infrastructure\Http\Web\Request;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use XIP\App\Infrastructure\Http\Web\Request\UserRequest;
use XIP\Shared\Infrastructure\Http\Request\AbstractRequest;

/**
 * @covers \XIP\App\Infrastructure\Http\Web\Request\UserRequest
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
     * @covers \XIP\App\Infrastructure\Http\Web\Request\UserRequest
     */
    public function testInstance(): void
    {
        $this->assertInstanceOf(AbstractRequest::class, $this->userRequest);
    }

    /**
     * @covers \XIP\App\Infrastructure\Http\Web\Request\UserRequest::constraints
     */
    public function testConstraints(): void
    {
        // Execute
        $result = $this->userRequest->constraints();

        // Validate
        $this->assertEquals(
            [
                'email' => [
                    new NotBlank(),
                    new Email(),
                ]
            ],
            $result
        );
    }

    /**
     * @covers \XIP\App\Infrastructure\Http\Web\Request\UserRequest::validationData
     */
    public function testValidationData(): void
    {
        // Setup
        $data = ['email' => 'patrick.batenburg@x-ip.nl'];
        $request = new Request($data);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        // Execute
        $result = $this->userRequest->validationData();

        // Validate
        $this->assertSame($data, $result);
    }
}