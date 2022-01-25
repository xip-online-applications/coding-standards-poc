<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\Request;

use App\Shared\Domain\Exception\ConstraintViolationException;
use App\Shared\Infrastructure\Request\AbstractRequest;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @covers \App\Shared\Infrastructure\Request\AbstractRequest
 */
class AbstractRequestTest extends TestCase
{
    private MockObject|RequestStack $requestStackMock;

    private MockObject|ValidatorInterface $validatorMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestStackMock = $this->createMock(RequestStack::class);
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
    }

    public function testValidate(): void
    {
        // Setup
        $inputData = ['name' => 'Patrick Batenburg'];
        $request = new Request([], $inputData);
        $this->requestStackMock->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest($request);
        $constraintViolationListMock = $this->createMock(ConstraintViolationListInterface::class);
        $this->validatorMock->expects(self::once())
            ->method('validate')
            ->with($inputData, new Collection($abstractRequest->constraints()))
            ->willReturn($constraintViolationListMock);
        $constraintViolationListMock->expects(self::once())
            ->method('count')
            ->willReturn(0);

        // Execute
        $abstractRequest->validate();
    }

    public function testValidateWithInvalidData(): void
    {
        // Expectation
        $this->expectException(ConstraintViolationException::class);

        // Setup
        $inputData = ['email' => 'p.batenburg@live.nl'];
        $request = new Request([], $inputData);
        $this->requestStackMock->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest($request);
        $constraintViolationListMock = $this->createMock(ConstraintViolationListInterface::class);
        $this->validatorMock->expects(self::once())
            ->method('validate')
            ->with($inputData, new Collection($abstractRequest->constraints()))
            ->willReturn($constraintViolationListMock);
        $constraintViolationListMock->expects(self::once())
            ->method('count')
            ->willReturn(1);

        // Execute
        $abstractRequest->validate();
    }

    public function stringValueScenarioProvider(): array
    {
        return [
            'A string value with the right key' => [
                'expectedValue' => 'string',
                'key' => 'key',
                'value' => 'string',
            ],
            'No key sey' => [
                'expectedValue' => null,
                'key' => null,
                'value' => null,
            ],
            'A null value for a key' => [
                'expectedValue' => null,
                'key' => 'key',
                'value' => null,
            ]
        ];
    }

    /**
     * @covers       \App\Shared\Infrastructure\Request\AbstractRequest::resolveStringOrNullValue
     *
     * @dataProvider stringValueScenarioProvider
     */
    public function testResolveStringOrNullValue(?string $expectedValue, ?string $key, ?string $value): void
    {
        // Setup
        $requestData = null !== $key ? [$key => $value] : [];
        $request = new Request([], $requestData);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest($request);

        // Execute
        $result = $abstractRequest->resolveStringOrNullValue($key ?? 'fake');

        // Validate
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @covers       \App\Shared\Infrastructure\Request\AbstractRequest::resolveStringValue
     *
     * @dataProvider stringValueScenarioProvider
     */
    public function testResolveStringValue(?string $expectedValue, ?string $key, ?string $value): void
    {
        // Setup
        $requestData = null !== $key ? [$key => $value] : [];
        $request = new Request([], $requestData);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest($request);

        // Execute
        $result = $abstractRequest->resolveStringValue($key ?? 'fake');

        // Validate
        $this->assertSame($expectedValue ?? '', $result);
    }

    public function intValueScenarioProvider(): array
    {
        return [
            'An int value with the right key' => [
                'expectedValue' => 1,
                'key' => 'key',
                'value' => '1',
            ],
            'No key sey' => [
                'expectedValue' => null,
                'key' => null,
                'value' => null,
            ],
            'A null value for a key' => [
                'expectedValue' => null,
                'key' => 'key',
                'value' => null,
            ]
        ];
    }

    /**
     * @covers       \App\Shared\Infrastructure\Request\AbstractRequest::resolveIntOrNullValue
     *
     * @dataProvider intValueScenarioProvider
     */
    public function testResolveIntOrNullValue(?int $expectedValue, ?string $key, ?string $value): void
    {
        // Setup
        $requestData = null !== $key ? [$key => $value] : [];
        $request = new Request([], $requestData);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest($request);

        // Execute
        $result = $abstractRequest->resolveIntOrNullValue($key ?? 'fake');

        // Validate
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @covers       \App\Shared\Infrastructure\Request\AbstractRequest::resolveIntValue
     *
     * @dataProvider intValueScenarioProvider
     */
    public function testResolveIntValue(?int $expectedValue, ?string $key, ?string $value): void
    {
        // Setup
        $requestData = null !== $key ? [$key => $value] : [];
        $request = new Request([], $requestData);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest($request);

        // Execute
        $result = $abstractRequest->resolveIntValue($key ?? 'fake');

        // Validate
        $this->assertSame($expectedValue ?? 0, $result);
    }

    public function floatValueScenarioProvider(): array
    {
        return [
            'An int value with the right key' => [
                'expectedValue' => 1.00,
                'key' => 'key',
                'value' => '1',
            ],
            'An float value with the right key' => [
                'expectedValue' => 1.00,
                'key' => 'key',
                'value' => '1.00',
            ],
            'No key sey' => [
                'expectedValue' => null,
                'key' => null,
                'value' => null,
            ],
            'A null value for a key' => [
                'expectedValue' => null,
                'key' => 'key',
                'value' => null,
            ]
        ];
    }

    /**
     * @covers       \App\Shared\Infrastructure\Request\AbstractRequest::resolveFloatOrNullValue
     *
     * @dataProvider floatValueScenarioProvider
     */
    public function testResolveFloatOrNullValue(?float $expectedValue, ?string $key, ?string $value): void
    {
        // Setup
        $requestData = null !== $key ? [$key => $value] : [];
        $request = new Request([], $requestData);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest($request);

        // Execute
        $result = $abstractRequest->resolveFloatOrNullValue($key ?? 'fake');

        // Validate
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @covers       \App\Shared\Infrastructure\Request\AbstractRequest::resolveFloatValue
     *
     * @dataProvider floatValueScenarioProvider
     */
    public function testResolveFloatValue(?float $expectedValue, ?string $key, ?string $value): void
    {
        // Setup
        $requestData = null !== $key ? [$key => $value] : [];
        $request = new Request([], $requestData);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest($request);

        // Execute
        $result = $abstractRequest->resolveFloatValue($key ?? 'fake');

        // Validate
        $this->assertSame($expectedValue ?? 0.00, $result);
    }

    public function booleanValueScenarioProvider(): array
    {
        return [
            'A true value with the right key' => [
                'expectedValue' => true,
                'key' => 'key',
                'value' => '1',
            ],
            'A false value with the right key' => [
                'expectedValue' => false,
                'key' => 'key',
                'value' => '0',
            ],
            'No key sey' => [
                'expectedValue' => null,
                'key' => null,
                'value' => null,
            ],
            'A null value for a key' => [
                'expectedValue' => null,
                'key' => 'key',
                'value' => null,
            ]
        ];
    }

    /**
     * @covers       \App\Shared\Infrastructure\Request\AbstractRequest::resolveBooleanOrNullValue
     *
     * @dataProvider booleanValueScenarioProvider
     */
    public function testResolveBooleanOrNullValue(?bool $expectedValue, ?string $key, ?string $value): void
    {
        // Setup
        $requestData = null !== $key ? [$key => $value] : [];
        $request = new Request([], $requestData);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest($request);

        // Execute
        $result = $abstractRequest->resolveBooleanOrNullValue($key ?? 'fake');

        // Validate
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @covers       \App\Shared\Infrastructure\Request\AbstractRequest::resolveBooleanValue
     *
     * @dataProvider booleanValueScenarioProvider
     */
    public function testResolveBooleanValue(?bool $expectedValue, ?string $key, ?string $value): void
    {
        // Setup
        $requestData = null !== $key ? [$key => $value] : [];
        $request = new Request([], $requestData);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest($request);

        // Execute
        $result = $abstractRequest->resolveBooleanValue($key ?? 'fake');

        // Validate
        $this->assertSame($expectedValue ?? false, $result);
    }

    private function getAbstractRequest(Request $request): AbstractRequest
    {
        return new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {

            public function constraints(): array
            {
                return [
                    'name' => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request->all();
            }
        };
    }
}