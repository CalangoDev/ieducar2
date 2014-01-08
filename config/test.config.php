<?php
/**
 * I-Educar v2
 * 
 * Arquivo de configuração de testes
 */

return array(
	'doctrine' => array(
    	'connection' => array(
            'orm_default' => array(                
                'params' => array(
                    'host' => 'servidor',
                    'port' => '5432',
                    'user' => 'postgres',
                    'password' => 'postgres',
                    'dbname' => 'ieducar_test'
                ),
            ),
    	),
    ),    
);