<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;
use Zend\Mvc\I18n\Translator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        // $eventManager        = $e->getApplication()->getEventManager();
        // $moduleRouteListener = new ModuleRouteListener();
        // $moduleRouteListener->attach($eventManager);

        $moduleManager = $e->getApplication()->getServiceManager()->get('modulemanager'); 
        // $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        // $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', \Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'mvcPreDispatch'), 100);
        
        $em = $e->getApplication()->getEventManager();
        $em->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, function($e) {
            $flashMessenger = new \Zend\Mvc\Controller\Plugin\FlashMessenger();
            if ($flashMessenger->hasMessages()) {
                $e->getViewModel()->setVariable('flashMessages', $flashMessenger->getMessages());                
            }            
        });
        /*
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $translator->addTranslationFile ( 'phpArray', './vendor/zendframework/zendframework/resources/languages/pt_BR/Zend_Validate.php' );
        //Define o local (você também pode definir diretamente no método acima
		$translator->setLocale ( 'pt_BR' );
        //Define a tradução padrão do Validator
		AbstractValidator::setDefaultTranslator ( new Translator ( $translator ) );*/
        //$translator = $serviceLocator->get('MvcTranslator');

        \Locale::setDefault('pt_BR');
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $translator->setLocale ( 'pt_BR' );
        $translator->addTranslationFile(
            'phpArray',
            './vendor/zendframework/zendframework/resources/languages/pt_BR/Zend_Validate.php',
            'default',
            'pt_BR'
        );
        AbstractValidator::setDefaultTranslator ( new Translator ( $translator ));


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
}
