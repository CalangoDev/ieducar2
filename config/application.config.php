<?php
return array(    
    'modules' => array(
        'ZendDeveloperTools',
        'DoctrineModule',
        'DoctrineORMModule',
        'Usuario',
        'Historico',
        'Application',
        'Portal',
        'Drh',
        'Auth',
        'DoctrineDataFixtureModule',
        'Core',
        'WebinoImageThumb'
    ),    
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',            
        ),
    ),

);
