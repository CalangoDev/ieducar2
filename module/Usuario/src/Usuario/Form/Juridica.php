<?php
namespace Usuario\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class Juridica extends Form
{
	public function __construct(ObjectManager $objectManager)
	{
		parent::__construct('juridica');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/usuario/juridica/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Usuario\Entity\Juridica());
        $this->setUseInputFilterDefaults(false);

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
                'required' => 'required',
			),
			'options' => array(
				'label' => 'Razão Social:'
			),
		));

		$this->add(array(
			'name' => 'situacao',
			'options' => array(
				'label' => 'Situação:',
				'value_options' => array(
					'A'	=> 'Ativo',
					'P' => 'Provisorio',
					'I' => 'Inativo'
				),
			),
            'type' => 'Zend\Form\Element\Select',
			'attributes' => array(
				'value' => 'A',
                'class' => 'form-control',
                'type' => 'Zend\Form\Element\Select'
			),
		));

		$this->add(array(
			'name' => 'cnpj',
			'attributes' => array(
				'type' => 'text',
                'class' => 'form-control cnpj'
			),
			'options' => array(
				'label' => 'CNPJ:'
			),
		));

		$this->add(array(
			'name' => 'inscricaoEstadual',
			'attributes' => array(
				'type' => 'text',
                'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Inscrição Estadual:'
			),
		));

		$this->add(array(
			'name' => 'fantasia',
			'attributes' => array(
				'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
			),
			'options' => array(
				'label' => 'Nome de Fantasia:'
			),
		));

		$this->add(array(
			'name' => 'capitalSocial',
			'attributes' => array(
				'type' => 'text',
                'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Capital Social:'
			),
		));

        $enderecoExternoFieldset = new EnderecoExternoFieldset($objectManager);
        $enderecoExternoFieldset->setLabel('Endereço');
        $enderecoExternoFieldset->setName('enderecoExterno');
        $enderecoExternoFieldset->setUseAsBaseFieldset(false);
        $this->add($enderecoExternoFieldset);

        $telefoneFieldset = new TelefoneFieldset($objectManager);
        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name' => 'telefones',
            'options' => array(
                'label' => 'Telefones:',
                'count'           => 2,
                'target_element' => $telefoneFieldset
            ),
            'attributes' => array(
                'type'    => 'Zend\Form\Element\Collection',
            )
        ));

        $this->add(array(
            'name' => 'url',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Site',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Salvar',
				'id' => 'submitbutton',
                'class' => 'btn btn-lg btn-primary'
			),			
		));

	}
}