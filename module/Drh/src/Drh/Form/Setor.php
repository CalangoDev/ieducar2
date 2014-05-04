<?php
namespace Drh\Form;

use Zend\Form\Form;
use Doctrine\ORM\EntityManager;

class Setor extends Form
{
	public function __construct(EntityManager $em)
	{
		parent::__construct('setor');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/drh/setor/save');

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden'
			),			
		));

		$this->add(array(
			'name' => 'refCodSetor',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Setor',
				'empty_option' => 'Selecione um setor já cadastrado',
				'object_manager' => $em,
				'target_class' => 'Drh\Entity\Setor',
				'property' => 'nome',
			),
		));

		$this->add(array(
			'name' => 'nome',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Nome do Setor'
			),
		));

		
		$this->add(array(
			'name' => 'siglaSetor',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Sigla do Setor'
			),
		));

		$this->add(array(
			'name' => 'noPaco',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'No Paço'
			),
		));

		$this->add(array(
			'name' => 'endereco',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Endereço'
			),
		));

		$this->add(array(
			'name' => 'tipo',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select'
			),
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Tipo',
				'value_options' => array(
					'' => 'Selecione',
					's' => 'Secreataria',
					'a' => 'Altarquia',
					'f' => 'Fundação'
				),
			),
			'attributes' => array(
				'value' => ''
			),
		));

		$this->add(array(
			'name' => 'secretario',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Secretário(a)',
				'empty_option' => 'Selecione um secretário',
				'object_manager' => $em,
				'target_class' => 'Portal\Entity\Funcionario',
				'property' => 'nome',
			),
		));

		$this->add(array(
			'name' => 'ativo',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select'
			),
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Ativo',
				'value_options' => array(
					0 => 'Inativo',
					1 => 'Ativo'					
				),
			),
			'attributes' => array(
				'value' => 1
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