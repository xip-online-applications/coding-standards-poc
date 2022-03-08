<?php

declare(strict_types=1);

namespace XIP\User\Application\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class RolesExist extends Constraint
{
    private string $message = 'Role does not exist.';

    public function getMessage(): string
    {
        return $this->message;
    }
}
