<?php

declare(strict_types=1);

namespace XIP\User\Application\Validator\Constraint;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\User\Infrastructure\Repository\UserRepositoryInterface;

class EmailUniqueValidator extends ConstraintValidator
{
    private UserRepositoryInterface $userRepository;
    private RequestStack $requestStack;

    public function __construct(UserRepositoryInterface $userRepository, RequestStack $requestStack)
    {
        $this->userRepository = $userRepository;
        $this->requestStack = $requestStack;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EmailUnique) {
            throw new UnexpectedTypeException($constraint, EmailUnique::class);
        }

        if ($this->emailIsNotChanged($value)) {
            return;
        }

        if ($this->userRepository->unique($value)) {
            return;
        }

        $this->context->buildViolation($constraint->getMessage())
            ->addViolation();
    }
    
    private function emailIsNotChanged(string $value): bool
    {
        $userId = $this->requestStack->getCurrentRequest()->attributes->get('userId');
        
        if (null === $userId) {
            return false;
        }
        
        $user = $this->userRepository->findOrFailById((int)$userId);
        
        return $user->getEmail() === $value;
    }
}