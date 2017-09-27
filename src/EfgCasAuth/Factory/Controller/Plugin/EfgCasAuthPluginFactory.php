<?php

namespace EfgCasAuth\Factory\Controller\Plugin;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceLocatorInterface;
//use ZfcUser\Authentication\Adapter;
use EfgCasAuth\Controller\Plugin\EfgCasAuthPlugin;

class EfgCasAuthPluginFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        /* @var $pluginManager PluginManager */
        $serviceManager = $pluginManager->getServiceLocator();
        /* @var $authService AuthenticationService */
        $authService = $serviceManager->get('Zend\Authentication\AuthenticationService');
        /* @var $authAdapter Adapter\AdapterChain */
//        $authAdapter = $serviceManager->get('ZfcUser\Authentication\Adapter\AdapterChain');
        $controllerPlugin = new EfgCasAuthPlugin();
        $controllerPlugin
            ->setAuthService($authService)
//                ->setAuthAdapter($authAdapter)
        ;
        return $controllerPlugin;
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

        /* @var $authService AuthenticationService */
        $authService = $container->get(AuthenticationService::class);

        $controllerPlugin = new EfgCasAuthPlugin();
        $controllerPlugin
            ->setAuthService($authService)
        ;
        return $controllerPlugin;
    }
}
