<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/12/15
 * Time: 14:21
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class Habilitacao extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('habilitacao');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/habilitacao/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\Habilitacao());

        $this->setUseInputFilterDefaults(false);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Nome:'
            ),
        ));

        $this->add(array(
            'name' => 'descricao',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Descrição',
            )
        ));

        $this->add(array(
            'name' => 'instituicao',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'instituicao'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Instituição:',
                //'empty_option' => 'Selecione',
                'allow_empty' => true,
                'continue_if_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Instituicao',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('ativo' => true),
                        'orderBy' => array('nome' => 'ASC')
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label'   => 'Selecione',
            ),
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