<?php

namespace EfgCasAuth\Factory\Authentication;

use DoctrineModule\Options\Authentication;
use DoctrineModule\Service\Authentication\AdapterFactory as BaseAdapterFactory;
use EfgCasAuth\Adapter\ObjectRepository;
use Interop\Container\ContainerInterface;
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
        
        $config = $serviceLocator->get('Config');
        
        $objectRepository = new ObjectRepository($options);
        $objectRepository->setConfig($config);
        
        return $objectRepository;
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
        /* @var $options Authentication */
        $options = $this->getOptions($container, 'authentication');
        if (is_string($objectManager = $options->getObjectManager())) {
            $options->setObjectManager($container->get($objectManager));
        }

        $config = $container->get('Config');

        $objectRepository = new ObjectRepository($options);
        $objectRepository->setConfig($config);

        return $objectRepository;
    }
}
