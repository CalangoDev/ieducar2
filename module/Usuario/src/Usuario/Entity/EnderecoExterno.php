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
 * @ORM\Table(name="cadastro_endereco_externo")
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
	 * @var string $pessoa
	 * ORM\OneToOne(targetEntity="Usuario\Entity\Pessoa", cascade={"persist"}, inversedBy="enderecoExterno")
	 * ORM\JoinColumn(name="idpes", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	//protected $pessoa;

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
	 * @ORM\ManyToOne(targetEntity="Core\Entity\TipoLogradouro", cascade={"persist"})
	 * ORM\JoinColumn(name="idtlog", referencedColumnName="idtlog", onDelete="SET NULL")
	 */
	protected $tipoLogradouro;

	/**
	 * @var string $logradouro
	 * 
	 * @ORM\Column(type="string", length=150, nullable=true)
	 */
	protected $logradouro;

	/**
	 * @var integer $numero
	 * 
	 * @ORM\Column(type="string", length=6, nullable=true)
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
	 * @ORM\Column(type="string", length=9, nullable=true)
	 */
	protected $cep;

	/**
	 * @var string $cidade
	 * 
	 * @ORM\Column(type="string", length=60, nullable=true)
	 */
	protected $cidade;

	/**
	 * @var string $siglaUf
	 * 
	 * ORM\Column(name="sigla_uf", type="string", length=2, nullable=true)
	 */
	//protected $siglaUf;

	/**
	 * @var Entity Uf
	 * @ORM\ManyToOne(targetEntity="Core\Entity\Uf", cascade={"persist"})
	 * @ORM\JoinColumn(name="sigla_uf", onDelete="NO ACTION", nullable=true)
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
	 * @deprecated deprecated since version 2.0
	 * 
	 * ORM\Column(name="data_rev", type="datetime", nullable=true)
	 */
	//protected $dataRev;

	/**
	 * @var  String $origem_gravacao M(Migração) ou U(Usuário) ou C(Rotina de Confrontação) ou O(Usuário do Oscar?) Origem dos dados
	 * @deprecated deprecated since version 2.0
	 * 
	 * ORM\Column(name="origem_gravacao", type="string", length=1, nullable=false)
	 */
	//protected $origemGravacao;

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
	 * @deprecated deprecated since version 2.0
	 * 
	 * ORM\Column(type="string", length=1, nullable=false)
	 */
	//protected $operacao;

	/**
	 * @var string $bloco
	 * 
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	protected $bloco;

	/**
	 * @var int $andar
	 * 
	 * @ORM\Column(type="string", length=2, nullable=true)
	 */
	protected $andar;

	/**
	 * @var int $apartamento
	 * 
	 * @ORM\Column(type="string", length=6, nullable=true)
	 */
	protected $apartamento;


	/**
	 * @var  Integer $idsis_rev - Id do Sistema porem o rev não consegui encontrar sentido
	 * @deprecated deprecated since version 2.0
	 * 
	 * ORM\Column(name="idsis_rev", type="integer", nullable=true)
	 */
	//protected $idsisRev;


	/**
	 * @var  Integer $idsis_cad - Id do sistema que cadastrou o usuario ver tabela acesso.sistema 
	 * 
	 * Na versão 1.0 não tem uma chave estrangeira nessa coluna. Algo que pode ser colocado
	 * @todo  coluna tem relacionamento com a tabela acesso.sistema falta ajustar isso e ajustar no teste
	 *
	 * @deprecated deprecated since version 2.0
	 * 
	 * ORM\Column(name="idsis_cad", type="integer", nullable=false)
	 */
	//protected $idsisCad;

	/**
	 * @var string $zonaLocalizacao
	 * 
	 * @ORM\Column(name="zona_localizacao", type="string", nullable=true, length=1)
	 */
	protected $zonaLocalizacao = "1";

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

	public function getTipo()
	{
		return $this->tipo;
	}
	
	public function setTipo($tipo)
	{
		$this->tipo = $this->valid("tipo", $tipo);
	}

	public function getTipoLogradouro()
	{
		return $this->tipoLogradouro;
	}
	
	public function setTipoLogradouro($tipoLogradouro)
	{
		$this->tipoLogradouro = $this->valid("tipoLogradouro", $tipoLogradouro);
	}

	public function getLogradouro()
	{
		return $this->logradouro;
	}
	
	public function setLogradouro($logradouro)
	{
		$this->logradouro = $this->valid("logradouro", $logradouro);
	}

	public function getNumero()
	{
		return $this->numero;
	}
	
	public function setNumero($numero)
	{
		$this->numero = $this->valid("numero", $numero);
	}

	public function getLetra()
	{
		return $this->letra;
	}
	
	public function setLetra($letra)
	{
		$this->letra = $this->valid("letra", $letra);
	}

	public function getComplemento()
	{
		return $this->complemento;
	}
	
	public function setComplemento($complemento)
	{
		$this->complemento = $this->valid("complemento", $complemento);
	}

	public function getBairro()
	{
		return $this->bairro;
	}
	
	public function setBairro($bairro)
	{
		$this->bairro = $this->valid("bairro", $bairro);
	}

	public function getCep()
	{
		return $this->cep;
	}
	
	public function setCep($cep)
	{
		$this->cep = $this->valid("cep", $cep);
	}

	public function getCidade()
	{
		return $this->cidade;
	}
	
	public function setCidade($cidade)
	{
		$this->cidade = $this->valid("cidade", $cidade);
	}

	public function getSiglaUf()
	{
		return $this->siglaUf;
	}
	
	public function setSiglaUf(\Core\Entity\Uf $siglaUf = null)
	{
		$this->siglaUf = $this->valid("siglaUf", $siglaUf);
	}

	public function getResideDesde()
	{
		return $this->resideDesde;
	}
	
	public function setResideDesde($resideDesde)
	{
		$this->resideDesde = $this->valid("resideDesde", $resideDesde);
	}

	public function getDataCad()
	{
		return $this->dataCad;
	}
	
	public function setDataCad($dataCad)
	{
		$this->dataCad = $this->valid("dataCad", $dataCad);
	}

	public function getBloco()
	{
		return $this->bloco;
	}
	
	public function setBloco($bloco)
	{
		$this->bloco = $this->valid("bloco", $bloco);
	}

	public function getAndar()
	{
		return $this->andar;
	}
	
	public function setAndar($andar)
	{
		$this->andar = $this->valid("andar", $andar);
	}

	public function getApartamento()
	{
		return $this->apartamento;
	}
	
	public function setApartamento($apartamento)
	{
		$this->apartamento = $this->valid("apartamento", $apartamento);
	}

	public function getZonaLocalizacao()
	{
		return $this->zonaLocalizacao;
	}
	
	public function setZonaLocalizacao($zonaLocalizacao)
	{
		$this->zonaLocalizacao = $this->valid("zonaLocalizacao", $zonaLocalizacao);
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
				'required' => false,
				'filters' => array(
					array('name' => 'Int')
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tipoLogradouro',
                'required' => true,
                'allow_empty' => true,
                'continue_if_empty' => true,
//                'required' => true,
//                'continue_if_empty' => true,
                'filters'     => array(
                    array('name' => 'Int'),
                    array('name' => 'Null'),
                ),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'logradouro',
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
							'max' => 150,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'numero',
				'required' => false,
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
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 9,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'cidade',
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
							'max' => 60,
						),
					),
				),				
			)));

//			$inputFilter->add($factory->createInput(array(
//				'name' => 'siglaUf',
//				'required' => false,
//				'filters'	=>	array(
//					array('name'	=>	'StripTags'),
//					array('name'	=>	'StringTrim'),
//				),
//				'validators' => array(
//					array(
//						'name' => 'StringLength',
//						'options' => array(
//							'encoding' => 'UTF-8',
//							'min' => 1,
//							'max' => 2,
//						),
//					),
//				),
//			)));

            $inputFilter->add($factory->createInput(array(
                'name' => 'siglaUf',
                'required' => true,
                'continue_if_empty' => true,
                'filters'     => array(
                    array('name' => 'Null'),
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
                'name' => 'zonaLocalizacao',
                'required' => false
            )));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

}