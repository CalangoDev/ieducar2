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
                    'host' => 'localhost',
                    'port' => '5433',
                    'user' => 'ieducar',
                    'password' => 'ieducar',
                    'dbname' => 'ieducar_test'
                ),
            ),
    	),
    ),    
);
