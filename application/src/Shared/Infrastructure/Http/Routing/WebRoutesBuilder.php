<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Routing;

use App\Shared\Domain\Http\Routing\RoutesBuilderInterface;
use App\Shared\Domain\Http\Routing\WebRoutesBuilderInterface;
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