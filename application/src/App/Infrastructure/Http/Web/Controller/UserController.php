<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Controller;

use XIP\App\Infrastructure\Http\Web\Request\UserRequest;

class UserController
{
    public function index(UserRequest $request): void
    {
        dd($request);
    }
}
