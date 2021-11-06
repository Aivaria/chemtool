<?php
namespace Chemtool\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;

class EntityManagerFactory
{
    public function __invoke(ContainerInterface $container):EntityManagerInterface
    {
        $config = $container->get('config');
        $entityMetaDataConfig = Setup::createAnnotationMetadataConfiguration([dirname(__DIR__).'/Doctrine/Entities/'], true, null, null, false);
        return EntityManager::create($config['doctrine'], $entityMetaDataConfig);
    }
}