<?php
namespace Drh\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class Setor extends Form
{
	public function __construct(ObjectManager $objectManager)
	{
		parent::__construct('setor');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/drh/setor/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Drh\Entity\Setor());
        $this->setUseInputFilterDefaults(false);

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden'
			),			
		));

		$this->add(array(
			'name' => 'parentSetor',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Setor Pai:',
				'empty_option' => 'Selecione um setor já cadastrado',
				'object_manager' => $objectManager,
				'target_class' => 'Drh\Entity\Setor',
				'property' => 'nome',
			),
		));

		$this->add(array(
			'name' => 'nome',
			'attributes' => array(
				'type' => 'text',
                'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Nome do Setor:'
			),
		));

		
		$this->add(array(
			'name' => 'sigla',
			'attributes' => array(
				'type' => 'text',
                'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Sigla do Setor:'
			),
		));


		$this->add(array(
			'name' => 'endereco',
			'attributes' => array(
				'type' => 'textarea',
                'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Endereço:'
			),
		));

		$this->add(array(
			'name' => 'ativo',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Ativo:',
				'value_options' => array(
					0 => 'Inativo',
					1 => 'Ativo'					
				),
			),
			'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
				'value' => 1,
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
			),
		));

        $this->add(array(
            'name' => 'nivel',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Nível:',
                'value_options' => array(
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5',
                    6 => '6',
                    7 => '7',
                    8 => '8',
                    9 => '9',
                    10 => '10',
                ),
                'empty_option' => 'Selecione um nível',
            ),
            'attributes' => array(
                'value' => '',
                'type' => 'Zend\Form\Element\Select',
                'class' => 'form-control chosen-select'
            ),
        ));

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Enviar',
                'class' => 'btn btn-lg btn-primary',
				'id' => 'submitbutton',
			),			
		));

	}
}