<?php

namespace EfgCasAuth\Factory\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\SessionManager;

class AuthDoctrineORMServiceFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $session = $serviceLocator->get('Zend\Session\SessionManager');
        if(!$session->isValid()){
            $session->destroy();
        }
        return $serviceLocator->get('doctrine.authenticationservice.orm_default');
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
        $session = $container->get(SessionManager::class);
        if(!$session->isValid()){
            $session->destroy();
        }
        return $container->get('doctrine.authenticationservice.orm_default');
    }
}
