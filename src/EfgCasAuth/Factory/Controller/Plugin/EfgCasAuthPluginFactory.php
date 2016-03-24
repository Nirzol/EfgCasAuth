<?php

namespace EfgCasAuth\Factory\Controller\Plugin;

use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
//use ZfcUser\Authentication\Adapter;
use EfgCasAuth\Controller\Plugin\EfgCasAuthPlugin;

class EfgCasAuthPluginFactory implements FactoryInterface
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
}
