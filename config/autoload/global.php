<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Session' => function($sm) {
                //return new \Zend\Session\Containter('Ieducar');                
                return new \Zend\Session\Container('ieducar2');
            },            
        ),
    ),
    'doctrine' => array(
    	'connection' => array(
            'orm_default' => array(
                //'driverClass' => '\Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(                    
                    'host' => 'servidor',
                    'port' => '5432',
                    'user' => 'postgres',
                    'password' => 'postgres',
                    'dbname' => 'ieducar'        
                ),
            ),    		
    	),
    ),    
);
