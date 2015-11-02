<?php
namespace Usuario\Form;

use Zend\Form\Form;

class Ocupacao extends Form
{
	public function __construct()
	{
		parent::__construct('ocupacao');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', 'usuario/ocupacao/save');

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
				'value' => 'Salvar',
				'id' => 'submitbutton',
			),			
		));

	}
}