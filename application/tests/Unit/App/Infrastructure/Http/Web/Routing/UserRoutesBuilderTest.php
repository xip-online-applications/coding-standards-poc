<?php

declare(strict_types=1);

namespace App\Tests\Unit\App\Infrastructure\Http\Web\Routing;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use XIP\App\Infrastructure\Http\Web\Controller\UserController;
use XIP\App\Infrastructure\Http\Web\Routing\UserRoutesBuilder;
use XIP\Shared\Domain\Http\Routing\WebRoutesBuilderInterface;

/**
 * @covers \XIP\App\Infrastructure\Http\Web\Routing\UserRoutesBuilder
 */
class UserRoutesBuilderTest extends TestCase
{
    private UserRoutesBuilder $userRoutesBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRoutesBuilder = new UserRoutesBuilder();
    }

    /**
     * @covers \XIP\App\Infrastructure\Http\Web\Routing\UserRoutesBuilder
     */
    public function testInstanceOf(): void
    {
        // Validate
        $this->assertInstanceOf(WebRoutesBuilderInterface::class, $this->userRoutesBuilder);
    }

    /**
     * @covers \XIP\App\Infrastructure\Http\Web\Routing\UserRoutesBuilder::build
     */
    public function testBuild(): void
    {
        // Setup
        $routeCollection = new RouteCollection();
        $routingConfiguration = new RoutingConfigurator(
            $routeCollection,
            new PhpFileLoader(
                new FileLocator(),
                'test'
            ),
            '/var/www/application/config/routes.php',
            '/var/www/application/config/routes.php',
            'test'
        );

        // Execute
        $this->userRoutesBuilder->build($routingConfiguration);

        // Validate
        $this->assertInstanceOf(RouteCollection::class, $routeCollection);
        $this->assertIsArray($routes = $routeCollection->all());
        $this->assertCount(1, $routes);
        $this->assertArrayHasKey('users', $routes);
        $this->assertInstanceOf(Route::class, $route = $routes['users']);
        $this->assertSame('/users', $route->getPath());
        $this->assertSame([Request::METHOD_GET], $route->getMethods());
        $this->assertSame([UserController::class, 'index'], $route->getDefaults()['_controller']);
    }
}