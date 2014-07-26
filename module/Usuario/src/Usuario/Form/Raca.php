<?php
namespace Usuario\Form;

use Zend\Form\Form;

class Raca extends Form
{
	public function __construct()
	{
		parent::__construct('raca');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/usuario/raca/save');

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
				'autofocus' => 'autofocus'
			),
			'options' => array(
				'label' => 'Nome'
			),
		));

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Enviar',
				'id' => 'submitbutton',
				'class' => 'btn btn-lg btn-primary',
			),			
		));

	}
}