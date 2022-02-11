<?php

declare(strict_types=1);

namespace XIP\Shared\Infrastructure\Http\Routing;

use XIP\Shared\Domain\Http\Routing\RoutesBuilderInterface;
use XIP\Shared\Domain\Http\Routing\WebRoutesBuilderInterface;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class WebRoutesBuilder implements RoutesBuilderInterface
{
    /** @var WebRoutesBuilderInterface[] */
    private array $webRoutesBuilders;

    public function __construct(WebRoutesBuilderInterface ...$webRoutesBuilders)
    {
        $this->webRoutesBuilders = $webRoutesBuilders;
    }

    public function build(RoutingConfigurator $routes): void
    {
        foreach ($this->webRoutesBuilders as $webRoutesBuilder) {
            $webRoutesBuilder->build($routes);
        }
    }
}