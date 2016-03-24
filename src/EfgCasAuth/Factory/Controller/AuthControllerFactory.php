<?php

namespace EfgCasAuth\Factory\Controller;

use EfgCasAuth\Controller\AuthController;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthControllerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator ControllerManager */
        $sm = $serviceLocator->getServiceLocator();

        $authService = $sm->get('Zend\Authentication\AuthenticationService');

        $config = $sm->get('Config');

        $authController = new AuthController($authService, $config['cas']);

        return $authController;
    }
}
