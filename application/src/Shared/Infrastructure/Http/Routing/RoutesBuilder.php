<?php

declare(strict_types=1);

namespace XIP\Shared\Infrastructure\Http\Routing;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use XIP\Shared\Domain\Http\Routing\RoutesBuilderInterface;

class RoutesBuilder implements RoutesBuilderInterface
{
    /** @var RoutesBuilderInterface[] */
    private array $routesBuilders;

    public function __construct(RoutesBuilderInterface ...$routesBuilders) {
        $this->routesBuilders = $routesBuilders;
    }

    public function build(RoutingConfigurator $routes): void
    {
        foreach ($this->routesBuilders as $routesBuilder) {
            $routesBuilder->build($routes);
        }
    }
}