<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Controller;

use App\User\Infrastructure\Http\Request\UserRequest;

class UserController
{
    public function index(UserRequest $request): void
    {
        dd($request);
    }
}
