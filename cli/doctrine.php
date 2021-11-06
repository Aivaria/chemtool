<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

chdir(dirname(__DIR__));
$container = require 'src/bootstrap.php';

/**
 * @var EntityManager $em
 */
$em = $container->get(EntityManager::class);
ConsoleRunner::run(ConsoleRunner::createHelperSet($em),[]);