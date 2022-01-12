<?php

namespace Chemtool\Webservice\Handler;

use Doctrine\ORM\EntityManager;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class TagHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return TagHandler
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TagHandler
    {
        return new TagHandler($container->get(TemplateRendererInterface::class), $container->get(EntityManager::class));
    }

}