<?php

declare(strict_types=1);

namespace XIP\Shared\Infrastructure\Http\Request;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use XIP\Shared\Domain\Exception\ConstraintViolationException;

abstract class AbstractRequest
{
    public function __construct(
        protected RequestStack $requestStack,
        private ValidatorInterface $validator,
    ) {
    }

    /**
     * @return array<string, array<int, Constraint>>
     */
    abstract public function constraints(): array;

    /**
     * @return array<string, mixed>
     */
    abstract public function validationData(): array;

    final public function validate(): void
    {
        $constraintViolationList = $this->validator->validate(
            $this->validationData(),
            $this->constraintsCollection()
        );

        if (0 !== $constraintViolationList->count()) {
            throw ConstraintViolationException::create($constraintViolationList);
        }
    }

    final protected function resolveStringOrNullValue(string $key): ?string
    {
        $value = $this->resolveValue($key);

        if (null === $value) {
            return null;
        }

        return (string)$value;
    }

    final protected function resolveStringValue(string $key): string
    {
        return (string)$this->resolveStringOrNullValue($key);
    }

    final protected function resolveIntOrNullValue(string $key): ?int
    {
        $value = $this->resolveValue($key);

        if (null === $value) {
            return null;
        }

        return (int)$value;
    }

    final protected function resolveIntValue(string $key): int
    {
        return (int)$this->resolveIntOrNullValue($key);
    }

    final protected function resolveFloatOrNullValue(string $key): ?float
    {
        $value = $this->resolveValue($key);

        if (null === $value) {
            return null;
        }

        return (float)$value;
    }

    final protected function resolveFloatValue(string $key): float
    {
        return (float)$this->resolveFloatOrNullValue($key);
    }

    final protected function resolveBooleanOrNullValue(string $key): ?bool
    {
        $value = $this->resolveValue($key);

        if (null === $value) {
            return null;
        }

        return (bool)$value;
    }

    final protected function resolveBooleanValue(string $key): bool
    {
        return (bool)$this->resolveBooleanOrNullValue($key);
    }

    final protected function resolveArrayOrNullValue(string $key): ?array
    {
        $value = $this->resolveValue($key);

        if (null === $value) {
            return null;
        }

        return (array)$value;
    }
    
    final protected function resolveArrayValue(string $key): array
    {
        $value = $this->resolveArrayOrNullValue($key);
        
        if (null === $value) {
            return [];
        }
        
        return $value;
    }

    private function constraintsCollection(): Collection
    {
        return new Collection($this->constraints());
    }

    private function resolveValue(string $key): mixed
    {
        $data = $this->validationData();

        if (!array_key_exists($key, $data)) {
            return null;
        }

        return $data[$key];
    }
}
