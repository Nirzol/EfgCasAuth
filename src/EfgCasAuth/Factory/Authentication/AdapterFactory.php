<?php

namespace EfgCasAuth\Factory\Authentication;

use DoctrineModule\Options\Authentication;
use DoctrineModule\Service\Authentication\AdapterFactory as BaseAdapterFactory;
use EfgCasAuth\Adapter\ObjectRepository;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterFactory extends BaseAdapterFactory
{

    /**
     * {@inheritDoc}
     *
     * @return ObjectRepository
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options Authentication */
        $options = $this->getOptions($serviceLocator, 'authentication');
        if (is_string($objectManager = $options->getObjectManager())) {
            $options->setObjectManager($serviceLocator->get($objectManager));
        }
        return new ObjectRepository($options);
    }
}
