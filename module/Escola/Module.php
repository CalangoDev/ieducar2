<?php
namespace Escola;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array (
                'tipoProgressao' => 'Escola\View\Helper\TipoProgressao',
                'tipoNota' => 'Escola\View\Helper\TipoNota',
                'parecerDescritivo' => 'Escola\View\Helper\ParecerDescritivo',
                'tipoPresenca' => 'Escola\View\Helper\TipoPresenca',
                'tipoBase' => 'Escola\View\Helper\TipoBase',
                'concluinte' => 'Escola\View\Helper\Concluinte'
            ),
        );
    }
        
}
