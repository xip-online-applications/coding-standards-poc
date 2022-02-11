<?php

declare(strict_types=1);

namespace XIP\Tests\Unit\Shared\Infrastructure\Routing;

use XIP\Shared\Domain\Http\Routing\WebRoutesBuilderInterface;
use XIP\Shared\Infrastructure\Http\Routing\WebRoutesBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * @covers \XIP\Shared\Infrastructure\Http\Routing\WebRoutesBuilder
 */
class WebRoutesBuilderTest extends TestCase
{
    private MockObject|WebRoutesBuilderInterface $webRoutesBuilderMock;

    private WebRoutesBuilder $webRoutesBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->webRoutesBuilderMock = $this->createMock(WebRoutesBuilderInterface::class);
        $this->webRoutesBuilder = new WebRoutesBuilder($this->webRoutesBuilderMock);
    }

    /**
     * @covers \XIP\Shared\Infrastructure\Http\Routing\WebRoutesBuilder::build
     */
    public function testBuild(): void
    {
        // Setup
        $routingConfiguratorMock = $this->createMock(RoutingConfigurator::class);

        // Expectation
        $this->webRoutesBuilderMock->expects($this->once())
            ->method('build')
            ->with($routingConfiguratorMock);

        // Execute
        $this->webRoutesBuilder->build($routingConfiguratorMock);
    }
}
