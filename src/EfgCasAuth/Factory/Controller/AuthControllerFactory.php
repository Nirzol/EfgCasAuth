<?php

namespace EfgCasAuth\Factory\Controller;

use EfgCasAuth\Controller\AuthController;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\SessionManager;

class AuthControllerFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator ControllerManager */
        $sm = $serviceLocator->getServiceLocator();

        $authService = $sm->get('Zend\Authentication\AuthenticationService');
        $session = $sm->get('Zend\Session\SessionManager');


        $config = $sm->get('Config');

        $authController = new AuthController($authService,$session, $config['cas']);

        return $authController;
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authService = $container->get(AuthenticationService::class);
        $session = $container->get(SessionManager::class);


        $config = $container->get('Config');

        $authController = new AuthController($authService, $session, $config['cas']);

        return $authController;
    }
}
