<?php
namespace Usuario\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\PrePersist;
//use Doctrine\ORM\Mapping\UniqueConstraint;


use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade EnderecoExterno
 * 
 * Recebe o endereço da pessoa fisica
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category Entidade 
 * @package Usuario
 * @subpackage EnderecoExterno
 * @version 0.1
 * @example Classe EnderecoExterno
 * @copyright Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * 
 * @ORM\Entity
 * @ORM\Table(name="cadastro.endereco_externo")
 * @ORM\HasLifecycleCallbacks
 */
class EnderecoExterno extends Entity		
{	
	/**
	 * @ORM\Id	 
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")	
	 */
	protected $id;	

	/**
	 * @var string $tipoLogradouro Id do tipo de logradouro
	 * ORM\Id
	 * @ORM\OneToOne(targetEntity="Usuario\Entity\Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa;

	/**
	 * @var int $tipo Tipo, o antigo sistema sempre salva como 1, 
	 * atributo deve ser depreciado
	 *
	 * @deprecated 
	 * @ORM\Column(type="smallint", nullable=false)
	 */
	protected $tipo = 1;

	/**
	 * @var string $tipoLogradouro Id do tipo de logradouro
	 * @ORM\OneToOne(targetEntity="Core\Entity\TipoLogradouro", cascade={"persist"})
	 * @ORM\JoinColumn(name="idtlog", referencedColumnName="idtlog", onDelete="SET NULL")
	 */
	protected $tipoLogradouro;

	/**
	 * @var string $logradouro
	 * 
	 * @ORM\Column(type="string", length=150, nullable=false)
	 */
	protected $logradouro;

	/**
	 * @var integer $numero
	 * 
	 * @ORM\Column(type="integer", length=6, nullable=true)
	 */
	protected $numero;

	/**
	 * @var string $letra
	 * 
	 * @ORM\Column(type="string", length=1, nullable=true)
	 */
	protected $letra;

	/**
	 * @var string $complemento
	 * 
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	protected $complemento;

	/**
	 * @var string $bairro
	 * 
	 * @ORM\Column(type="string", length=40, nullable=true)
	 */
	protected $bairro;

	/**
	 * @var int $cep
	 * 
	 * @ORM\Column(type="integer", length=8, nullable=true)
	 */
	protected $cep;

	/**
	 * @var string $cidade
	 * 
	 * @ORM\Column(type="string", length=60, nullable=false)
	 */
	protected $cidade;

	/**
	 * @var string $siglaUf
	 * 
	 * @ORM\Column(name="sigla_uf", type="string", length=2, nullable=false)
	 */
	protected $siglaUf;

	/**
	 * @var date $resideDesde
	 * 
	 * @ORM\Column(name="reside_desde", type="date", nullable=true)
	 */
	protected $resideDesde;

	/**
	 * @var datetime $dataRev
	 * 
	 * @ORM\Column(name="data_rev", type="datetime", nullable=true)
	 */
	protected $dataRev;

	/**
	 * @var  String $origem_gravacao M(Migração) ou U(Usuário) ou C(Rotina de Confrontação) ou O(Usuário do Oscar?) Origem dos dados 
	 * 
	 * @ORM\Column(name="origem_gravacao", type="string", length=1, nullable=false)
	 */
	protected $origemGravacao;

	/**
	 * @var integer $pessoaCad Pessoa que efetuou o cadastrado
	 * 
	 * @ORM\ManyToOne(targetEntity="Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_cad", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoaCad;

	/**
	 * @var datetime $dataCad
	 * 
	 * @ORM\Column(name="data_cad", type="datetime", nullable=false)
	 */
	protected $dataCad;

	/**
	 * @var  String $operacao I(?) ou A(?) ou E(?) - Não consegui identificar os significados, porem todos os registros são salvos 
	 * como I inserir?
	 * como A alterar?
	 * como E excluir?
	 * 
	 * @ORM\Column(type="string", length=1, nullable=false)
	 */
	protected $operacao;

