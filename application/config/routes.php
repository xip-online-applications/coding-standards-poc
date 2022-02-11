<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use XIP\App\Infrastructure\Http\Web\Routing\UserRoutesBuilder;
use XIP\Product\Infrastructure\Http\ProductRoutesBuilder;
use XIP\Shared\Infrastructure\Http\Routing\RoutesBuilder;
use XIP\Shared\Infrastructure\Http\Routing\WebRoutesBuilder;

return function (RoutingConfigurator $routingConfigurator) {
    (new RoutesBuilder(
        new WebRoutesBuilder(
            new UserRoutesBuilder(),
            new ProductRoutesBuilder(),
        ),
    ))->build($routingConfigurator);
};