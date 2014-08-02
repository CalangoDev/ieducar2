<?php
namespace Usuario\Form;

use Zend\Form\Form;
use Doctrine\ORM\EntityManager;

class Fisica extends Form
{
	public function __construct(EntityManager $em)
	{
		parent::__construct('fisica');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', '/usuario/fisica/save');
		// $this->setAttribute('class', 'form-inline');

		$this->add(array(
			'name' => 'id',
			'attributes' => array(
				'type' => 'hidden',
			),
		));

		$this->add(array(
			'name' => 'nome',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control',
				// 'style' => 'width:510px'
			),
			'options' => array(
				'label' => 'Nome'
			),
		));

		$this->add(array(
			'type' => 'Zend\Form\Element\Select',
			'name' => 'situacao',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',				
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
				'value' => 'A',
				'class' => 'form-control'
			),
		));		

		$this->add(array(
			'name' => 'dataNasc',
			'type' => 'date',
			'attributes' => array(
				'type' => 'date',
				'class' => 'form-control dataNasc'
			),
			'options' => array(
				'label' => 'Data de Nascimento',
			),
		));

		$this->add(array(
			'name' => 'sexo',
			'type' => 'Zend\Form\Element\Select',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',
				'value' => 'M',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Sexo',
				'value_options' => array(
					'M' => 'Masculino',
					'F' => 'Feminino'
				),
			),			
		));

		$this->add(array(
			'name' => 'raca',
			'attributes' => array(
				'type' => 'DoctrineModule\Form\Element\ObjectSelect',
				'class' => 'form-control chosen-select',
				'style' => 'height:100px;'
			),
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'label' => 'Raça',
				'empty_option' => 'Selecione',
				'object_manager' => $em,
				'target_class' => 'Usuario\Entity\Raca',
				'property' => 'nome',
			),
		));

		$this->add(array(
			'name' => 'dataUniao',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Data de União'
			),
		));

		$this->add(array(
			'name' => 'dataObito',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Data do Obito'
			),
		));

		$this->add(array(
			'name' => 'nacionalidade',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',
				'value' => '1',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Nacionalidade',
				'value_options' => array(
					'1'  => 'Brasileiro',
     				'2'  => 'Naturalizado brasileiro',
     				'3'  => 'Estrangeiro'
				),
			),
		));

		$this->add(array(
			'name' => 'dataChegadaBrasil',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Data de chegada ao Brasil'
			),
		));

		$this->add(array(
			'name' => 'ultimaEmpresa',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Última Empresa'
			),
		));

		$this->add(array(
			'name' => 'nomeMae',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Nome da Mãe'
			),
		));

		$this->add(array(
			'name' => 'nomePai',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Nome do Pai'
			),
		));

		$this->add(array(
			'name' => 'nomeConjuge',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Nome Conjuge'
			),
		));

		$this->add(array(
			'name' => 'nomeResponsavel',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Nome do Responsável'
			),
		));

		$this->add(array(
			'name' => 'justificativaProvisorio',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Justificativa Provisorio'
			),
		));

		$this->add(array(
			'name' => 'cpf',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control cpf',
				'pattern' => "\d{3}\.\d{3}\.\d{3}-\d{2}",
				'title' => "Digite o CPF no formato nnn.nnn.nnn-nn"
			),
			'options' => array(
				'label' => 'CPF <small>nnn.nnn.nnn-nn</small>'
			),
		));


		$this->add(array(
			'name' => 'idmunNascimento',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Municipio de Nascimento'
			),
		));

		$this->add(array(
			'name' => 'idpaisEstrangeiro',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Pais Estrangeiro'
			),
		));

		$this->add(array(
			'name' => 'idesco',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Escolaridade'
			),
		));

		$this->add(array(
			'name' => 'ideciv',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Estado Civil'
			),
		));

		$this->add(array(
			'name' => 'idocup',
			'attributes' => array(
				'type' => 'text',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Ocupação'
			),
		));

		$this->add(array(
			'name' => 'refCodReligiao',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Religião'
			),
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
				'value' => 'Enviar',
				'id' => 'submitbutton',
				'class' => 'btn btn-lg btn-primary',
			),
		));
	}
}