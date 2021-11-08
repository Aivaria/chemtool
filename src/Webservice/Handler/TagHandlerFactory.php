<?php

namespace Chemtool\Webservice\Handler;

use Doctrine\ORM\EntityManager;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class TagHandlerFactory
{
    public function __invoke(ContainerInterface $container): TagHandler
    {
        return new TagHandler($container->get(TemplateRendererInterface::class), $container->get(EntityManager::class));
    }

}