	/**
	 * @var string $bloco
	 * 
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	protected $bloco;

	/**
	 * @var int $andar
	 * 
	 * @ORM\Column(type="integer", length=2, nullable=true)
	 */
	protected $andar;

	/**
	 * @var int $apartamento
	 * 
	 * @ORM\Column(type="integer", length=6, nullable=true)
	 */
	protected $apartamento;


	/**
	 * @var  Integer $idsis_rev - Id do Sistema porem o rev não consegui encontrar sentido
	 * 
	 * @ORM\Column(name="idsis_rev", type="integer", nullable=true)
	 */
	protected $idsisRev;	


	/**
	 * @var  Integer $idsis_cad - Id do sistema que cadastrou o usuario ver tabela acesso.sistema 
	 * 
	 * Na versão 1.0 não tem uma chave estrangeira nessa coluna. Algo que pode ser colocado
	 * @todo  coluna tem relacionamento com a tabela acesso.sistema falta ajustar isso e ajustar no teste
	 * 
	 * @ORM\Column(name="idsis_cad", type="integer", nullable=false)
	 */
	protected $idsisCad;

	/**
	 * @var int $zonaLocalizacao
	 * 
	 * @ORM\Column(name="zona_localizacao", type="integer", nullable=true)
	 */
	protected $zonaLocalizacao = 1;

	/**
	 * Funcao para checar se a string de operacao é diferente de I, A ou E se sim remove a variavel $this->operacao
	 * @access  public
	 * @return  Exception 
	 * @ORM\PrePersist
	 */
	public function checkOperacao()
	{			
		if (($this->getOperacao() != "I") && ($this->getOperacao() != "A") && ($this->getOperacao() != "E"))
			//throw new \Exception("O atributo operacao recebeu um valor inválido: \"" . $this->operacao. "\"", 1);
			throw new EntityException("O atributo operacao recebeu um valor inválido: \"" . $this->getOperacao(). "\"", 1);
	}
	
	/**
	 * Funcao para checar se origem de gravacao é diferente de M, U, C ou O
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkOrigemGravacao()
	{	
		if(($this->getOrigemGravacao() != "M") && ($this->getOrigemGravacao() != "U") && ($this->getOrigemGravacao() != "C") && ($this->getOrigemGravacao() != "O"))
			throw new EntityException("O atributo origem_gravacao recebeu um valor inválido: \"" . $this->getOrigemGravacao(). "\"", 1);
	}

	/**
	 * Função para gerar o timestamp para o atributo data_cad, é executada antes de salvar os dados no banco
	 * @access  public
	 * @return  void 
	 * @ORM\PrePersist
	 */
	public function timestamp()
	{
		if (is_null($this->getDataCad())) {			
			$this->setDataCad(new \DateTime());
		}		
	}

	public function getId()	
	{
		return $this->id;
	}

	public function setId($value)
	{
		$this->id = $value;
	}

	public function getPessoa()
	{
		return $this->pessoa;
	}

	public function setPessoa($value)
	{
		$this->pessoa = $value;
	}



	public function getTipo()
	{
		return $this->tipo;
	}
	
	public function setTipo($value)
	{
		$this->tipo = $this->valid("tipo", $value);
	}

	public function getTipoLogradouro()
	{
		return $this->tipoLogradouro;
	}
	
	public function setTipoLogradouro($value)
	{
		$this->tipoLogradouro = $value;
	}

	public function getLogradouro()
	{
		return $this->logradouro;
	}
	
	public function setLogradouro($value)
	{
		$this->logradouro = $this->valid("logradouro", $value);
	}

	public function getNumero()
	{
		return $this->numero;
	}
	
	public function setNumero($value)
	{
		$this->numero = $this->valid("numero", $value);
	}

	public function getLetra()
	{
		return $this->letra;
	}
	
	public function setLetra($value)
	{
		$this->letra = $this->valid("letra", $value);
	}

	public function getComplemento()
	{
		return $this->complemento;
	}
	
	public function setComplemento($value)
	{
		$this->complemento = $this->valid("complemento", $value);
	}

