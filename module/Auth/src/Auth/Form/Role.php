<?php
namespace Auth\Form;

use Zend\Form\Form;
use Doctrine\ORM\EntityManager;

class Role extends Form
{
	public function __construct(EntityManager $em)
	{
		parent::__construct('role');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/auth/role/save');
		$this->setAttribute('role', 'form');		

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden',
			),
		));

		$this->add(array(
			'name' => 'funcionario',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
				'class' => 'form-control chosen-select',
				'tabindex' => '4',
				'data-placeholder' => 'Escolha um usuário...'
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Funcionário ',
				'empty_option' => 'Escolha um funcionário',
				'object_manager' => $em,
				'target_class' => 'Usuario\Entity\Fisica',
				'property' => 'nome',
			),
		));

		$this->add(array(
			'name' => 'resource',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
				'class' => 'form-control chosen-select'
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Recurso',
				'empty_option' => 'Selecione',
				'object_manager' => $em,
				'target_class' => 'Auth\Entity\Resource',
				'property' => 'nome',
			),
		));

		$this->add(array(
			'name' => 'privilegio',			
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Privilégio',
				'value_options' => array(
					1 => 'Negar',
					0 => 'Permitir'
					
				),
			),
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',				
				'value' => 0,
				'class' => 'form-control chosen-select'
			),			
		));
		
		$this->add(array(
			'name' => 'submit',
			'type' => 'Zend\Form\Element\Button',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Enviar',
				'id' => 'submitbutton',	
				'class' => 'btn btn-lg btn-primary'
			),
			'options' => array(
				'label' => 'Enviar',
			),
		)); 
	}
}