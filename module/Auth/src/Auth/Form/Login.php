<?php
namespace Auth\Form;

use Zend\Form\Form;

class Login extends Form
{
	public function __construct()
	{
		parent::__construct('login');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', 'auth/index/index');

		$this->add(array(
			'name' => 'matricula',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Matrícula'
			),
		));

		$this->add(array(
			'name' => 'senha',
			'attributes' => array(
				'type' => 'password'
			),
			'options' => array(
				'label' => 'Senha'
			),
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