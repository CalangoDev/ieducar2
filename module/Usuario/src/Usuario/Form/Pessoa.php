<?php
namespace Usuario\Form;

use Zend\Form\Form;

class Pessoa extends Form
{
	public function __construct()
	{
		parent::__construct("pessoa");
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/usuario/pessoa/save');

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden',
			),
		));

		$this->add(array(
			'name' => 'nome',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Nome',
			),
		));

		$this->add(array(
			'name' => 'url',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'URL',
			),
		));

		$this->add(array(
			'name' => 'tipo',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select'
			),
			'options' => array(
				'label' => 'tipo',
				'value_options' => array(                    
                    'F' => 'Física',
                    'J' => 'Juridica',                    
                ),                
			),
			'attributes' => array(
                'value' => 'F'
            )
		));

		$this->add(array(
			'name' => 'email',
			'attributes' => array(
				'type' => 'email'
			),
			'options' => array(
				'label' => 'Email',
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
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Enviar',
				'id' => 'submitbutton',
			),
		));
	}
}