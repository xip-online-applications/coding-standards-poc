<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Controller;

use Symfony\Component\HttpFoundation\Response;
use XIP\App\Infrastructure\Http\Content\UserContentFactory;
use XIP\App\Infrastructure\Http\Web\Content\UsersWebContentFactory;
use XIP\App\Infrastructure\Http\Web\Content\UserWebContent;
use XIP\App\Infrastructure\Http\Web\Content\UserWebContentFactory;
use XIP\Shared\Domain\Bus\CommandBusInterface;
use XIP\Shared\Domain\Http\Response\ResponseFactoryInterface;
use XIP\User\Application\Command\StoreUserCommand;
use XIP\User\Domain\DataTransferObject\User as UserDto;
use XIP\User\Infrastructure\Http\Request\UserRequest;

class UserController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ResponseFactoryInterface $responseFactory
    ) {
    }
    
    public function index(UsersWebContentFactory $usersWebContentFactory): Response
    {
        return $this->responseFactory->lastModifiedResponse(
            static fn(): \DateTimeInterface => $usersWebContentFactory->getLastUpdatedAt(),
            static fn(): string => $usersWebContentFactory->build()
        );
    }

    public function show(int $userId, UserContentFactory $userContentFactory, UserWebContent $userWebContent): Response
    {
        return $this->responseFactory->lastModifiedResponse(
            static fn(): \DateTimeInterface => $userContentFactory->getLastUpdatedAt($userId),
            static fn(): string => $userContentFactory->setUserContent($userWebContent)->build($userId)
        );
    }
    
    public function store(UserRequest $userRequest): void
    {
        $this->commandBus->dispatch(
            new StoreUserCommand(
                new UserDto(
                    $userRequest->getName(),
                    $userRequest->getEmail(),
                    $userRequest->getPassword(),
                    $userRequest->getRoles()
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
                    $userRequest->getName(),
                    $userRequest->getEmail(),
                    $userRequest->getPassword(),
                    $userRequest->getRoles()
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
