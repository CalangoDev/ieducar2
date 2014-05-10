<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * 
 * REFERENCIA DE AUTH NO USO DE DOCTRINE
 * https://github.com/doctrine/DoctrineModule/blob/master/docs/authentication.md
 */

namespace Auth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {        
        $moduleManager = $e->getApplication()->getServiceManager()->get('modulemanager');
        
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', \Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'mvcPreDispatch'), 100);
    }

    /**
     * Verifica se precisa fazer a autorização do acesso
     * @param MvcEvent $event Evento
     */
    
    public function mvcPreDispatch($event)
    {        
        $di = $event->getTarget()->getServiceLocator();
        $routeMatch = $event->getRouteMatch();
        $moduleName = $routeMatch->getParam('module');
        $controllerName = $routeMatch->getParam('controller');
        $actionName = $routeMatch->getParam('action');

        $authService = $di->get('Auth\Service\Auth');        
        if (!$authService->authorize($moduleName, $controllerName, $actionName)){            
            $redirect = $event->getTarget()->redirect();
            /**
             * So redireciona para /auth se o usuario nao estiver logado
             * caso esteja logado, redirecionar para uma tela de permissao negada
             */
            $auth = new AuthenticationService();        
            if ($auth->hasIdentity()){
                //formatar uma tela bonita depois e mandar o redirect para a mesma
                throw new \Exception("Você não tem permissão para acessar este recurso");
            } else {
               $redirect->toUrl('/auth'); 
            }
        } 
        
        return true;      
    }
    

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
        
}
