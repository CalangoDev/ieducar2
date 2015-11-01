<?php
namespace Usuario\Form;

use Zend\Form\Form;
use Doctrine\ORM\EntityManager;
// use Usuario\Entity\EnderecoExterno as Ex;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Stdlib\Hydrator\ClassMethods;

class EnderecoExterno extends Form
{
	public function __construct(EntityManager $em = null)
	{
		parent::__construct('enderecoExterno');
		// var_dump($this);

		// $this->setHydrator(new ClassMethods(false));             
		$this->setHydrator(new DoctrineHydrator($em))
             ->setObject(new \Usuario\Entity\EnderecoExterno());

		$this->add(array(
			'name' => 'tipoLogradouro',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
				'class' => 'form-control chosen-select tipoLogradouro',
				'style' => 'height:100px;',
                'required' => 'required'
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Tipo de Logradouro:',
				'empty_option' => 'Selecione',
				'object_manager' => $em,
				'target_class' => 'Core\Entity\TipoLogradouro',
				'property' => 'descricao',
				'find_method' => array(
					'name' => 'findBy',
					'params' => array(
						'criteria' => array(),
						'orderBy' => array('descricao' => 'ASC')
					)
				)
			),
		));

		$this->add(array(
            'name' => 'apartamento',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Nº'
            ),
            'options' => array(
                'label' => 'Número Apartamento'
            )
        ));

        $this->add(array(
            'name' => 'bloco',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Informe o Bloco'
            ),
            'options' => array(
                'label' => 'Bloco'
            )
        ));

        $this->add(array(
        	'name' => 'andar', 
        	'attributes' => array(
        		'type' => 'text',
        		'class' => 'form-control',
        		'placeholder' => 'Nº'
        	),
        	'options' => array(
        		'label' => 'Andar'
        	)
        ));

        $this->add(array(
			'name' => 'numero',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control numero',		
				'placeholder' => 'Número'		
			),
			'options' => array(
				'label' => 'Número:'
			),
		));

		$this->add(array(
			'name' => 'cidade',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control cidade',		
				'placeholder' => 'Informe a Cidade'		
			),
			'options' => array(
				'label' => 'Cidade:'
			),
		));

		$this->add(array(
			'name' => 'siglaUf',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
				'class' => 'form-control chosen-select uf',
				'style' => 'height:100px;',
                'required' => 'required'
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Estado:',
				'empty_option' => 'Selecione',
				'object_manager' => $em,
				'target_class' => 'Core\Entity\Uf',
				'property' => 'nome'				
			),
		));

		$this->add(array(
			'name' => 'letra',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control letra',
				'placeholder' => 'Letra'		
			),
			'options' => array(
				'label' => 'Letra:'
			),
		));

		$this->add(array(
			'name' => 'bairro',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control bairro',
				'placeholder' => 'Informe o Bairro'		
			),
			'options' => array(
				'label' => 'Bairro:'
			),
		));

		$this->add(array(
            'name' => 'apartamento',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Nº'
            ),
            'options' => array(
                'label' => 'Número Apartamento'
            )
        ));

        $this->add(array(
            'name' => 'bloco',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Informe o Bloco'
            ),
            'options' => array(
                'label' => 'Bloco'
            )
        ));

        $this->add(array(
        	'name' => 'andar', 
        	'attributes' => array(
        		'type' => 'text',
        		'class' => 'form-control',
        		'placeholder' => 'Nº'
        	),
        	'options' => array(
        		'label' => 'Andar'
        	)
        ));

        $this->add(array(
			'name' => 'complemento',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control complemento',
				'placeholder' => 'Informe o Complemento'
			),
			'options' => array(
				'label' => 'Complemento:',							
			),
		));

        $this->add(array(
			'name' => 'logradouro',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control logradouro',		
				'placeholder' => 'Nome da Rua / Logradouro'		
			),
			'options' => array(
				'label' => 'Logradouro:',
				'object_manager' => $em,
				'target_class' => 'Usuario\Entity\EnderecoExterno',
				'property' => 'logradouro'
			),
		));

		$this->add(array(
			'name' => 'cep',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control cep',
				'style' => 'width:100px',
                'required' => 'required'
			),
			'options' => array(
				'label' => 'CEP:'
			),
		));

		$this->add(array(
			'name' => 'zonaLocalizacao',
			'attributes' => array(				
				'value' => '1',
				'class' => 'form-control chosen-select',
				'required' => 'required'
			),
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Zona Localização',
				'value_options' => array(
					'1'  => 'Urbana',
     				'2'  => 'Rural',     				
				),
			),
		));

	}
}