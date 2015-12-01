<?php
namespace Usuario\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\PrePersist;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Raça
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Usuario
 * @subpackage  Raca
 * @version  0.1
 * @example  Classe Raca
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="cadastro_raca")
 * @ORM\HasLifecycleCallbacks
 */
class Raca extends Entity
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="cod_raca", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")	 
	 */
	protected $id;

	/**
	 * @var string $nome Nome da raca
	 * 
	 * @ORM\Column(name="nm_raca", type="string", length=50, nullable=false)	 
	 */
	protected $nome;

	/**
	 * @var datetime  $data_cadastro	Data de Cadastro
	 * 
	 * @ORM\Column(name="data_cadastro", type="datetime", nullable=false)
	 */
	protected $dataCadastro;

	/**
	 * @var datetime $data_exclusao	Data de Exclusão
	 * 
	 * @ORM\Column(name="data_exclusao", type="datetime", nullable=true)
	 */
	protected $dataExclusao;

	/**
	 * @var boolean $ativo
	 * 
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $ativo = true;

	/**
	 * @var int $pessoa_exclu id da pessoa que excluiu o registro
	 * 
	 * @todo  Verificar a necessidade dessa informação
	 * 
	 * @ORM\ManyToOne(targetEntity="Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_exc", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoaExclu;

	/**
	 * @var int $pessoa_cad Id pessoa que cadastrou o registro
	 *  	 
	 * @ORM\ManyToOne(targetEntity="Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_cad", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoaCad;


	/**
	 * Função para gerar o timestamp para o atributo data_cadadastro, é executada antes de salvar os dados no banco
	 * @access  public
	 * @return  void 
	 * @ORM\PrePersist
	 */
	public function timestamp()
	{
		if (is_null($this->getDataCadastro())) {			
			$this->setDataCadastro(new \DateTime());
		}		
	}

	/**
	 * getters and setters
	 */
	public function getId()
	{
		return $this->id;
	}

	public function getNome()
	{
		return $this->nome;
	}
	
	public function setNome($value)
	{
		$this->nome = $this->valid("nome", $value);
	}

	public function getDataCadastro()
	{
		return $this->dataCadastro;
	}
	
	public function setDataCadastro($value)
	{
		$this->dataCadastro = $this->valid("dataCadastro", $value);
	}

	public function getDataExclusao()
	{
		return $this->dataExclusao;
	}
	
	public function setDataExclusao($value)
	{
		// $this->dataExclusao = $this->valid("dataExclusao", $value);
		$this->dataExclusao = $value;
	}

	public function getAtivo()
	{
		return $this->ativo;
	}
	
	public function setAtivo($value)
	{
		$this->ativo = $this->valid("ativo", $value);
	}

	public function getPessoaExclu()
	{
		return $this->pessoaExclu;
	}
	
	public function setPessoaExclu($value)
	{
		$this->pessoaExclu = $value;
	}

	public function getPessoaCad()
	{
		return $this->pessoaCad;
	}
	
	public function setPessoaCad($value)
	{
		$this->pessoaCad = $this->valid("pessoaCad", $value);
	}

	/**
	 * [$inputFilter recebe os filtros]
	 * @var Zend\InputFilter\InputFilter
	 */
	protected $inputFilter;

	/**
	 * Configura os filtros dos campos da entidade
	 * 
	 * @return Zend\InputFilter\InputFilter
	 */
	public function getInputFilter()
	{
		if (!$this->inputFilter){
			$inputFilter = new InputFilter();
			$factory = new InputFactory();

			$inputFilter->add($factory->createInput(array(
				'name' => 'id',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'nome',
				'required' => true,
				'filters'	=>	array(
					array('name'	=>	'StripTags'),
					array('name'	=>	'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 50,
						),
					),
				),				
			)));

			// $inputFilter->add($factory->createInput(array(
			// 	'name' => 'dataCadastro',
			// 	'required' => true,
			// 	'filters' => array(
			// 		// array('name' => 'StripTags'),
			// 		// array('name' => 'StringTrim'),
			// 	),
			// 	'validators' => array(
			// 		'name' => new \Zend\Validator\Date(),
			// 	),
			// )));


			$inputFilter->add($factory->createInput(array(
				'name' => 'dataExclusao',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			/**
			 * @todo  ver o filtro para boolean aqui
			 */
			$inputFilter->add($factory->createInput(array(
				'name' => 'ativo',
				'required' => false,
				'filters' => array(
					array('name' => 'Boolean'),					
				),				
			)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

}