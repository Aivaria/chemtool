<?php

namespace Chemtool\Webservice;

use Interop\Container\ContainerInterface;
use Mezzio\Router\RouteCollectorFactory;

class RouterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $router = new RouteCollectorFactory();
        return $router;
    }
}