<?php

namespace Chemtool\Webservice\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ChemtoolHandlerFactory
{
    public function __invoke(ContainerInterface $container): ChemtoolHandler
    {
        return new ChemtoolHandler($container->get(TemplateRendererInterface::class));
    }

}