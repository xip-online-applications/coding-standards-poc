<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Controller;

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

    public function show(int $userId): void
    {
        dd(
            $this->userRepository->findOrFailById($userId)
        );
    }
    
    public function mail(string $mail): void
    {
        dd(
            $this->userRepository->findOrFailByEmail($mail)
        );
    }
}
