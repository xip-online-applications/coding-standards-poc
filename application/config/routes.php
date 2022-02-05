<?php

declare(strict_types=1);

use App\Shared\Infrastructure\Http\Routing\RoutesBuilder;
use App\Shared\Infrastructure\Http\Routing\WebRoutesBuilder;
use App\User\Infrastructure\Http\Routing\UserRoutesBuilder;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routingConfigurator) {
    (new RoutesBuilder(
        new WebRoutesBuilder(
            new UserRoutesBuilder(),
        ),
    ))->build($routingConfigurator);
};