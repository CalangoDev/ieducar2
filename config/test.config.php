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
                //'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'driverClass' =>'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                //'driverClass' => '\Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                     'path'=> ':memory:',
                 ),
                'params' => array(
                   //'host' => '192.168.59.103',
                   //'host' => 'servidor',
                   // 'port' => '3306',
//                    'host' => 'localhost',
//                    'port' => '5432',
//                    'user' => 'postgres',
//                    'password' => 'postgres',
  //                  'dbname' => 'ieducar_test',
    //                'host' => '127.0.0.1',
	//				'port' => '3306',
	//				'user' => 'root',
	//				'password' => '',
                ),
            ),
    	),
    ),    
);
