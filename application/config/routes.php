<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use XIP\App\Infrastructure\Http\Api\Routing\ApiRoutesBuilder;
use XIP\App\Infrastructure\Http\Api\Routing\UserRoutesBuilder as UserApiRoutesBuilder;
use XIP\App\Infrastructure\Http\Web\Routing\UserRoutesBuilder as UserWebRoutesBuilder;
use XIP\App\Infrastructure\Http\Web\Routing\WebRoutesBuilder;
use XIP\Shared\Infrastructure\Http\Routing\RoutesBuilder;

return function (RoutingConfigurator $routingConfigurator) {
    (new RoutesBuilder(
        new WebRoutesBuilder(
            new UserWebRoutesBuilder(),
        ),
        new ApiRoutesBuilder(
            new UserApiRoutesBuilder(),
        ),
    ))->build($routingConfigurator);
};
