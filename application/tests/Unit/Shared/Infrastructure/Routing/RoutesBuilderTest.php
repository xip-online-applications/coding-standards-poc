<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\Shared\Infrastructure\Routing;

use XIP\Shared\Domain\Http\Routing\RoutesBuilderInterface;
use XIP\Shared\Infrastructure\Http\Routing\RoutesBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * @covers \XIP\Shared\Infrastructure\Http\Routing\RoutesBuilder
 */
class RoutesBuilderTest extends TestCase
{
    private RoutesBuilderInterface|MockObject $routesBuilderMock;

    private RoutesBuilder $routesBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->routesBuilderMock = $this->createMock(RoutesBuilderInterface::class);
        $this->routesBuilder = new RoutesBuilder($this->routesBuilderMock);
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Routing\RoutesBuilder::build
     */
    public function testBuild(): void
    {
        // Setup
        $routingConfiguratorMock = $this->createMock(RoutingConfigurator::class);

        // Expectation
        $this->routesBuilderMock->expects($this->once())
            ->method('build')
            ->with($routingConfiguratorMock);

        // Execute
        $this->routesBuilder->build($routingConfiguratorMock);
    }
}
