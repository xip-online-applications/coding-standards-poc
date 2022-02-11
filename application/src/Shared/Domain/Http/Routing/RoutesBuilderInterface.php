<?php

declare(strict_types=1);

namespace XIP\Shared\Domain\Http\Routing;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

interface RoutesBuilderInterface
{
    public function build(RoutingConfigurator $routes): void;
}
