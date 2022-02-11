<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Exception;

use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ConstraintViolationException extends RuntimeException
{
    private ConstraintViolationListInterface $constraintViolationList;

    #[Pure] public static function create(ConstraintViolationListInterface $constraintViolationList): self
    {
        $instance = new self();

        $instance->constraintViolationList = $constraintViolationList;

        return $instance;
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}
