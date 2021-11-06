<?php

namespace Chemtool\Webservice;

use Psr\Container\ContainerInterface;

class JsonErrorResponseGeneratorFactory
{
    public function __invoke(ContainerInterface $container):JsonErrorResponseGenerator
    {
        $config = $container->get('config');
        $debug = $config['debug']??false;
        return new JsonErrorResponseGenerator($debug);

    }
}