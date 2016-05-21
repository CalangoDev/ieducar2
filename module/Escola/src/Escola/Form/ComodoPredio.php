<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/05/16
 * Time: 10:22
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ComodoPredio extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('comodo-predio');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/comodo-predio/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\ComodoPredio());
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
            'name' => 'area',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Área:'
            )
        ));

        $this->add(array(
            'name' => 'descricao',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Descrição:'
            )
        ));

        $this->add(array(
            'name' => 'ativo',
            'options' => array(
                'label' => 'Ativo',
                'value_options' => array(
                    '1'	=> 'Sim',
                    '0' => 'Não',
                ),
            ),
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                'value' => '1',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'comodoFuncao',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'stype' => 'height:100px',
                'id' => 'comodoFuncao'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Função do Cômodo:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\ComodoFuncao',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('nome' => 'ASC')
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'predio',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'stype' => 'height:100px',
                'id' => 'predio'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Prédio:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Predio',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('nome' => 'ASC')
                    )
                )
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