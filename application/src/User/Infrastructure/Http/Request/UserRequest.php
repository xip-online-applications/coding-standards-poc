<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Request;

use App\Shared\Infrastructure\Http\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRequest extends AbstractRequest
{
    /**
     * {@inheritDoc}
     */
    public function constraints(): array
    {
        return [
            'email' => [
                new NotBlank(),
                new Email(),
            ]
        ];
    }

    public function validationData(): array
    {
        return $this->requestStack->getCurrentRequest()->query->all();
    }
}
