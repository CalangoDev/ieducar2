<?php
namespace Usuario\Form;

use Zend\Form\Form;

class Juridica extends Form
{
	public function __construct()
	{
		parent::__construct('juridica');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', 'usuario/juridica/save');

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
			'name' => 'situacao',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select'
			),
			'options' => array(
				'label' => 'Situação',
				'value_options' => array(
					'A'	=> 'Ativo',
					'P' => 'Provisorio',
					'I' => 'Inativo'
				),
			),
			'attributes' => array(
				'value' => 'A'
			),
		));

		$this->add(array(
			'name' => 'cnpj',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'CNPJ'
			),
		));

		$this->add(array(
			'name' => 'insc_estadual',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Inscrição Estadual'
			),
		));

		$this->add(array(
			'name' => 'fantasia',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Nome de Fantasia'
			),
		));

		$this->add(array(
			'name' => 'capital_social',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Capital Social'
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