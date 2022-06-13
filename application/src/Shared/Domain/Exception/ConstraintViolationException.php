<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Exception;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationException extends RuntimeException
{
    private function __construct(
        private ConstraintViolationListInterface $constraintViolationList
    ) {
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
