<?php
/**
 * This file is placed here for compatibility with Zendframework 2's ModuleManager.
 * It allows usage of this module even without composer.
 * The original Module.php is in 'src/EfgCasAuth' in order to respect PSR-0
 */
require_once __DIR__ . '/src/EfgLogger/Module.php';

namespace EfgCasAuth;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

//    public function getAutoloaderConfig()
//    {
//        return array(
//            'Zend\Loader\StandardAutoloader' => array(
//                'namespaces' => array(
//                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
//                ),
//            ),
//        );
//    }
}
