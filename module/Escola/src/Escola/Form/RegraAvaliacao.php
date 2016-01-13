<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 10/01/16
 * Time: 13:45
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class RegraAvaliacao extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('regra-avaliacao');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/regra-avaliacao/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\RegraAvaliacao());

        $this->setUseInputFilterDefaults(false);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Nome:'
            )
        ));

        $this->add(array(
            'name' => 'tipoNota',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label' => 'Sistema de Nota:',
                'value_options' => array(
                    '0' => 'Nenhum',
                    '1' => 'Nota numérica',
                    '2' => 'Nota conceitual',
                ),
            ),
            'attributes' => array(
                'type' => 'Zend\Form\Element\Radio',
            ),
        ));

//        $this->add(
//            [
//                'type'       => 'Radio',
//                'name'       => 'tiponota',
//                'options'    => [
//                    'label' => 'Sistema de Nota:',
//                    'value_options' => [
//                        '0' => 'merda',
//                        'gold' => 'gold',
//                        '2' => 'xuxu',
//                    ]
//                ],
//                'attributes' => [
//                    'value' => 'gold' // This set the opt 1 as selected when form is rendered
//                ]
//            ]
//        );

        $this->add(array(
            'name' => 'tabelaArredondamento',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'tabelaArredondamento'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Tabela de arredondamento de nota: ',
                //'empty_option' => 'Selecione',
                'allow_empty' => true,
                'continue_if_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\TabelaArredondamento',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('nome' => 'ASC')
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label'   => 'Selecione',
            ),
        ));

        $this->add(array(
            //'name' => 'tipoProgressao',
            'name' => 'tipoProgressao',
            'options' => array(
                'label' => 'Progressão:',
                'value_options' => array(
                    '1' => 'Continuada',
                    '2' => 'Não-continuada automática: média e presença',
                    '3' => 'Não-continuada automática: somente média',
                    '4' => 'Não-continuada manual'
                ),
            ),
            'type' => 'Zend\Form\Element\Radio',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Radio',
                //'value' => '1',
            )
        ));

        $this->add(array(
            'name' => 'media',
            'options' => array(
                'label' => 'Média final para promoção:',
                'help' => 'Informe a média necessária para promoção
do aluno, aceita até 3 casas decimais. Exemplos: 5,00; 6,725, 6.
Se o tipo de progressão for "Progressiva", esse
valor não será considerado.'
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
        ));

        $this->add(array(
            'name' => 'mediaRecuperacao',
            'options' => array(
                'label' => 'Média exame final para promoção:',
                'help' => 'Informe a média necessária para promoção
do aluno, aceita até 3 casas decimais. Exemplos: 5,00; 6,725, 6.
Desconsidere esse campo caso selecione o tipo de nota "conceitual"'
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            )
        ));

        $this->add(array(
            'name' => 'formulaMedia',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'formulaMedia'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Fórmula de cálculo da média: ',
                //'empty_option' => 'Selecione',
                'allow_empty' => true,
                'continue_if_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\FormulaMedia',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('nome' => 'ASC')
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label'   => 'Selecione',
            ),
        ));

        $this->add(array(
            'name' => 'formulaRecuperacao',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'formulaRecuperacao'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Fórmula de cálculo da média de recuperação: ',
                //'empty_option' => 'Selecione',
                'allow_empty' => true,
                'continue_if_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\FormulaMedia',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('tipoFormula' => 2),
                        'orderBy' => array('nome' => 'ASC')
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label'   => 'Não usar recuperação',
            ),
        ));

        $this->add(array(
            'name' => 'porcentagemPresenca',
            'options' => array(
                'label' => 'Porcentagem de presença:',
                'required' => 'required',
                'help' => 'A porcentagem de presença necessária para o aluno ser aprovado.
Esse valor é desconsiderado caso o campo "Progressão" esteja como
"Não progressiva automática - Somente média".
Em porcentagem, exemplo: 75 ou 80,750'
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'name' => 'parecerDescritivo',
            'options' => array(
                'label' => 'Parecer descritivo:',
                'value_options' => array(
                    '0' => 'Não usar parecer descritivo',
                    '2' => 'Um parecer por etapa e por componente curricular',
                    '3' => 'Um parecer por etapa, geral',
                    '5' => 'Uma parecer por ano letivo e por componente curricular',
                    '6' => 'Um parecer por ano letivo, geral'
                ),
            ),
            'type' => 'Zend\Form\Element\Radio',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Radio',
            )
        ));

        $this->add(array(
            'name' => 'tipoPresenca',
            'options' => array(
                'label' => 'Apuração de presença:',
                'value_options' => array(
                    '1' => 'Apura falta no geral (unificada)',
                    '2' => 'Apura falta por componente curricular',
                ),
            ),
            'type' => 'Zend\Form\Element\Radio',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Radio',
            )
        ));

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