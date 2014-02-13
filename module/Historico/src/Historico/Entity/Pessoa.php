<?php
namespace Historico\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\PrePersist;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Pessoa 
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Historico
 * @subpackage  Pessoa
 * @version  0.1
 * @example  Classe Pessoa
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""historico"".""pessoa""")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Pessoa extends Entity implements InputFilterAwareInterface {

	protected $inputFilter;

	/**
	 * @var  Int $id
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="SEQUENCE") 
	 * @SequenceGenerator(sequenceName="historico.seq_pessoa", initialValue=1, allocationSize=1)	 
	 */
	protected $id;

	/**
	 * @var  Int $id Identificador da entidade pessoa	 
	 * @ORM\Column(name="idpes", type="integer", nullable=false);
	 */
	protected $idpes;

	/**	 
	 * @var  String $nome Nome da Pessoa
	 * 
	 * @ORM\Column(type="string", nullable=false, length=150)
	 */
	protected $nome;

	/**
	 * @var  DateTime $data_cad Data de cadastro da pessoa
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	protected $data_cad;

	/**
	 * @var  String $url Website
	 * @ORM\Column(type="string", length=60, nullable=true)
	 */
	protected $url;

	/**
	 * @var  String $tipo F(Fisica) ou J(Juridica)
	 * //era false o nullable
	 * @ORM\Column(type="string", length=1, nullable=true)
	 */
	protected $tipo;

	/**
	 * @var  DateTime $data_rev Data de revisao do cadastro
	 * @todo  Verificar no antigo sistema a logica de quando esse dado é informado
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $data_rev;

	/**
	 * @var  String $email Email da pessoa
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $email;

	/**
	 * @var  String $situacao A(Ativo) ou P(Provisorio) ou I(Inativo)
	 * 
	 * Na versao anterior do sistema não achei em nenhum codigo alguma instrução que altere a situação para Inativo nessa tabela
	 * 
	 * @ORM\Column(type="string", length=1, nullable=false)
	 */
	protected $situacao;

	/**
	 * @var  String $origem_gravacao M(Migração) ou U(Usuário) ou C(Rotina de Confrontação) ou O(Usuário do Oscar?) Origem dos dados 
	 * 
	 * @ORM\Column(type="string", length=1, nullable=false)
	 */
	protected $origem_gravacao;

	/**
	 * @var  String $operacao I(?) ou A(?) ou E(?) - Não consegui identificar os significados, porem todos os registros são salvos 
	 * como I
	 * 
	 * @ORM\Column(type="string", length=1, nullable=false)
	 */
	protected $operacao;

	/**
	 * @var  Integer $idsis_rev - Id do Sistema porem o rev não consegui encontrar sentido
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $idsis_rev;

	/**
	 * @var  Integer $idsis_cad - Id do sistema que cadastrou o usuario ver tabela acesso.sistema 
	 * 
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $idsis_cad;

	/**
	 * @var Integer $idpes_rev Id da pessoa ??
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $idpes_rev;

	/**
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_cad", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa_cad;

	/**
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_rev", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa_rev;
 	

	/**
	 * Funcao para checar se a string de operacao é diferente de I, A ou E se sim remove a variavel $this->operacao
	 * @access  public
	 * @return  Exception 
	 * @ORM\PrePersist
	 */
	public function checkOperacao()
	{
		if (($this->operacao != "I") && ($this->operacao != "A") && ($this->operacao != "E"))
			throw new \Exception("O atributo operacao recebeu um valor inválido: \"" . $this->operacao. "\"", 1);
	}
	
	/**
	 * Funcao para checar se origem de gravacao é diferente de M, U, C ou O
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkOrigemGravacao()
	{
		if(($this->origem_gravacao != "M") && ($this->origem_gravacao != "U") && ($this->origem_gravacao != "C") && ($this->origem_gravacao != "O"))
			throw new \Exception("O atributo origem_gravacao recebeu um valor inválido: \"" . $this->origem_gravacao. "\"", 1);
	}

	/**
	 * Funcao para checar se o tipo é diferente de F ou J
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkTipo()
	{
		if(($this->tipo != "F") && ($this->tipo != "J"))
			throw new \Exception("O atributo tipo recebeu um valor inválido: \"" . $this->tipo. "\"", 1);
	}

	/**
	 * Funcao para checar se a situacao é diferente de A, P ou I
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkSituacao()
	{
		if(($this->situacao != "A") && ($this->situacao != "P") && ($this->situacao != "I"))
			throw new \Exception("O atributo situacao recebeu um valor inválido: \"" . $this->situacao. "\"", 1);
	}

	public function teste()
	{
		unset($this->inputFilter);
		unset($this->id);
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

	public function getIdpes()
	{
		return $this->idpes;
	}

	public function setIdpes($value)
	{
		$this->idpes = $this->valid("idpes", $value);
	}

	public function getNome()
	{
		return $this->nome;
	}

	public function setNome($value)
	{
		$this->nome = $value;
	}

	public function getDataCad()
	{
		return $this->data_cad;
	
	}
	
	public function setDataCad($value)
	{
		$this->data_cad = $this->valid("data_cad", $value);
	}

	public function getUrl()
	{
		return $this->url;
	}
	
	public function setUrl($value)
	{
		$this->url = $this->valid("url", $value);
	}

	public function getTipo()
	{
		return $this->tipo;
	}
	
	public function setTipo($value)
	{
		$this->tipo = $this->valid("tipo", $value);
	}

	public function getDataRev()
	{
		return $this->data_rev;
	}
	
	public function setDataRev($value)
	{
		$this->data_rev = $this->valid("data_rev", $value);
	}

	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($value)
	{
		$this->email = $this->valid("email", $value);
	}

	public function getSituacao()
	{
		return $this->situacao;
	}
	
	public function setSituacao($value)
	{
		$this->situacao = $this->valid("situacao", $value);
	}

	public function getOrigemGravacao()
	{
		return $this->origem_gravacao;
	}
	
	public function setOrigemGravacao($value)
	{
		$this->origem_gravacao = $this->valid("origem_gravacao", $value);
	}

	public function getOperacao()
	{
		return $this->operacao;
	}
	
	public function setOperacao($value)
	{
		$this->operacao = $this->valid("operacao", $value);
	}

	public function getIdsisRev()
	{
		return $this->idsis_rev;
	}
	
	public function setIdsisRev($value)
	{
		$this->idsis_rev = $this->valid("idsis_rev", $value);
	}

	public function getIdsisCad()
	{
		return $this->idsis_cad;
	}
	
	public function setIdsisCad($value)
	{
		$this->idsis_cad = $this->valid("idsis_cad", $value);
	}

	public function getPessoaCad()
	{
		return $this->pessoa_cad;
	}
	
	public function setPessoaCad($value)
	{
		$this->pessoa_cad = $this->valid("pessoa_cad", $value);
	}

	public function getPessoaRev()
	{
		return $this->pessoa_rev;
	}
	
	public function setPessoaRev($value)
	{
		$this->pessoa_rev = $this->valid("pessoa_rev", $value);
	}

	/**
	 * Convert the object to an array
	 * 
	 * @return  array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	/**
	 * Populate from an array.
	 * 
	 * @param  array $data
	 */
	public function populate($data = array())
	{
		$this->id = $data['id'];
		$this->idpes = $data['idpes'];
		$this->nome = $data['nome'];
		$this->data_cad = $data['data_cad'];
		$this->url = $data['url'];
		$this->tipo = $data['tipo'];
		$this->data_rev = $data['data_rev'];
		$this->email = $data['email'];
		$this->situacao = $data['situacao'];
		$this->origem_gravacao = $data['origem_gravacao'];
		$this->operacao = $data['operacao'];
		$this->idsis_rev = $data['idsis_rev'];
		$this->idsis_cad = $data['idsis_cad'];
		$this->idpes_rev = $data['idpes_rev'];
	}

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory = new InputFactory();

			$inputFilter->add($factory->createInput(array(
				'name' => 'id',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}