	public function getBairro()
	{
		return $this->bairro;
	}
	
	public function setBairro($value)
	{
		$this->bairro = $this->valid("bairro", $value);
	}

	public function getCep()
	{
		return $this->cep;
	}
	
	public function setCep($value)
	{
		$this->cep = $this->valid("cep", $value);
	}

	public function getCidade()
	{
		return $this->cidade;
	}
	
	public function setCidade($value)
	{
		$this->cidade = $this->valid("cidade", $value);
	}

	public function getSiglaUf()
	{
		return $this->siglaUf;
	}
	
	public function setSiglaUf($value)
	{
		$this->siglaUf = $this->valid("siglaUf", $value);
	}

	public function getResideDesde()
	{
		return $this->resideDesde;
	}
	
	public function setResideDesde($value)
	{
		$this->resideDesde = $this->valid("resideDesde", $value);
	}

	public function getDataRev()
	{
		return $this->dataRev;
	}
	
	public function setDataRev($value)
	{
		$this->dataRev = $this->valid("dataRev", $value);
	}

	public function getOrigemGravacao()
	{
		return $this->origemGravacao;
	}
	
	public function setOrigemGravacao($value)
	{
		$this->origemGravacao = $this->valid("origemGravacao", $value);
	}

	public function getPessoaCad()
	{
		return $this->pessoaCad;
	}
	
	public function setPessoaCad($value)
	{
		$this->pessoaCad = $value;
	}

	public function getDataCad()
	{
		return $this->dataCad;
	}
	
	public function setDataCad($value)
	{
		$this->dataCad = $this->valid("dataCad", $value);
	}

	public function getOperacao()
	{
		return $this->operacao;
	}
	
	public function setOperacao($value)
	{
		$this->operacao = $this->valid("operacao", $value);
	}

	public function getBloco()
	{
		return $this->bloco;
	}
	
	public function setBloco($value)
	{
		$this->bloco = $this->valid("bloco", $value);
	}

	public function getAndar()
	{
		return $this->andar;
	}
	
	public function setAndar($value)
	{
		$this->andar = $this->valid("andar", $value);
	}

	public function getApartamento()
	{
		return $this->apartamento;
	}
	
	public function setApartamento($value)
	{
		$this->apartamento = $this->valid("apartamento", $value);
	}

	public function getIdsisRev()
	{
		return $this->idsisRev;
	}
	
	public function setIdsisRev($value)
	{
		$this->idsisRev = $this->valid("idsisRev", $value);
	}

	public function getIdsisCad()
	{
		return $this->idsisCad;
	}
	
	public function setIdsisCad($value)
	{
		$this->idsisCad = $this->valid("idsisCad", $value);
	}

	public function getZonaLocalizacao()
	{
		return $this->zonaLocalizacao;
	}
	
	public function setZonaLocalizacao($value)
	{
		$this->zonaLocalizacao = $this->valid("zonaLocalizacao", $value);
	}

	/**
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
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory = new InputFactory();					

			$inputFilter->add($factory->createInput(array(
				'name' => 'id',
				'required' => true,
				'filters' => array(
					array('name' => 'Int')
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tipo',
				'required' => true,
				'filters' => array(
					array('name' => 'Int')
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tipoLogradouro',
				'required' => true
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'logradouro',
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
							'max' => 150,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'numero',
				'required' => false,
				'filters' => array(
					array('name' => 'Int')
				)
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'letra',
				'required' => false,
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
				'name' => 'complemento',
				'required' => false,
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
							'max' => 20,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'bairro',
				'required' => false,
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
							'max' => 40,
						),
					),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'cep',
				'required' => false,
				'filters' => array(
					array('name' => 'Int')
				)
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'cidade',
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
							'max' => 60,
						),
					),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'siglaUf',
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
							'max' => 2,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'resideDesde',
				'required' => false,
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'dataRev',
				'required' => false,
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				)
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'origemGravacao',
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
							'max' => 150,
						),
					),
				),				
			)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

}