<?php
namespace Auth\Form;

use Zend\Form\Form;

class Role extends Form
{
	public function __construct()
	{
		parent::__construct('role');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/auth/role/save');
		$this->setAttribute('role', 'form');		

		$this->add(array(
			'name' => 'resource',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
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
			'name' => 'submit',
			'type' => 'Zend\Form\Element\Button',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Enviar',
				'id' => 'submitbutton',	
				'class' => 'btn btn-lg btn-primary btn-block'
			),
			'options' => array(
				'label' => 'Enviar',
			),
		)); 
	}
}