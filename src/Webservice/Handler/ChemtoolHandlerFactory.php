<?php

namespace Chemtool\Webservice\Handler;

use Doctrine\ORM\EntityManager;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ChemtoolHandlerFactory
{
    public function __invoke(ContainerInterface $container): ChemtoolHandler
    {
        return new ChemtoolHandler($container->get(TemplateRendererInterface::class), $container->get(EntityManager::class));
    }

}