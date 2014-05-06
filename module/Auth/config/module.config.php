<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth;

return array(
    'router' => array(
        'routes' => array(            
            'auth' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/auth',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Auth\Controller',
                        'controller'    => 'index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            // 'Session' => function($sm) {
            //     return new \Zend\Session\Container('ieducar2');
            // },
            'Zend\Authentication\AuthenticationService' => function($sm) {
                //$dbAdapter = $sm->get('DbAdapter');                
                // $dbAdapter = $sm->get('doctrine.authenticationservice.orm_default');
                return $sm->get('doctrine.authenticationservice.orm_default');
                // return new Service\Auth($dbAdapter);                
            }
        ),        
    ),
    'translator' => array(
        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(            
            'Auth\Controller\Index' => 'Auth\Controller\IndexController',
            
        ),
    ),
    'view_manager' => array(        
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        // 'driver' => array(            
        //     __NAMESPACE__ . '_driver' => array(
        //         'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
        //         'cache' => 'array',                
        //         'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')                
        //     ),
        //     'orm_default' => array(                
        //         'drivers' => array(
        //             __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
        //         ),                                
        //     ),          
        // ),        
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Portal\Entity\Funcionario',
                'identity_property' => 'matricula',
                'credential_property' => 'senha',
            ),
        ),
    ),    
);
