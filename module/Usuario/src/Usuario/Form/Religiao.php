<?php
namespace Usuario\Form;

use Zend\Form\Form;

class Religiao extends Form
{
	public function __construct()
	{
		parent::__construct('religiao');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', 'usuario/religiao/save');

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden'
			),			
		));

		$this->add(array(
			'name' => 'nome',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Nome'
			),
		));

		$this->add(array(
			'name' => 'ativo',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select'
			),
			'options' => array(
				'label' => 'Ativo',
				'value_options' => array(
					true	=> 'Ativo',
					false => 'Inativo',					
				),
			),
			'attributes' => array(
				'value' => 't'
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