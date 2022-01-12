<?php

namespace Chemtool\Webservice\Handler;

use Doctrine\ORM\EntityManager;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ChemtoolHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return ChemtoolHandler
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ChemtoolHandler
    {
        return new ChemtoolHandler($container->get(TemplateRendererInterface::class), $container->get(EntityManager::class));
    }

}