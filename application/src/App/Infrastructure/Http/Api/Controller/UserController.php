<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Controller;

use Symfony\Component\HttpFoundation\Response;
use XIP\App\Infrastructure\Http\Api\Content\UserJsonContent;
use XIP\App\Infrastructure\Http\Api\Content\UsersJsonContent;
use XIP\App\Infrastructure\Http\Content\UserContentFactory;
use XIP\App\Infrastructure\Http\Content\UsersContentFactory;
use XIP\Shared\Domain\Http\Response\ResponseFactoryInterface;

class UserController
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function index(
        UsersContentFactory $usersContentFactory,
        UsersJsonContent $usersJsonContent
    ): Response {
        return $this->responseFactory->lastModifiedResponse(
            static fn(): \DateTimeInterface => $usersContentFactory->getLastUpdatedAt(),
            static fn(): string => $usersContentFactory->build($usersJsonContent)
        );
    }
    
    public function show(
        int $userId,
        UserContentFactory $userContentFactory,
        UserJsonContent $userJsonContent
    ): Response {
        return $this->responseFactory->lastModifiedResponse(
            static fn(): \DateTimeInterface => $userContentFactory->getLastUpdatedAt($userId),
            static fn(): string => $userContentFactory->build($userId, $userJsonContent)
        );
    }
}