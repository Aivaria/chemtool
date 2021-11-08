<?php

declare(strict_types=1);

use FastRoute\RouteCollector;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;

error_reporting(E_ALL & ~E_USER_DEPRECATED & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
chdir(dirname(__DIR__));
$container = require "src/bootstrap.php";

/**
 * @var Application $app ;
 */
$app = $container->get(Application::class);

$factory = $container->get(MiddlewareFactory::class);

(require 'config/pipeline.php')($app, $factory, $container);
(require 'config/routes.php')($app, $factory, $container);

try {
    $app->run();
} catch (Throwable $exception) {
    echo $exception->getMessage();
}