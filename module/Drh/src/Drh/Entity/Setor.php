<?php
namespace Drh\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\PrePersist;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Doctrine\ORM\Event\LifecycleEventArgs;


/**
 * Entidade Setor
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Drh
 * @subpackage  Setor
 * @version  0.1
 * @example  Classe Setor
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="pmidrh_setor")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Setor extends Entity
{
	/**
	 * @var  int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")	 
	 */
	protected $id;

	/**
	 * @var string $nome Nome do setor
	 * 
	 * @ORM\Column(name="nm_setor", type="string", length=255, nullable=false)
	 */
	protected $nome;

	/**
	 * @var int $ref_cod_pessoa_exc Ref da Pessoa(funcionario) que excluiu o registro
	 * 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Fisica", cascade={"persist"})
	 * @ORM\JoinColumn(name="ref_cod_pessoa_exc", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoaExclu;

	/**
	 * @var int $ref_cod_pessoa_cad Ref da pessoa(funcionario) que cadastrou o registro
	 * 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Fisica", cascade={"persist"})
	 * @ORM\JoinColumn(name="ref_cod_pessoa_cad", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoaCad;

	/**
	 * @var int $ref_cod_setor Ref do codigo setor pai
	 * 
	 * @ORM\ManyToOne(targetEntity="Setor", cascade={"persist"})
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $refCodSetor;

	/**
	 * @var string $sigla_setor Sigla do setor
	 * 
	 * @ORM\Column(name="sgl_setor", type="string", length=15, nullable=false)
	 */
	protected $siglaSetor;

	/**
	 * @var datetime $data_cadastro 
	 * 
	 * @ORM\Column(name="data_cadastro", type="datetime", nullable=false)
	 */
	protected $dataCadastro;

	/**
	 * @var datetime $data_exclusao
	 * 
	 * @ORM\Column(name="data_exclusao", type="datetime", nullable=true)
	 */
	protected $dataExclusao;
	
	/**
	 * @var smallint $ativo 
	 * 
	 * 0 - Inativo
	 * 1 - Ativo
	 * @ORM\Column(type="smallint", nullable=false)
	 */
	protected $ativo = 1;

	/**
	 * @var smallint $nivel 
	 * 
	 * @todo  Nao sei a que tipo de nivel isso se refere no sistema
	 * @ORM\Column(type="smallint", nullable=false)
	 */
	protected $nivel = 1;

	/**
	 * @var smallint $no_paco 
	 * @todo nao sei a que se refere
	 * @ORM\Column(name="no_paco", type="smallint", nullable=true)
	 */
	protected $noPaco = 1;

	/**
	 * @var text $endereco Endereço do setor
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $endereco;

	/**
	 * @var string $tipo
	 * 
	 * Tipos
	 * 
	 * s = Secretaria
	 * a = Altarquia
	 * f = Fundação
	 * 
	 * @ORM\Column(type="string", length=1)
	 */
	protected $tipo;

	/**
	 * @var int $secretario
	 * 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Fisica", cascade={"persist"})
	 * @ORM\JoinColumn(name="ref_idpes_resp", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $secretario;

	/**
	 * Funcao para gerar o timestamp para o atributo data_cadastro, é executada antes de salvar os dados no banco
	 * @access public
	 * @return  void 
	 * @ORM\PrePersist
	 */
	public function timestamp()
	{
		if (is_null($this->getDataCadastro())) {
			$this->setDataCadastro(new \DateTime());
		}
	}

	public function setData($data)
	{		
		$this->setId( isset($data['id']) ? $data['id'] : null );
		$this->setNome( isset($data['nome']) ? $data['nome'] : null );
		$this->setPessoaExclu( isset($data['pessoaExclu']) ? $data['pessoaExclu'] : null );
		$this->setPessoaCad( isset($data['pessoaCad']) ? $data['pessoaCad'] : null );
		if (!empty($data['refCodSetor']))			
			$this->setRefCodSetor( isset($data['refCodSetor']) ? $data['refCodSetor'] : null );
		// $this->setDataCadastro( isset($data['data_cadastro']) ? $data['data_cadastro'] : null );
		$this->setDataExclusao( isset($data['dataExclusao']) ? $data['dataExclusao'] : null );
		$this->setAtivo( isset($data['ativo']) ? $data['ativo'] : null );
		$this->setNoPaco( isset($data['noPaco']) ? $data['noPaco'] : null );
		$this->setEndereco( isset($data['endereco']) ? $data['endereco'] : null );
		$this->setTipo( isset($data['tipo']) ? $data['tipo'] : null );
		if (!empty($data['secretario']))
			$this->setSecretario( isset($data['secretario']) ? $data['secretario'] : null );
		$this->setSiglaSetor( isset($data['siglaSetor']) ? $data['siglaSetor'] : null );
		$this->setNivel( isset($data['nivel']) ? $data['nivel'] : null );
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

	public function getPessoaExclu()
	{
		return $this->pessoaExclu;
	}
	
	public function setPessoaExclu($value)
	{
		$this->pessoaExclu = $this->valid("pessoaExclu", $value);
	}

	public function getPessoaCad()
	{
		return $this->pessoaCad;
	}
	
	public function setPessoaCad($value)
	{
		$this->pessoaCad = $this->valid("pessoaCad", $value);
	}

	public function getRefCodSetor()
	{
		return $this->refCodSetor;
	}
	
	public function setRefCodSetor($value)
	{
		$this->refCodSetor = $this->valid("refCodSetor", $value);
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
		$this->dataExclusao = $this->valid("dataExclusao", $value);
	}

	public function getAtivo()
	{
		return $this->ativo;
	}
	
	public function setAtivo($value)
	{
		$this->ativo = $this->valid("ativo", $value);
	}

	public function getNoPaco()
	{
		return $this->noPaco;
	}
	
	public function setNoPaco($value)
	{
		$this->noPaco = $this->valid("noPaco", $value);
	}

	public function getEndereco()
	{
		return $this->endereco;
	}
	
	public function setEndereco($value)
	{
		$this->endereco = $this->valid("endereco", $value);
	}

	public function getTipo()
	{
		return $this->tipo;
	}
	
	public function setTipo($value)
	{
		$this->tipo = $this->valid("tipo", $value);
	}

	public function getSecretario()
	{
		return $this->secretario;
	}
	
	public function setSecretario($value)
	{
		$this->secretario = $this->valid("secretario", $value);
	}

	public function getSiglaSetor()
	{
		return $this->siglaSetor;
	}
	
	public function setSiglaSetor($value)
	{
		$this->siglaSetor = $this->valid("siglaSetor", $value);
	}
	
	public function getNivel()
	{
		return $this->nivel;
	}
	
	public function setNivel($value)
	{
		$this->nivel = $this->valid("nivel", $value);
	}

	/**
	 * [$intputFilter description]
	 * @var Zend\InputFilter\InputFilter
	 */
	protected $inputFilter;

	/**
	 * Configura os filtros dos campos da entidade
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
							'max' => 255,
						),
					),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'pessoaExclu',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'pessoaCad',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'refCodSetor',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'siglaSetor',
				// 'name' => 'sigla_setor',
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
							'max' => 15,
						),
					),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'dataExclusao',
				'required' => false,				
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ativo',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'noPaco',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tipo',
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
							'max' => 1,
						),
					),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'secretario',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$this->inputFilter = $inputFilter;
		}		
		return $this->inputFilter;
	}
}