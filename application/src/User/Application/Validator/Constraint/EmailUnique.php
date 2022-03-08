<?php

declare(strict_types=1);

namespace XIP\User\Application\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class EmailUnique extends Constraint
{
    private string $message = 'Email is not unique.';
    
    public function getMessage(): string
    {
        return $this->message;
    }
}
