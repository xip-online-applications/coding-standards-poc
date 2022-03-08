<?php

declare(strict_types=1);

namespace XIP\User\Application\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\User\Infrastructure\Repository\UserRepositoryInterface;

class EmailUniqueValidator extends ConstraintValidator
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EmailUnique) {
            throw new UnexpectedTypeException($constraint, EmailUnique::class);
        }
        
        if ($this->userRepository->unique($value)) {
            return;
        }

        $this->context->buildViolation($constraint->getMessage())
            ->addViolation();
    }
}