<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 22/09/16
 * Time: 10:34
 */
return [
    'controllers' => [
        'invokables' => [
            'Api\Controller\Pessoa' => 'Api\Controller\PessoaController',
        ],
    ],
    'router' => [
        'routes' => [
            'api-pessoa' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/api/v1/pessoa[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Api\Controller\Pessoa'
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy'
        ],
        'template_path_stack' => [
            'api' => __DIR__ . '/../view',
        ]
    ]
];