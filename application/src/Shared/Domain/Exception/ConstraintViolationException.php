<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Exception;

use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ConstraintViolationException extends RuntimeException
{
    protected ConstraintViolationListInterface $constraintViolationList;

    private function __construct(ConstraintViolationListInterface $constraintViolationList)
    {
        $this->constraintViolationList = $constraintViolationList;

        parent::__construct();
    }

    public static function create(ConstraintViolationListInterface $constraintViolationList): self
    {
        return new self($constraintViolationList);
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}
