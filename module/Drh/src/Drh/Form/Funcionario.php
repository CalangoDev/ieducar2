<?php
namespace Drh\Form;

use DoctrineModule\Validator\NoObjectExists;
use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;


class Funcionario extends Form
{	

	public function __construct(ObjectManager $objectManager)
	{		
		parent::__construct('funcionario');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/drh/funcionario/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Drh\Entity\Funcionario());

        $this->setUseInputFilterDefaults(false);

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden'
			),			
		));

		$this->add(array(
			'name' => 'fisica',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Pessoa Física:',
				'empty_option' => 'Selecione',
				'object_manager' => $objectManager,
				'target_class' => 'Usuario\Entity\Fisica',
				'property' => 'nome',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'getFisicaNaoFuncionario',
                ),
			),
		));


		$this->add(array(
			'name' => 'matricula',
			'attributes' => array(
				'type' => 'text',
                'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Matrícula:'
			),
		));

		$this->add(array(
			'name' => 'senha',
			'attributes' => array(
				'type' => 'password',
                'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Senha:'
			),
		));

        $this->add(array(
            'name' => 'ramal',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Ramal:'
            ),
        ));

		$this->add(array(
            'type' => 'Zend\Form\Element\Select',
			'name' => 'ativo',
			'options' => array(
				'label' => 'Situação:',
				'value_options' => array(
					true => 'Ativo',
					false => 'Inativo',					
				),
			),
			'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
				'value' => true,
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;'
			),
		));


        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
			'name' => 'vinculo',
			'options' => array(
				'label' => 'Vínculo:',
				'value_options' => array(
					'' => 'Selecione',
					5 => 'Comissionado',
					4 => 'Contratado',
					3 => 'Efetivo',
					6 => 'Estágiario'
				),
			),
			'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
				'value' => '',
			),
		));

		$this->add(array(
			'name' => 'tempoExpiraConta',
			'type' => 'Zend\Form\Element\Select',
			'options' => array(
				'label' => 'Dias p/ expirar a conta:',
				'value_options' => array(
					'' => 'Selecione',
					5 => '5',					
					10 => '10',
					15 => '15',
					20 => '20',
					25 => '25',
					30 => '30',
					35 => '35',
					40 => '40',
					60 => '60',
					90 => '90',
					120 => '120',
					150 => '150',
					180 => '180',
					210 => '210',
					240 => '240',
					270 => '270',
					300 => '300',
					365 => '365'
				),
			),
			'attributes' => array(
				'value' => '',
                'type' => 'Zend\Form\Element\Select',
                'class' => 'form-control chosen-select'
			),
		));

		$this->add(array(
			'name' => 'banido',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Checkbox',
                'class' => 'form-control'
			),
			'type' => 'Zend\Form\Element\Checkbox',
			'options' => array(
				'label' => 'Banido:',
				'checked_value' => '1',
        		'unchecked_value' => '0'
			),			
		));

		$this->add(array(
			'name' => 'matriculaPermanente',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Checkbox',
                'class' => 'form-control'
			),
			'type' => 'Zend\Form\Element\Checkbox',
			'options' => array(
				'label' => 'Matrícula Permanente:',
				'checked_value' => '1',
        		'unchecked_value' => '0'
			),			
		));		

		$this->add(array(
			'name' => 'codigoSetor',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control'
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Setor',
				'empty_option' => 'Selecione',
				'object_manager' => $objectManager,
				'target_class' => 'Drh\Entity\Setor',
				'property' => 'nome',
			),
		));

		$this->add(array(
            'name' => 'superAdmin',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Checkbox',
                'class' => 'form-control'
            ),
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Super Admin',
                'checked_value' => '1',
                'unchecked_value' => '0'
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