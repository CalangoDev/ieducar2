<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/01/16
 * Time: 10:40
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class Serie extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('serie');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/serie/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\Serie());

        $this->setUseInputFilterDefaults(false);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'curso',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'curso'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Curso: ',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Curso',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('nome' => 'ASC')
                    ),
                ),

            ),
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Série:'
            )
        ));

        $this->add(array(
            'name' => 'etapaCurso',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Etapa Curso:'
            ),
            'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                'class' => 'form-control',
                'required' => 'required'
            )
        ));

        $this->add(array(
            'name' => 'regraAvaliacao',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'regraAvaliacao'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Regra Avaliação: ',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\RegraAvaliacao',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('nome' => 'ASC')
                    ),
                ),

            ),
        ));

        $this->add(array(
            'name' => 'concluinte',
            'options' => array(
                'label' => 'Concluinte:',
                'value_options' => array(
                    '0' => 'Não',
                    '1' => 'Sim',
                ),
            ),
            'type' => 'Zend\Form\Element\Radio',
            'attributes' => array(
                'value' => '0',
                'type' => 'Zend\Form\Element\Radio',
            )
        ));

        $this->add(array(
            'name' => 'cargaHoraria',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Carga Horária:'
            )
        ));

        $this->add(array(
            'name' => 'diasLetivos',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Dias Letivos:',
                'help' => 'Somente números'
            )
        ));

        $this->add(array(
            'name' => 'intervalo',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Intervalo:',
                'help' => 'Somente números'
            )
        ));

        $this->add(array(
            'name' => 'idadeInicial',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Faixa etária:',
                'help' => 'Somente números'
            )
        ));

        $this->add(array(
            'name' => 'idadeFinal',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Faixa etária:',
                'help' => 'Somente números'
            )
        ));

        $this->add(array(
            'name' => 'observacaoHistorico',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Observação Histórico:'
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