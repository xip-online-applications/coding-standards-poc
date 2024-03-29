<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\Shared\Infrastructure\Request;

use XIP\Shared\Domain\Exception\ConstraintViolationException;
use XIP\Shared\Infrastructure\Http\Request\AbstractRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest
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

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::validate
     */
    public function testValidate(): void
    {
        // Setup
        $inputData = ['name' => 'Patrick Batenburg'];
        $request = new Request([], $inputData);
        $this->requestStackMock->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = $this->getAbstractRequest();
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
        $abstractRequest = $this->getAbstractRequest();
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
                'key' => 'name',
                'value' => 'string',
            ],
            'No key sey' => [
                'expectedValue' => null,
                'key' => null,
                'value' => null,
            ],
            'A null value for a key' => [
                'expectedValue' => null,
                'key' => 'name',
                'value' => null,
            ]
        ];
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::resolveStringOrNullValue
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
        $abstractRequest = new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {
            private const KEY_NAME = 'name';

            public function constraints(): array
            {
                return [
                    self::KEY_NAME => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }

            public function getName(): ?string
            {
                return $this->resolveStringOrNullValue(self::KEY_NAME);
            }
        };

        // Execute
        $result = $abstractRequest->getName();

        // Validate
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::resolveStringValue
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
        $abstractRequest = new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {
            private const KEY_NAME = 'name';

            public function constraints(): array
            {
                return [
                    self::KEY_NAME => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }

            public function getName(): string
            {
                return $this->resolveStringValue(self::KEY_NAME);
            }
        };

        // Execute
        $result = $abstractRequest->getName();

        // Validate
        $this->assertSame($expectedValue ?? '', $result);
    }

    public function intValueScenarioProvider(): array
    {
        return [
            'An int value with the right key' => [
                'expectedValue' => 1,
                'key' => 'age',
                'value' => '1',
            ],
            'No key sey' => [
                'expectedValue' => null,
                'key' => null,
                'value' => null,
            ],
            'A null value for a key' => [
                'expectedValue' => null,
                'key' => 'age',
                'value' => null,
            ]
        ];
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::resolveIntOrNullValue
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
        $abstractRequest = new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {
            private const KEY_AGE = 'age';

            public function constraints(): array
            {
                return [
                    self::KEY_AGE => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }

            public function getAge(): ?int
            {
                return $this->resolveIntOrNullValue(self::KEY_AGE);
            }
        };

        // Execute
        $result = $abstractRequest->getAge();

        // Validate
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::resolveIntValue
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
        $abstractRequest = new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {
            private const KEY_AGE = 'age';

            public function constraints(): array
            {
                return [
                    self::KEY_AGE => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }

            public function getAge(): int
            {
                return $this->resolveIntValue(self::KEY_AGE);
            }
        };

        // Execute
        $result = $abstractRequest->getAge();

        // Validate
        $this->assertSame($expectedValue ?? 0, $result);
    }

    public function floatValueScenarioProvider(): array
    {
        return [
            'An int value with the right key' => [
                'expectedValue' => 1.00,
                'key' => 'price',
                'value' => '1',
            ],
            'An float value with the right key' => [
                'expectedValue' => 1.00,
                'key' => 'price',
                'value' => '1.00',
            ],
            'No key sey' => [
                'expectedValue' => null,
                'key' => null,
                'value' => null,
            ],
            'A null value for a key' => [
                'expectedValue' => null,
                'key' => 'price',
                'value' => null,
            ]
        ];
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::resolveFloatOrNullValue
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
        $abstractRequest = new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {
            private const KEY_PRICE = 'price';

            public function constraints(): array
            {
                return [
                    self::KEY_PRICE => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }

            public function getPrice(): ?float
            {
                return $this->resolveFloatOrNullValue(self::KEY_PRICE);
            }
        };

        // Execute
        $result = $abstractRequest->getPrice();

        // Validate
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::resolveFloatValue
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
        $abstractRequest = new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {
            private const KEY_PRICE = 'price';

            public function constraints(): array
            {
                return [
                    self::KEY_PRICE => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }

            public function getPrice(): float
            {
                return $this->resolveFloatValue(self::KEY_PRICE);
            }
        };

        // Execute
        $result = $abstractRequest->getPrice();

        // Validate
        $this->assertSame($expectedValue ?? 0.00, $result);
    }

    public function booleanValueScenarioProvider(): array
    {
        return [
            'A true value with the right key' => [
                'expectedValue' => true,
                'key' => 'accepted',
                'value' => '1',
            ],
            'A false value with the right key' => [
                'expectedValue' => false,
                'key' => 'accepted',
                'value' => '0',
            ],
            'No key sey' => [
                'expectedValue' => null,
                'key' => null,
                'value' => null,
            ],
            'A null value for a key' => [
                'expectedValue' => null,
                'key' => 'accepted',
                'value' => null,
            ]
        ];
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::resolveBooleanOrNullValue
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
        $abstractRequest = new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {
            private const KEY_ACCEPTED = 'accepted';

            public function constraints(): array
            {
                return [
                    self::KEY_ACCEPTED => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }

            public function isAccepted(): ?bool
            {
                return $this->resolveBooleanOrNullValue(self::KEY_ACCEPTED);
            }
        };

        // Execute
        $result = $abstractRequest->isAccepted();

        // Validate
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::resolveBooleanValue
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
        $abstractRequest = new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {
            private const KEY_ACCEPTED = 'accepted';

            public function constraints(): array
            {
                return [
                    self::KEY_ACCEPTED => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }

            public function isAccepted(): bool
            {
                return $this->resolveBooleanValue(self::KEY_ACCEPTED);
            }
        };

        // Execute
        $result = $abstractRequest->isAccepted();

        // Validate
        $this->assertSame($expectedValue ?? false, $result);
    }

    public function arrayValueScenarioProvider(): array
    {
        return [
            'A array with the right key' => [
                'expectedValue' => ['1', '2', '3'],
                'key' => 'userIds',
                'value' => ['1', '2', '3'],
            ],
            'A array value with the right key' => [
                'expectedValue' => [],
                'key' => 'userIds',
                'value' => [],
            ],
            'No key set' => [
                'expectedValue' => null,
                'key' => null,
                'value' => null,
            ],
            'A null value for a key' => [
                'expectedValue' => null,
                'key' => 'userIds',
                'value' => null,
            ]
        ];
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::resolveArrayOrNullValue
     *
     * @dataProvider arrayValueScenarioProvider
     */
    public function testResolveArrayOrNullValue(?array $expectedValue, ?string $key, ?array $value): void
    {
        // Setup
        $requestData = null !== $key ? [$key => $value] : [];
        $request = new Request([], $requestData);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {
            private const KEY_USER_IDS = 'userIds';

            public function constraints(): array
            {
                return [
                    self::KEY_USER_IDS => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }

            public function getUserIds(): ?array
            {
                return $this->resolveArrayOrNullValue(self::KEY_USER_IDS);
            }
        };

        // Execute
        $result = $abstractRequest->getUserIds();

        // Validate
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Request\AbstractRequest::resolveArrayValue
     *
     * @dataProvider arrayValueScenarioProvider
     */
    public function testResolveArrayValue(?array $expectedValue, ?string $key, ?array $value): void
    {
        // Setup
        $requestData = null !== $key ? [$key => $value] : [];
        $request = new Request([], $requestData);
        $this->requestStackMock->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);
        $abstractRequest = new class($this->requestStackMock, $this->validatorMock) extends AbstractRequest {
            private const KEY_USER_IDS = 'userIds';

            public function constraints(): array
            {
                return [
                    self::KEY_USER_IDS => [
                        new NotBlank(),
                    ],
                ];
            }

            public function validationData(): array
            {
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }

            public function getUserIds(): array
            {
                return $this->resolveArrayValue(self::KEY_USER_IDS);
            }
        };

        // Execute
        $result = $abstractRequest->getUserIds();

        // Validate
        $this->assertSame($expectedValue ?? [], $result);
    }

    private function getAbstractRequest(): AbstractRequest
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
                return $this->requestStack->getCurrentRequest()->request?->all() ?? [];
            }
        };
    }
}
