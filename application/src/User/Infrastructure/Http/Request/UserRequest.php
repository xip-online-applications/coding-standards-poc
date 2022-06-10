<?php

declare(strict_types=1);

namespace XIP\User\Infrastructure\Http\Request;

use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use XIP\Shared\Infrastructure\Http\Request\AbstractRequest;
use XIP\User\Application\Validator\Constraint\EmailUnique;
use XIP\User\Application\Validator\Constraint\RolesExist;

final class UserRequest extends AbstractRequest
{
    private const KEY_NAME = 'name';
    private const KEY_EMAIL = 'email';
    private const KEY_PASSWORD = 'password';
    private const KEY_ROLES = 'roles';

    /**
     * {@inheritDoc}
     */
    public function constraints(): array
    {
        $constraints = [
            self::KEY_NAME => [
                new Required([
                    new NotBlank(),
                ]),
            ],
            self::KEY_EMAIL => [
                new Required([
                    new NotBlank(),
                    new Email(),
                    new EmailUnique(),
                ]),
            ],
            self::KEY_ROLES => [
                new Required([
                    new Type('array'),
                    new Count(['min' => 1]),
                    new RolesExist(),
                ])
            ],
        ];
        
        $userId = $this->requestStack->getCurrentRequest()?->attributes?->get('userId') ?? null;
        if (null !== $userId) {
            $constraints[self::KEY_PASSWORD] = [
                new Required([
                    new Type('string'),
                    new Length(['min' => 8]),
                ])
            ];
        }
        
        return $constraints;
    }

    public function validationData(): array
    {
        return $this->requestStack->getCurrentRequest()?->query?->all() ?? [];
    }
    
    public function getName(): string
    {
        return $this->resolveStringValue(self::KEY_NAME);
    }
    
    public function getEmail(): string
    {
        return $this->resolveStringValue(self::KEY_EMAIL);
    }
    
    public function getPassword(): ?string
    {
        return $this->resolveStringOrNullValue(self::KEY_PASSWORD);
    }
    
    public function getRoles(): array
    {
        return array_map(
            static fn(string $number): int => (int)$number,
            $this->resolveArrayValue(self::KEY_ROLES)
        );
    }
}
