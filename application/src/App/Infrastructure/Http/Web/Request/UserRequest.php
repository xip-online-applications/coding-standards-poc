<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Request;

use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use XIP\User\Application\Validator\Constraint\EmailUnique;
use XIP\User\Application\Validator\Constraint\RolesExist;
use XIP\Shared\Infrastructure\Http\Request\AbstractRequest;

class UserRequest extends AbstractRequest
{
    public const KEY_NAME = 'name';
    public const KEY_EMAIL = 'email';
    public const KEY_PASSWORD = 'password';
    public const KEY_ROLES = 'roles';

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
}
