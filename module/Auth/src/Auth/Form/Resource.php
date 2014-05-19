<?php
namespace Auth\Form;

use Zend\Form\Form;

class Resource extends Form
{
	public function __construct()
	{
		parent::__construct('resource');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/auth/resource/save');
		$this->setAttribute('role', 'form');		

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden',
			),
		));

		$this->add(array(
			'name' => 'nome',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control',				
				'placeholder' => 'Digite um nome para o recurso',
				'autofocus' => 'autofocus'
			),			
			'options' => array(
				'label' => 'Nome ',				
			),
		));

		$this->add(array(
			'name' => 'descricao',
			'attributes' => array(
				'type' => 'textarea',
				'class' => 'form-control',
				'placeholder' => 'Descrição do recurso',				
			),
			'options' => array(
				'label' => 'Descrição'
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