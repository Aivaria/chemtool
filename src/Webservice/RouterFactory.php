<?php

namespace Chemtool\Webservice;

use Interop\Container\ContainerInterface;
use Mezzio\Router\RouteCollectorFactory;

class RouterFactory
{
    /**
     * @param ContainerInterface $container
     * @return RouteCollectorFactory
     */
    public function __invoke(ContainerInterface $container)
    {
        $router = new RouteCollectorFactory();
        return $router;
    }
}