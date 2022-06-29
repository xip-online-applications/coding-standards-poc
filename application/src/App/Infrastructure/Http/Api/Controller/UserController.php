<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Controller;

use Symfony\Component\HttpFoundation\Response;
use XIP\App\Infrastructure\Http\Api\Content\UserJsonContent;
use XIP\App\Infrastructure\Http\Content\UserContentFactory;
use XIP\Shared\Domain\Http\Response\ResponseFactoryInterface;

class UserController
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory
    ) {
    }
    
    public function show(
        int $userId,
        UserContentFactory $userContentFactory,
        UserJsonContent $userJsonContent
    ): Response {
        return $this->responseFactory->lastModifiedResponse(
            static fn(): \DateTimeInterface => $userContentFactory->getLastUpdatedAt($userId),
            static fn(): string => $userContentFactory->setUserContent($userJsonContent)->build($userId)
        );
    }
}