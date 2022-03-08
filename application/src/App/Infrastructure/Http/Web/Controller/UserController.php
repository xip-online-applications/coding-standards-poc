<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Controller;

use XIP\App\Infrastructure\Http\Web\Request\UserRequest;
use XIP\User\Domain\DataTransferObject\User as UserDto;
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
    
    public function exists(string $mail): void
    {
        dd(
            $this->userRepository->exists($mail)
        );
    }
    
    public function store(UserRequest $userRequest): void
    {
        dd(
            $this->userRepository->store(
                new UserDto(
                    $userRequest->resolveStringValue(UserRequest::KEY_NAME),
                    $userRequest->resolveStringValue(UserRequest::KEY_EMAIL),
                    null,
                    array_map(
                        static fn(string $number): int => (int)$number,
                        $userRequest->resolveArrayValue(UserRequest::KEY_ROLES)
                    )
                )
            )
        );
    }
    
    public function update(UserRequest $userRequest, int $userId): void
    {
        $user = $this->userRepository->findOrFailById($userId);
        
        dd(
            $this->userRepository->update(
                new UserDto(
                    $userRequest->resolveStringValue(UserRequest::KEY_NAME),
                    $userRequest->resolveStringValue(UserRequest::KEY_EMAIL),
                    null,
                    array_map(
                        static fn(string $number): int => (int)$number,
                        $userRequest->resolveArrayValue(UserRequest::KEY_ROLES)
                    )
                ),
                $user
            )
        );
    }
    
    public function delete(int $userId): void
    {
        $user = $this->userRepository->findOrFailById($userId);
        
        dd(
            $this->userRepository->delete($user)
        );
    }
}
