<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Core;

return array(
	'di' => array(),
    'controllers' => array(
        'invokables' => array(
            'Core\Controller\Index' => 'Core\Controller\IndexController',
            'Core\Controller\Cep' => 'Core\Controller\CepController',
            'Core\Controller\Municipio' => 'Core\Controller\MunicipioController'
        ),
    ),
    'router' => array(
        'routes' => array(            
            'core' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/core',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Core\Controller',
                        'controller'    => 'Index',
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
                        'child_routes' => array(
                            'wildcard' => array(
                                'type' => 'Wildcard'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(        
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
           'ViewJsonStrategy',
        ),
    ),
	'doctrine' => array(
        'driver' => array(            
            __NAMESPACE__ . '_driver' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',                
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')                
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ),
            ),          
        ),
        'fixture' => array(
            __NAMESPACE__ . "_fixture" => __DIR__ . '/../src/' . __NAMESPACE__ . '/Fixture',
        ),
    ),        
);
