<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Request;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use XIP\Shared\Infrastructure\Http\Request\AbstractRequest;

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
