<?php
namespace Escola;

return array(
    'router' => array(
        'routes' => array(            
            'escola' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/escola',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Escola\Controller',
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
                        //add may terminate to navigator menu working
                        'may_terminate' => true,
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
    'controllers' => array(
        'invokables' => array(
            'Escola\Controller\Index' => 'Escola\Controller\IndexController',
            'Escola\Controller\Instituicao' => 'Escola\Controller\InstituicaoController',
            'Escola\Controller\TipoRegime' => 'Escola\Controller\TipoRegimeController',
			'Escola\Controller\TipoEnsino' => 'Escola\Controller\TipoEnsinoController',
            'Escola\Controller\Habilitacao' => 'Escola\Controller\HabilitacaoController',
			'Escola\Controller\NivelEnsino' => 'Escola\Controller\NivelEnsinoController',
			'Escola\Controller\Curso' => 'Escola\Controller\CursoController',
            'Escola\Controller\FormulaMedia' => 'Escola\Controller\FormulaMediaController',
            'Escola\Controller\TabelaArredondamento' => 'Escola\Controller\TabelaArredondamentoController',
            'Escola\Controller\RegraAvaliacao' => 'Escola\Controller\RegraAvaliacaoController',
            'Escola\Controller\AreaConhecimento' => 'Escola\Controller\AreaConhecimentoController',
            'Escola\Controller\TipoDispensa' => 'Escola\Controller\TipoDispensaController',
            'Escola\Controller\ComponenteCurricular' => 'Escola\Controller\ComponenteCurricularController',
            'Escola\Controller\Serie' => 'Escola\Controller\SerieController',
            'Escola\Controller\RedeEnsino' => 'Escola\Controller\RedeEnsinoController',
            'Escola\Controller\Localizacao' => 'Escola\Controller\LocalizacaoController',
            'Escola\Controller\Escola' => 'Escola\Controller\EscolaController',
            'Escola\Controller\EscolaSerie' => 'Escola\Controller\EscolaSerieController',
            'Escola\Controller\ComponenteCurricularAnoEscolar' => 'Escola\Controller\ComponenteCurricularAnoEscolarController',
            'Escola\Controller\SequenciaSerie' => 'Escola\Controller\SequenciaSerieController',
            'Escola\Controller\Modulo' => 'Escola\Controller\ModuloController',
            'Escola\Controller\Predio' => 'Escola\Controller\PredioController',
            'Escola\Controller\ComodoFuncao' => 'Escola\Controller\ComodoFuncaoController',
            'Escola\Controller\ComodoPredio' => 'Escola\Controller\ComodoPredioController',
            'Escola\Controller\AnoLetivo' => 'Escola\Controller\AnoLetivoController',
        ),
    ),
    'view_manager' => array(
        'template_map' => include __DIR__  .'/../template_map.php',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
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
        // 'data-fixture' => array(
        //     'location' => __DIR__ . '/../src/' . __NAMESPACE__ . '/Fixture',
        // ),
    ),
);
