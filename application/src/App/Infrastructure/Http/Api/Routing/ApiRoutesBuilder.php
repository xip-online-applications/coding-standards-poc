<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Api\Routing;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use XIP\Shared\Domain\Http\Routing\ApiRoutesBuilderInterface;
use XIP\Shared\Domain\Http\Routing\RoutesBuilderInterface;

class ApiRoutesBuilder implements RoutesBuilderInterface
{
    /** @var ApiRoutesBuilderInterface[] */
    private array $apiRoutesBuilders;

    public function __construct(ApiRoutesBuilderInterface ...$apiRoutesBuilders)
    {
        $this->apiRoutesBuilders = $apiRoutesBuilders;
    }

    public function build(RoutingConfigurator $routes): void
    {
        foreach ($this->apiRoutesBuilders as $apiRoutesBuilder) {
            $apiRoutesBuilder->build($routes);
        }
    }
}
