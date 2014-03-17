<?php
namespace Usuario\Form;

use Zend\Form\Form;

class EstadoCivil extends Form
{
	public function __construct()
	{
		parent::__construct('estadocivil');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', 'usuario/estadocivil/save');

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden'
			),			
		));

		$this->add(array(
			'name' => 'descricao',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Descrição'
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