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
        $routes = $routeCollection->all();
        $this->assertCount(7, $routes);

        $this->assertArrayHasKey('users', $routes);
        $this->assertInstanceOf(Route::class, $route = $routes['users']);
        $this->assertSame('/users', $route->getPath());
        $this->assertSame([Request::METHOD_GET], $route->getMethods());
        $this->assertSame([UserController::class, 'index'], $route->getDefaults()['_controller']);

        $this->assertArrayHasKey('users.show', $routes);
        $this->assertInstanceOf(Route::class, $route = $routes['users.show']);
        $this->assertSame('/users/{userId}/show', $route->getPath());
        $this->assertSame([Request::METHOD_GET], $route->getMethods());
        $this->assertSame([UserController::class, 'show'], $route->getDefaults()['_controller']);

        $this->assertArrayHasKey('users.email', $routes);
        $this->assertInstanceOf(Route::class, $route = $routes['users.email']);
        $this->assertSame('/users/{email}/email', $route->getPath());
        $this->assertSame([Request::METHOD_GET], $route->getMethods());
        $this->assertSame([UserController::class, 'email'], $route->getDefaults()['_controller']);

        $this->assertArrayHasKey('users.exists', $routes);
        $this->assertInstanceOf(Route::class, $route = $routes['users.exists']);
        $this->assertSame('/users/{email}/exists', $route->getPath());
        $this->assertSame([Request::METHOD_GET], $route->getMethods());
        $this->assertSame([UserController::class, 'exists'], $route->getDefaults()['_controller']);

        $this->assertArrayHasKey('users.store', $routes);
        $this->assertInstanceOf(Route::class, $route = $routes['users.store']);
        $this->assertSame('/users/store', $route->getPath());
        $this->assertSame([Request::METHOD_GET], $route->getMethods());
        $this->assertSame([UserController::class, 'store'], $route->getDefaults()['_controller']);

        $this->assertArrayHasKey('users.update', $routes);
        $this->assertInstanceOf(Route::class, $route = $routes['users.update']);
        $this->assertSame('/users/{userId}/update', $route->getPath());
        $this->assertSame([Request::METHOD_GET], $route->getMethods());
        $this->assertSame([UserController::class, 'update'], $route->getDefaults()['_controller']);

        $this->assertArrayHasKey('users.delete', $routes);
        $this->assertInstanceOf(Route::class, $route = $routes['users.delete']);
        $this->assertSame('/users/{userId}/delete', $route->getPath());
        $this->assertSame([Request::METHOD_GET], $route->getMethods());
        $this->assertSame([UserController::class, 'delete'], $route->getDefaults()['_controller']);
    }
}
