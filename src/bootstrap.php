<?php

namespace Chemtool;

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use Laminas\ServiceManager\ServiceManager;
use Mezzio\ConfigProvider;

require 'vendor/autoload.php';
$configProvider = new PhpFileProvider('config/autoload/dev/*.php');
$globalConfigProvider = new PhpFileProvider('config/autoload/{,*.}global.php');

$configAggregator = new ConfigAggregator([
    \Laminas\HttpHandlerRunner\ConfigProvider::class,
    \Mezzio\Router\LaminasRouter\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \Mezzio\Helper\ConfigProvider::class,
    \Mezzio\LaminasView\ConfigProvider::class,
    ConfigProvider::class,

    $globalConfigProvider,
    $configProvider
]);

$config = $configAggregator->getMergedConfig();

$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

return new ServiceManager($dependencies);