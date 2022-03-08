<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use XIP\App\Infrastructure\Http\Web\Controller\UserController;
use XIP\Shared\Domain\Http\Routing\WebRoutesBuilderInterface;

class UserRoutesBuilder implements WebRoutesBuilderInterface
{
    public function build(RoutingConfigurator $routes): void
    {
        $routes->add('users', '/users')
            ->controller([UserController::class, 'index'])
            ->methods([Request::METHOD_GET]);

        $routes->add('users.show', '/users/{userId}/show')
            ->controller([UserController::class, 'show'])
            ->methods([Request::METHOD_GET]);

        $routes->add('users.email', '/users/{email}/email')
            ->controller([UserController::class, 'email'])
            ->methods([Request::METHOD_GET]);

        $routes->add('users.exists', '/users/{email}/exists')
            ->controller([UserController::class, 'exists'])
            ->methods([Request::METHOD_GET]);

        $routes->add('users.store', '/users/store')
            ->controller([UserController::class, 'store'])
            ->methods([Request::METHOD_GET]);

        $routes->add('users.update', '/users/{userId}/update')
            ->controller([UserController::class, 'update'])
            ->methods([Request::METHOD_GET]);
        
        $routes->add('users.delete', '/users/{userId}/delete')
            ->controller([UserController::class,  'delete'])
            ->methods([Request::METHOD_GET]);
    }
}
