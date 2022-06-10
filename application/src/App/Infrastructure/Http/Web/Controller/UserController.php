<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Controller;

use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use XIP\Shared\Domain\Bus\CommandBusInterface;
use XIP\Shared\Domain\Bus\QueryBusInterface;
use XIP\User\Application\Command\StoreUserCommand;
use XIP\User\Application\Query\FindUserByEmailQuery;
use XIP\User\Application\Query\FindUserQuery;
use XIP\User\Application\Query\FindUsersQuery;
use XIP\User\Application\Query\Result\UserExistsResult;
use XIP\User\Application\Query\Result\UserResult;
use XIP\User\Application\Query\Result\UsersResult;
use XIP\User\Application\Query\UserExistsQuery;
use XIP\User\Domain\DataTransferObject\User as UserDto;
use XIP\User\Infrastructure\Http\Request\UserRequest;

class UserController
{
    private QueryBusInterface $queryBus;

    private CommandBusInterface $commandBus;

    public function __construct(
        QueryBusInterface $queryBus,
        CommandBusInterface $commandBus
    ) {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }
    
    public function index(): void
    {
        $usersResult = $this->queryBus->query(new FindUsersQuery());

        if (!$usersResult instanceof UsersResult) {
            throw new UnexpectedTypeException($usersResult, UsersResult::class);
        }
        
        dd(
            $usersResult->getUsers()
        );
    }

    public function show(int $userId): void
    {
        $userResult = $this->queryBus->query(new FindUserQuery($userId));

        if (!$userResult instanceof UserResult) {
            throw new UnexpectedTypeException($userResult, UserResult::class);
        }

        dd(
            $userResult->getUser()
        );
    }
    
    public function email(string $email): void
    {
        $userResult = $this->queryBus->query(new FindUserByEmailQuery($email));

        if (!$userResult instanceof UserResult) {
            throw new UnexpectedTypeException($userResult, UserResult::class);
        }

        dd(
            $userResult->getUser()
        );
    }
    
    public function exists(string $email): void
    {
        $userExistsResult = $this->queryBus->query(new UserExistsQuery($email));
        
        if (!$userExistsResult instanceof UserExistsResult) {
            throw new UnexpectedTypeException($userExistsResult, UserExistsResult::class);
        }
        
        dd(
            $userExistsResult->exists()
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
        
        dd(
            'User store prepared in queue.'
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
