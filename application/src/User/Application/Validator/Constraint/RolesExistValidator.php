<?php

declare(strict_types=1);

namespace XIP\User\Application\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\User\Domain\Repository\RoleRepositoryInterface;

class RolesExistValidator extends ConstraintValidator
{
    private RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof RolesExist) {
            throw new UnexpectedTypeException($constraint, RolesExist::class);
        }
        
        $value = array_map(static fn(string $value): int => (int)$value, $value);

        if ($this->roleRepository->exists($value)) {
            return;
        }
        
        $this->context->buildViolation($constraint->getMessage())
            ->addViolation();
    }
}