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
 * Entidade Fisica
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Historico
 * @subpackage  Fisica
 * @version  0.1
 * @example  Classe Fisica
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""historico"".""fisica""")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Fisica extends Entity implements InputFilterAwareInterface 
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="historico.seq_fisica", initialValue=1, allocationSize=1)
	 */
	protected $id;

	/**
	 * @var int $idpes Identificador da entidade pessoa
	 * @ORM\Column(name="idpes", type="integer", nullable=false)
	 */
	protected $idpes;

	/**
	 * @var DateTime data de nascimento
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $data_nasc;

	/**
	 * @var String $sexo Sexo M(Masculino) ou F(Feminino)
	 * 
	 * @ORM\Column(type="string", length=1, nullable=true)
	 */
	protected $sexo;

	/**
	 * @var Datetime $data_uniao Data de Uniao ???
	 * 
	 * Não achei utilidade dessa variavel no sistema.
	 * 
	 * @todo verificar necessidade desse campo
	 * @ORM\Column(type="datetime", nullable=true) 
	 */
	protected $data_uniao;

	/**
	 * @var Datetime $data_obito Date do óbito ???
	 * 
	 * Não achei utilidade dessa variavel no sistema
	 * @todo verificar necessidade desse campo
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $data_obito;

	/**
	 * @var Int $nacionalidade	Nacionalidade
	 * 
	 * '1'  => 'Brasileiro',
     * '2'  => 'Naturalizado brasileiro',
     *  3'  => 'Estrangeiro');
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $nacionalidade;

	/**
	 * @var Datetime $data_chegada_brasil	Data de chegada ao Brasil se for estrangeiro
	 * 
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $data_chegada_brasil;

	/**
	 * @var String $ultima_empresa	Ultima empresa que trabalhou
	 * 
	 * @ORM\Column(type="string", length=150, nullable=true)
	 */
	protected $ultima_empresa;

	/**
	 * @var string $nome_mae	Nome da Mae
	 * 
	 * @ORM\Column(type="string", length=150, nullable=true)
	 */
	protected $nome_mae;

	/**
	 * @var string $nome_pai	Nome do pai
	 * 
	 * @ORM\Column(type="string", length=150, nullable=true)
	 */
	protected $nome_pai;

	/**
	 * @var string $nome_conjuge	Nome do conjuge
	 * 
	 * @ORM\Column(type="string", length=150, nullable=true)
	 */
	protected $nome_conjuge;

	/**
	 * @var string $nome_responsavel	Nome do responsavel
	 * 
	 * @ORM\Column(type="string", length=150, nullable=true)
	 */
	protected $nome_responsavel;

	/**
	 * @var string $justificativa_provisorio	????
	 *
	 * @todo verificar essa info
	 *  
	 * @ORM\Column(type="string", length=150, nullable=true)
	 */
	protected $justificativa_provisorio;

	/**
	 * @var int $ref_cod_sistema	Referencia do codigo do sistema
	 * @todo  check nesse campo
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $ref_cod_sistema;

	/**
	 * @var string $cpf CPF
	 * @ORM\Column(type="string", length=11, nullable=true)
	 */
	protected $cpf;	

	/**
	 * @var int $pessoa_mae	Id Pessoa da Mae
	 * 
	 * @todo  verificar esse relacionamento
	 * 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_mae", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa_mae;

	/**
	 * example
	 * ORM\ManyToOne(targetEntity="Usuario\Entity\Pessoa", cascade={"persist"})
	 * ORM\JoinColumn(name="idpes_cad", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	//protected $pessoa_cad;

	/**
	 * @var int $pessoa_pai Id pessoa do pai
	 * 
	 * @todo  verificar esse relacionamento
	 * 	 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Pessoa")
	 * @ORM\JoinColumn(name="idpes_pai", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa_pai;

	/**
	 * @var int $pessoa_responsavel	Id da pessoa responsavel
	 * 
	 * @todo verificar esse relacionamento
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Pessoa")
	 * @ORM\JoinColumn(name="idpes_responsavel", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa_responsavel;

	/**
	 * @var Int $municiopio_nascimento Naturalidade obtem o id na tabela public.municipio
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\ManyToOne(targetEntity="Core\Entity\Municipio")
	 * @ORM\JoinColumn(name="idmun_nascimento", referencedColumnName="idmun", onDelete="NO ACTION")
	 */
	protected $municipio_nascimento;

	/**
	 * @var Int $pais_estrangeiro	Armazena id do pais se for estrangeiro obtem o id na tabela public.pais
	 * 
	 * @todo falta ajustar esse relacionamento	 
	 * 	 
	 * @ORM\ManyToOne(targetEntity="Core\Entity\Pais")
	 * @ORM\JoinColumn(name="idpais_estrangeiro", referencedColumnName="idpais", onDelete="NO ACTION")
	 */
	protected $pais_estrangeiro;

	/**
	 * @var Int $escola	Código da Escola, chave estrangeira para o campo idesco na relacao cadastro.escolaridade	 
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 	 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Escolaridade")
	 * @ORM\JoinColumn(name="idesco", referencedColumnName="idesco", onDelete="NO ACTION")
	 */
	protected $escola;

	/**
	 * @var Int $estado_civil Id do Estado Civil, chave estrangeira para o campo ideciv na relacao cadastro.estado_civil
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 	 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\EstadoCivil")
	 * @ORM\JoinColumn(name="ideciv", referencedColumnName="ideciv", onDelete="NO ACTION")
	 */
	protected $estado_civil;

	/**
	 * @var int $pessoa_conjugue	Id Pessoa Conjuge
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Pessoa")
	 * @ORM\JoinColumn(name="idpes_con", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa_conjuge;

	/**
	 * @var int $ocupacao	ID da Ocupacao
	 *
	 * @todo falta ajustar esse relacionamento
	 * 	 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Ocupacao")
	 * @ORM\JoinColumn(name="idocup", referencedColumnName="idocup", onDelete="NO ACTION")
	 */
	protected $ocupacao;


	/**
	 * @var int $ref_cod_religiao	Referencia de codigo da religiao
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Religiao")
	 * @ORM\JoinColumn(name="ref_cod_religiao", referencedColumnName="cod_religiao", onDelete="NO ACTION")
	 */
	protected $ref_cod_religiao;

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

	public function getDataNasc()
	{
		return $this->data_nasc;
	}
	
	public function setDataNasc($value)
	{
		$this->data_nasc = $this->valid("data_nasc", $value);
	}

	public function getSexo()
	{
		return $this->sexo;
	}
	
	public function setSexo($value)
	{
		$this->sexo = $this->valid("sexo", $value);
	}

	public function getDataUniao()
	{
		return $this->data_uniao;
	}
	
	public function setDataUniao($value)
	{
		$this->data_uniao = $this->valid("data_uniao", $value);
	}

	public function getDataObito()
	{
		return $this->data_obito;
	}
	
	public function setDataObito($value)
	{
		$this->data_obito = $this->valid("data_obito", $value);
	}

	public function getNacionalidade()
	{
		return $this->nacionalidade;
	}
	
	public function setNacionalidade($value)
	{
		$this->nacionalidade = $this->valid("nacionalidade", $value);
	}

	public function getDataChegadaBrasil()
	{
		return $this->data_chegada_brasil;
	}
	
	public function setDataChegadaBrasil($value)
	{
		$this->data_chegada_brasil = $this->valid("data_chegada_brasil", $value);
	}

	public function getUltimaEmpresa()
	{
		return $this->ultima_empresa;
	}
	
	public function setUltimaEmpresa($value)
	{
		$this->ultima_empresa = $this->valid("ultima_empresa", $value);
	}

	public function getNomeMae()
	{
		return $this->nome_mae;
	}
	
	public function setNomeMae($value)
	{
		$this->nome_mae = $this->valid("nome_mae", $value);
	}

	public function getNomePai()
	{
		return $this->nome_pai;
	}
	
	public function setNomePai($value)
	{
		$this->nome_pai = $this->valid("nome_pai", $value);
	}

	public function getNomeConjuge()
	{
		return $this->nome_conjuge;
	}
	
	public function setNomeConjuge($value)
	{
		$this->nome_conjuge = $this->valid("nome_conjuge", $value);
	}

	public function getNomeResponsavel()
	{
		return $this->nome_responsavel;
	}
	
	public function setNomeResponsavel($value)
	{
		$this->nome_responsavel = $this->valid("nome_responsavel", $value);
	}

	public function getJustificativaProvisorio()
	{
		return $this->justificativa_provisorio;
	}
	
	public function setJustificativaProvisorio($value)
	{
		$this->justificativa_provisorio = $this->valid("justificativa_provisorio", $value);
	}

	public function getRefCodSistema()
	{
		return $this->ref_cod_sistema;
	}
	
	public function setRefCodSistema($value)
	{
		$this->ref_cod_sistema = $this->valid("ref_cod_sistema", $value);
	}

	public function getCpf()
	{
		return $this->cpf;
	}
	
	public function setCpf($value)
	{
		$this->cpf = $this->valid("cpf", $value);
	}

	public function getPessoaMae()
	{
		return $this->pessoa_mae;
	}
	
	public function setPessoaMae($value)
	{
		$this->pessoa_mae = $this->valid("pessoa_mae", $value);
	}

	public function getPessoaPai()
	{
		return $this->pessoa_pai;
	}
	
	public function setPessoaPai($value)
	{
		$this->pessoa_pai = $this->valid("pessoa_pai", $value);
	}

	public function getPessoaResponsavel()
	{
		return $this->pessoa_responsavel;
	}
	
	public function setPessoaResponsavel($value)
	{
		$this->pessoa_responsavel = $this->valid("pessoa_responsavel", $value);
	}

	public function getMunicipioNascimento()
	{
		return $this->municipio_nascimento;
	}
	
	public function setMunicipioNascimento($value)
	{
		$this->municipio_nascimento = $this->valid("municipio_nascimento", $value);
	}

	public function getPaisEstrangeiro()
	{
		return $this->pais_estrangeiro;
	}
	
	public function setPaisEstrangeiro($value)
	{
		$this->pais_estrangeiro = $this->valid("pais_estrangeiro", $value);
	}

	public function getEscola()
	{
		return $this->escola;
	}
	
	public function setEscola($value)
	{
		$this->escola = $this->valid("escola", $value);
	}

	public function getEstadoCivil()
	{
		return $this->estado_civil;
	}
	
	public function setEstadoCivil($value)
	{
		$this->estado_civil = $this->valid("estado_civil", $value);
	}

	public function getPessoaConjuge()
	{
		return $this->pessoa_conjuge;
	}
	
	public function setPessoaConjuge($value)
	{
		$this->pessoa_conjuge = $this->valid("pessoa_conjuge", $value);
	}

	public function getOcupacao()
	{
		return $this->ocupacao;
	}
	
	public function setOcupacao($value)
	{
		$this->ocupacao = $this->valid("ocupacao", $value);
	}

	public function getRefCodReligiao()
	{
		return $this->ref_cod_religiao;
	}
	
	public function setRefCodReligiao($value)
	{
		$this->ref_cod_religiao = $this->valid("ref_cod_religiao", $value);
	}

}