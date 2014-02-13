<?php
namespace Usuario\Form;

use Zend\Form\Form;

class Fisica extends Form
{
	public function __construct()
	{
		parent::__construct('fisica');
		$this->setAttribute('method', 'post');
		$this->setAttribute('action', 'usuario/fisica/save');

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
			'name' => 'data_nasc',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Data de Nascimento',
			),
		));

		$this->add(array(
			'name' => 'sexo',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',
				'value' => 'M'
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
			'name' => 'data_uniao',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Data de União'
			),
		));

		$this->add(array(
			'name' => 'data_obito',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Data do Obito'
			),
		));

		$this->add(array(
			'name' => 'nacionalidade',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select',
				'value' => '1'
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
			'name' => 'data_chegada_brasil',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Data de chegada ao Brasil'
			),
		));

		$this->add(array(
			'name' => 'ultima_empresa',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Última Empresa'
			),
		));

		$this->add(array(
			'name' => 'nome_mae',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Nome da Mãe'
			),
		));

		$this->add(array(
			'name' => 'nome_pai',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Nome do Pai'
			),
		));

		$this->add(array(
			'name' => 'nome_conjuge',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Nome Conjuge'
			),
		));

		$this->add(array(
			'name' => 'nome_responsavel',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Nome do Responsável'
			),
		));

		$this->add(array(
			'name' => 'justificativa_provisorio',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Justificativa Provisorio'
			),
		));

		$this->add(array(
			'name' => 'cpf',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'CPF'
			),
		));


		$this->add(array(
			'name' => 'idmun_nascimento',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select'
			),
			'options' => array(
				'label' => 'Municipio de Nascimento'
			),
		));

		$this->add(array(
			'name' => 'idpais_estrangeiro',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select'
			),
			'options' => array(
				'label' => 'Pais Estrangeiro'
			),
		));

		$this->add(array(
			'name' => 'idesco',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select'
			),
			'options' => array(
				'label' => 'Escolaridade'
			),
		));

		$this->add(array(
			'name' => 'ideciv',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select'
			),
			'options' => array(
				'label' => 'Estado Civil'
			),
		));

		$this->add(array(
			'name' => 'idocup',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => 'Ocupação'
			),
		));

		$this->add(array(
			'name' => 'ref_cod_religiao',
			'attributes' => array(
				'type' => 'Zend\Form\Element\Select'
			),
			'options' => array(
				'label' => 'Religião'
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