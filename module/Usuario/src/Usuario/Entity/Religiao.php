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
 * Entidade Religiao
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Usuario
 * @subpackage  Religiao
 * @version  0.1
 * @example  Classe Religiao
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""cadastro"".""religiao""")
 * @ORM\HasLifecycleCallbacks
 */
class Religiao extends Entity
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="cod_religiao", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="cadastro.seq_escolaridade", initialValue=1, allocationSize=1)
	 */
	protected $id;

	/**
	 * @var string $nm_religiao Nome da religiao
	 * 
	 * @ORM\Column(name="nm_religiao", type="string", length=50, nullable=false)	 
	 */
	protected $nome;

	/**
	 * @var datetime  $data_cadastro	Data de Cadastro
	 * 
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	protected $data_cadastro;

	/**
	 * @var datetime $data_exclusao	Data de Exclusão
	 * 
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $data_exclusao;

	/**
	 * @var boolean $ativo
	 * 
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $ativo;

	/**
	 * @var int $pessoa_exclu id da pessoa que excluiu o registro
	 * 
	 * @todo  Verificar a necessidade dessa informação
	 * 
	 * @ORM\ManyToOne(targetEntity="Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_exc", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa_exclu;

	/**
	 * @var int $pessoa_cad Id pessoa que cadastrou o registro
	 *  	 
	 * @ORM\ManyToOne(targetEntity="Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_cad", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa_cad;


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
	
	public function setId($value)
	{
		$this->id = $this->valid("id", $value);
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
		return $this->data_cadastro;
	}
	
	public function setDataCadastro($value)
	{
		$this->data_cadastro = $this->valid("data_cadastro", $value);
	}

	public function getDataExclusao()
	{
		return $this->data_exclusao;
	}
	
	public function setDataExclusao($value)
	{
		$this->data_exclusao = $this->valid("data_exclusao", $value);
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
		return $this->pessoa_exclu;
	}
	
	public function setPessoaExclu($value)
	{
		$this->pessoa_exclu = $this->valid("pessoa_exclu", $value);
	}

	public function getPessoaCad()
	{
		return $this->pessoa_cad;
	}
	
	public function setPessoaCad($value)
	{
		$this->pessoa_cad = $this->valid("pessoa_cad", $value);
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

			$inputFilter->add($factory->createInput(array(
				'name' => 'data_cadastro',
				'required' => true,
				'filters' => array(
					// array('name' => 'StripTags'),
					// array('name' => 'StringTrim'),
				),
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));


			$inputFilter->add($factory->createInput(array(
				'name' => 'data_exclusao',
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

			// $inputFilter->add($factory->createInput(array(
			// 	'name' => 'pessoa_cad',
			// 	'required' => false,
			// 	'filters' => array(
			// 		array('name' => 'Int'),
			// 	),
			// )));

			// $inputFilter->add($factory->createInput(array(
			// 	'name' => 'pessoa_exclu',
			// 	'required' => false,
			// 	'filters' => array(
			// 		array('name' => 'Int'),
			// 	),
			// )));


			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

}