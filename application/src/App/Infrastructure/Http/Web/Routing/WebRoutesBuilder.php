<?php

declare(strict_types=1);

namespace XIP\App\Infrastructure\Http\Web\Routing;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use XIP\Shared\Domain\Http\Routing\RoutesBuilderInterface;
use XIP\Shared\Domain\Http\Routing\WebRoutesBuilderInterface;

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
