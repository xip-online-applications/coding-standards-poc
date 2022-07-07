<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use XIP\App\Infrastructure\Http\Api\Controller\UserController;
use XIP\Shared\Domain\Http\Routing\ApiRoutesBuilderInterface;

class UserRoutesBuilder implements ApiRoutesBuilderInterface
{

    public function build(RoutingConfigurator $routes): void
    {
        $routes->add('api.users', '/user')
            ->controller([UserController::class, 'index'])
            ->methods([Request::METHOD_GET]);

        $routes->add('api.users.show', '/users/{userId}')
            ->controller([UserController::class, 'show'])
            ->methods([Request::METHOD_GET]);
    }
}