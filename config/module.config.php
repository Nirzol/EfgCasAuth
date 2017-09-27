<?php

namespace EfgCasAuth;

return [
    'controllers' => [
        'factories' => [
            \EfgCasAuth\Controller\AuthController::class => Factory\Controller\AuthControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            'efgCasAuthPlugin' => Factory\Controller\Plugin\EfgCasAuthPluginFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'login' => [
                'type' => \Zend\Router\Http\Literal::class,
                'options' => [
                    'route' => '/api/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => \Zend\Router\Http\Literal::class,
                'options' => [
                    'route' => '/api/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Factory\Service\AuthDoctrineORMServiceFactory::class,
        ]
    ],
    'doctrine_factories' => [
        'authenticationadapter' => Factory\Authentication\AdapterFactory::class,
    ],
];
