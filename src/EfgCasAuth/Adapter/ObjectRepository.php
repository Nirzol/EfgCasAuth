<?php

namespace EfgCasAuth\Adapter;

use DoctrineModule\Authentication\Adapter\ObjectRepository as BaseObjectRepository;
use Zend\Authentication\Result;

class ObjectRepository extends BaseObjectRepository
{

    protected $config;

    /**
     * {@inheritDoc}
     */
    public function authenticate()
    {
        $this->setup();
        
        $options = $this->options;
        $config = $this->config;
        
        $findOneBy = array(
            $options->getIdentityProperty() => $this->identity,
//            'userStatus' => 1,
//            'userPassword' => '',
        );
        
        foreach ($config['efgcasauth'] as $key => $value) {
            $findOneBy[$key] = $value;
        }
        
        $identity = $options
            ->getObjectRepository()
            ->findOneBy($findOneBy);
        if (!$identity) {
            $this->authenticationResultInfo['code'] = Result::FAILURE_IDENTITY_NOT_FOUND;
            $this->authenticationResultInfo['messages'][] = 'A record with the supplied identity could not be found.';
            return $this->createAuthenticationResult();
        }
        $authResult = $this->validateIdentity($identity);
        return $authResult;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }
}
