<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Http\Routing;

use App\Shared\Domain\Http\Routing\WebRoutesBuilderInterface;
use App\User\Infrastructure\Http\Controller\UserController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class UserRoutesBuilder implements WebRoutesBuilderInterface
{
    public function build(RoutingConfigurator $routes): void
    {
        $routes->add('users', '/users')
            ->controller([UserController::class, 'index'])
            ->methods([Request::METHOD_GET]);
    }
}
