<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Routing;

use App\Shared\Domain\Http\Routing\RoutesBuilderInterface;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

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