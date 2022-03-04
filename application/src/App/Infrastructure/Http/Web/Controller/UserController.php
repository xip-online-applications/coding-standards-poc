<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Controller;

use XIP\App\Infrastructure\Http\Web\Request\UserRequest;
use XIP\User\Infrastructure\Repository\UserRepositoryInterface;

class UserController
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function index(): void
    {
        dd(
            $this->userRepository->findAll()
        );
    }
}
