<?php

use Chemtool\Doctrine\EntityManagerFactory;
use Chemtool\Webservice\Handler\ChemtoolHandler;
use Chemtool\Webservice\Handler\ChemtoolHandlerFactory;
use Chemtool\Webservice\Handler\TagHandler;
use Chemtool\Webservice\Handler\TagHandlerFactory;
use Chemtool\Webservice\JsonErrorResponseGeneratorFactory;
use Chemtool\Webservice\RouterFactory;
use Doctrine\ORM\EntityManager;
use FastRoute\RouteCollector;
use Mezzio\Middleware\ErrorResponseGenerator;

return [
    'dependencies' => [
        'factories' => [
            ChemtoolHandler::class => ChemtoolHandlerFactory::class,
            TagHandler::class => TagHandlerFactory::class,
            EntityManager::class => EntityManagerFactory::class,
            ErrorResponseGenerator::class => JsonErrorResponseGeneratorFactory::class,
            RouteCollector::class => RouterFactory::class,
        ],
    ],

    'mezzio' => [
        'error_handler' => [
            'template_404' => 'error::404',
            'template_error' => 'error::error',
        ]
    ],

    'doctrine' => [
        'dbname' => 'chemtool',
        'user' => 'chemtool',
        'password' => 'wE8zmAHxNvS18rob',
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
    ],
    'templates' => [
        'paths' => [
            'chemtool' => ['templates/chemtool'],
            'tagtool' => ['templates/tagtool'],
            'error' => ['templates/error'],
            'layout' => ['templates/layout'],
        ]
    ],

    'debug' => true,
];