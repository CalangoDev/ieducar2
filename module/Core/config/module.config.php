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
