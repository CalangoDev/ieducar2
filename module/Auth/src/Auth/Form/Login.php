<?php
namespace Auth\Form;

use Zend\Form\Form;

class Login extends Form
{
	public function __construct()
	{
		parent::__construct('login');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/auth/index/index');
		$this->setAttribute('role', 'form');		

		$this->add(array(
			'name' => 'matricula',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control p-10',
				'placeholder' => 'Matrícula',
				'style' => 'font-size: 16px; height: auto',
				'autofocus' => 'autofocus'
			),
			'options' => array(
				'label' => 'Matrícula'
			),
		));

		$this->add(array(
			'name' => 'senha',
			'attributes' => array(
				'type' => 'password',
				'class' => 'form-control p-10',
				'style' => 'font-size: 16px; height: auto',
				'placeholder' => 'Senha'
			),
			'options' => array(
				'label' => 'Senha'
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