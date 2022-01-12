<?php

namespace Chemtool\Webservice;

use Psr\Container\ContainerInterface;

class JsonErrorResponseGeneratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return JsonErrorResponseGenerator
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container):JsonErrorResponseGenerator
    {
        $config = $container->get('config');
        $debug = $config['debug']??false;
        return new JsonErrorResponseGenerator($debug);

    }
}