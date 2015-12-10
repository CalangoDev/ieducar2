<?php
namespace Drh\Form;

use Zend\Form\Form;
use Doctrine\ORM\EntityManager;

class Setor extends Form
{
	public function __construct(EntityManager $em)
	{
		parent::__construct('setor');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/drh/setor/save');

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden'
			),			
		));

		$this->add(array(
			'name' => 'parentSetor',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Setor Pai:',
				'empty_option' => 'Selecione um setor já cadastrado',
				'object_manager' => $em,
				'target_class' => 'Drh\Entity\Setor',
				'property' => 'nome',
			),
		));

		$this->add(array(
			'name' => 'nome',
			'attributes' => array(
				'type' => 'text',
                'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Nome do Setor:'
			),
		));

		
		$this->add(array(
			'name' => 'sigla',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Sigla do Setor:'
			),
		));


		$this->add(array(
			'name' => 'endereco',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Endereço:'
			),
		));

		$this->add(array(
			'name' => 'ativo',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Ativo:',
				'value_options' => array(
					0 => 'Inativo',
					1 => 'Ativo'					
				),
			),
			'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
				'value' => 1
			),
		));

        $this->add(array(
           'name' => 'nivel',
            'attributes' => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Nivel:'
            )
        ));

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Enviar',
				'id' => 'submitbutton',
			),			
		));

	}
}