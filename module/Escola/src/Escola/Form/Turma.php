<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/09/16
 * Time: 15:49
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class Turma extends Form
{
    public function __construct(ObjectManager $objectManager, $id = null)
    {
        parent::__construct('turma');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/turma/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\Turma());

        $this->setUseInputFilterDefaults(false);

        $this->add([
            'name' => 'id',
            'attributes' => [
                'type' => 'hidden'
            ]
        ]);

        $this->add([
            'name' => 'nome',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
            ],
            'options' => [
                'label' => 'Nome:'
            ],
        ]);

        $this->add([
            'name' => 'sigla',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Sigla:'
            ]
        ]);

        $this->add([
            'name' => 'maximoAluno',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Máximo de Alunos: ',
                'help' => 'Somente números'
            ]
        ]);

        $this->add([
            'name' => 'multiSeriada',
            'attributes' => [
                'type' => 'Zend\Form\Element\Checkbox',
                'class' => 'form-control'
            ],
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => [
                'label' => 'Multi Seriada: ',
                'checked_value' => '1',
                'unchecked_value' => '0'
            ],
        ]);

        $this->add([
            'name' => 'ativo',
            'attributes' => [
                'type' => 'Zend\Form\Element\Checkbox',
                'class' => 'form-control'
            ],
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => [
                'label' => 'Ativo: ',
                'checked_value' => '1',
                'unchecked_value' => '0'
            ],
        ]);

        $this->add([
            'name' => 'horaInicial',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Hora Inicial:',
                'help' => 'hh:mm'
            ]
        ]);

        $this->add([
            'name' => 'horaFinal',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Hora Final:',
                'help' => 'hh:mm'
            ]
        ]);

        $this->add([
            'name' => 'horaInicioIntervalo',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Hora Inicio Intervalo:',
                'help' => 'hh:mm'
            ]
        ]);

        $this->add([
            'name' => 'horaFimIntervalo',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Hora Fim Intervalo:',
                'help' => 'hh:mm'
            ]
        ]);

        // nao add visivel
        $this->add([
            'name' => 'tipoBoletim',
            'attributes' => [
                'type' => 'Zend\Form\Element\Select',
                'class' => 'tipoBoletim chosen-select',
                'style' => 'height: 100px; width: 100%',
                'required' => 'required',
            ],
            'type' => 'Zend\Form\Element\Select',
            'options' => [
                'label' => 'Modelo relatório boletim: ',
                'value_options' => [
                    '' => 'Selecione um modelo',
                    '1' => 'Bimestral',
                    '2' => 'Trimestral',
                    '3' => 'Trimestral conceitual',
                    '4' => 'Semestral',
                    '5' => 'Semestral conceitual',
                    '6' => 'Semestral educação infantil',
                    '7' => 'Parecer descritivo por componentes curricular',
                    '8' => 'Parecer descritivo geral'
                ]
            ]
        ]);


        $this->add([
            'name' => 'anoLetivo',
            'attributes' => [
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'anoLetivo chosen-select',
                'style' => 'height:100px; width:100%',
                'id' => 'anoLetivo',
                'data-placeholder' => 'data-placeholder',
                'required' => 'required'
            ],
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => [
                'label' => 'Ano:',
                'empty_option' => 'Selecione um ano',
                'allow_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\AnoLetivo',
                'property' => 'ano',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy' => ['ano' => 'DESC']
                    ]
                ]
            ]
        ]);


        $this->add([
            'name' => 'dataFechamento',
            'attributes' => [
                'type' => 'text',
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Data de fechamento: ',
                'help' => 'dd/mm/aaaa'
            ]
        ]);

        if ($id) {

            $this->add([
                'name' => 'comodoPredio',
                'attributes' => [
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'class' => 'comodoPredio chosen-select',
                    'style' => 'height: 100px; width: 100%',
                    'id' => 'anoLetivo',
                    'data-placeholder' => 'data-placeholder',
                ],
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'options' => [
                    'label' => 'Sala:',
                    'empty_option' => 'Selecione uma sala',
                    'allow_empty' => true,
                    'object_manager' => $objectManager,
                    'target_class' => 'Escola\Entity\ComodoPredio',
                    'property' => 'nome',
                    'find_method' => [
                        'name' => 'findBy',
                        'params' => [
                            'criteria' => [],
                            'orderBy' => ['nome' => 'ASC']
                        ]
                    ]
                ]
            ]);
        } else {
            $this->add([
                'name' => 'comodoPredio',
                'attributes' => [
                    'type' => 'Zend\Form\Element\Select',
                    'class' => 'comodoPredio chosen-select',
                    'style' => 'height: 100px; width: 100%',
                ],
                'type' => 'Zend\Form\Element\Select',
                'options' => [
                    'label' => 'Sala: ',
                    'value_options' => [
                        '' => 'Selecione uma escola',
                    ]
                ]
            ]);
        }

        $this->add([
            'name' => 'turmaTurno',
            'attributes' => [
                'type' => 'DoctrineModule\Form\Element\ObjectRadio',
                'class' => 'turmaTurno chosen-select',
                'style' => 'height: 100px; width: 100%',
                'id' => 'turmaTurno',
                'data-placeholder' => 'data-placeholder',
                'required' => 'required'
            ],
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => [
                'label' => 'Turno: ',
                'empty_option' => 'Selecione um turno',
                'allow_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\TurmaTurno',
                'property' => 'nome',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy' => ['nome' => 'ASC']
                    ]
                ]
            ]
        ]);


        // falta ajustar esse input
        $this->add([
            'name' => 'escolaSerie',
            'attributes' => [
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'escolaSerie chosen-select',
                'style' => 'height: 100px; width: 100%',
                'id' => 'escolaSerie',
                'data-placeholder' => 'data-placeholder',
                'required' => 'required'
            ],
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => [
                'label' => 'Escola Série',
                'empty_option' => 'Selecione uma Escola Série',
                'allow_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\EscolaSerie',
                'label_generator' => function($targetEntity){
                    return $targetEntity->getEscola()->getJuridica()->getNome() . ' (' . $targetEntity->getSerie()->getNome() . ')';
                }
            ]
        ]);

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Salvar',
                'id' => 'submitbutton',
                'class' => 'btn btn-lg btn-primary'
            ),
        ));
    }
}