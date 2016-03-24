<?php

return array(
    'controllers' => array(
        'factories' => array(
            'EfgCasAuth\Controller\Auth' => 'EfgCasAuth\Factory\Controller\AuthControllerFactory',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'efgCasAuthPlugin' => 'EfgCasAuth\Factory\Controller\Plugin\EfgCasAuthPluginFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => \Zend\Mvc\Router\Http\Literal::class,
                'options' => array(
                    'route' => '/api/login',
                    'defaults' => array(
                        'controller' => 'EfgCasAuth\Controller\Auth',
                        'action' => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => \Zend\Mvc\Router\Http\Literal::class,
                'options' => array(
                    'route' => '/api/logout',
                    'defaults' => array(
                        'controller' => 'EfgCasAuth\Controller\Auth',
                        'action' => 'logout',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Authentication\AuthenticationService' => 'EfgCasAuth\Factory\Service\AuthDoctrineORMServiceFactory',
        )
    ),
    'doctrine_factories' => array(
        'authenticationadapter' => 'EfgCasAuth\Factory\Authentication\AdapterFactory',
    ),
);
