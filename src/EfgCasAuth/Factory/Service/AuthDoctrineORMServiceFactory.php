<?php

namespace EfgCasAuth\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthDoctrineORMServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $session = $serviceLocator->get('Zend\Session\SessionManager');
        if(!$session->isValid()){
            $session->destroy();
        }
        return $serviceLocator->get('doctrine.authenticationservice.orm_default');
    }
}
