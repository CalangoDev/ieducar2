<?php
namespace Usuario\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\EventSubscriber;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Fisica
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Usuario
 * @subpackage  Fisica
 * @version  0.1
 * @example  Classe Fisica
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""cadastro"".""fisica""")
 * @ORM\HasLifecycleCallbacks
 * 
 */
//class Fisica extends Entity implements EventSubscriber
class Fisica extends Pessoa implements EventSubscriber
{
	public function getSubscribedEvents ()
    {
        return array(
                        
        );
    }
	/**
	 * @var Int $id Identificador da entidade fisica
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="cadastro.seq_fisica", initialValue=1, allocationSize=1)	 
	 */
	protected $id;

	/**
	 * @var Pessoa $pessoa Entity Associada Pessoa
	 * 
	 * ORM\OneToOne(targetEntity="Pessoa", inversedBy="fisica")
	 * ORM\JoinColumn(name="pessoa_id", referencedColumnName="idpes")
	 */
	//protected $pessoa;

	/**
	 * @var Datetime $data_nasc Data de nascimento
	 * 
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
	 * Funcao para checar se o sexo é M(Masculino) ou F(Feminino)
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkSexo()
	{			
		if(($this->getSexo() != "M") && ($this->getSexo() != "F"))
			throw new EntityException("O atributo sexo recebeu um valor inválido: \"" . $this->getSexo() . "\"", 1);
	}


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
	 * Funcao para checar se a nacionalidade escolhida esta entre 1 e 3
	 * @access  public
	 * @return  Exception 
	 * @ORM\PrePersist
	 */
	public function checkNacionalidade()
	{
		if ((($this->getNacionalidade() <= 0) || ($this->getNacionalidade() >= 4)) && ($this->getNacionalidade() != "")){			
			throw new EntityException("O atributo nacionalidade recebeu um valor inválido: \"" . $this->getNacionalidade() . "\"", 1);
		}		
			
	}
	
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
	 * @var  DateTime $data_rev Data de revisao
	 * ORM\Column(type="datetime", nullable=true)
	 * heranca
	 */
	//protected $data_rev;

	/**
	 * @var string $origem_gravacao	Origem da gravacao
	 * ORM\Column(type="string", length=1, nullable=false)
	 * 
	 * Heranca
	 */
	//protected $origem_gravacao;

	/**
	 * Funcao para checar se origem de gravacao é diferente de M, U, C ou O
	 * @access  public
	 * @return  Exception
	 * ORM\PrePersist
	 * 
	 * RETIRAR FUNCAO HERDADA DE PESSOA
	 */
	// public function checkOrigemGravacao()
	// {		
	// 	if(($this->getOrigemGravacao() != "M") && ($this->getOrigemGravacao() != "U") && ($this->getOrigemGravacao() != "C") && ($this->getOrigemGravacao() != "O"))
	// 		throw new EntityException("O atributo origem_gravacao recebeu um valor inválido: \"" . $this->OrigemGravacao(). "\"", 1);		
	// }

	/**
	 * @var  DateTime $data_cad Data de cadastro
	 * ORM\Column(type="datetime", nullable=false)
	 * heranca
	 */
	//protected $data_cad;

	/**
	 * Função para gerar o timestamp para o atributo data_cad, é executada antes de salvar os dados no banco
	 * @access  public
	 * @return  void 
	 * ORM\PrePersist
	 * heranca
	 */
	// public function timestamp()
	// {
	// 	if (is_null($this->data_cad)) {
	// 		$this->getDataCad() = new \DateTime();
	// 	}		
	// }

	/**
	 * @var  String $operacao I(?) ou A(?) ou E(?) - Não consegui identificar os significados, porem todos os registros são salvos 
	 * como I inserir?
	 * como A alterar?
	 * como E excluir?
	 * 
	 * ORM\Column(type="string", length=1, nullable=false)
	 * heranca
	 */
	//protected $operacao;

	/**
	 * Funcao para checar se a string de operacao é diferente de I, A ou E se sim remove a variavel $this->operacao
	 * @access  public
	 * @return  Exception 
	 * ORM\PrePersist
	 * Heranca
	 */
	// public function checkOperacao()
	// {
	// 	if (($this->getOperacao != "I") && ($this->getOperacao != "A") && ($this->getOperacao != "E"))			
	// 		throw new EntityException("O atributo operacao recebeu um valor inválido: \"" . $this->getOperacao . "\"", 1);
	// }

	/**
	 * @var  Integer $idsis_rev - Id do Sistema porem o rev não consegui encontrar sentido
	 * 
	 * ORM\Column(type="integer", nullable=true)
	 * heranca
	 */
	//protected $idsis_rev;

	/**
	 * @var  Integer $idsis_cad - Id do sistema que cadastrou o usuario ver tabela acesso.sistema 
	 * 	 
	 * ORM\Column(type="integer", nullable=false)
	 * heranca
	 */
	//protected $idsis_cad;

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
	 * @var int $idpes_mae	Id Pessoa da Mae
	 * 
	 * @todo  verificar esse relacionamento
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pessoa")
	 * @ORM\JoinColumn(name="idpes_mae", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $idpes_mae;

	/**
	 * @var int $idpes_pai Id pessoa do pai
	 * 
	 * @todo  verificar esse relacionamento
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pessoa")
	 * @ORM\JoinColumn(name="idpes_pai", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $idpes_pai;

	/**
	 * @var int $idpes_responsavel	Id da pessoa responsavel
	 * 
	 * @todo verificar esse relacionamento
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pessoa")
	 * @ORM\JoinColumn(name="idpes_responsavel", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $idpes_responsavel;

	/**
	 * @var Int $idmun_nascimento Naturalidade obtem o id na tabela public.municipio
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Municipio")
	 * @ORM\JoinColumn(name="idmun_nascimento", referencedColumnName="idmun", onDelete="NO ACTION")
	 */
	protected $idmun_nascimento;

	/**
	 * @var Int $idpais_estrangeiro	Armazena id do pais se for estrangeiro obtem o id na tabela public.pais
	 * 
	 * @todo falta ajustar esse relacionamento	 
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pais")
	 * @ORM\JoinColumn(name="idpais_estrangeiro", referencedColumnName="idpais", onDelete="NO ACTION")
	 */
	protected $idpais_estrangeiro;

	/**
	 * @var Int $idesco	Código da Escola, chave estrangeira para o campo idesco na relacao cadastro.escolaridade	 
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Escolaridade")
	 * @ORM\JoinColumn(name="idesco", referencedColumnName="idesco", onDelete="NO ACTION")
	 */
	protected $idesco;

	/**
	 * @var Int $ideciv Id do Estado Civil, chave estrangeira para o campo ideciv na relacao cadastro.estado_civil
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="EstadoCivil")
	 * @ORM\JoinColumn(name="ideciv", referencedColumnName="ideciv", onDelete="NO ACTION")
	 */
	protected $ideciv;

	/**
	 * @var int $idpes_con	Id Pessoa Conjugue
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pessoa")
	 * @ORM\JoinColumn(name="idpes_con", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $idpes_con;

	/**
	 * @var int $idocup	ID da Ocupacao
	 *
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Ocupacao")
	 * @ORM\JoinColumn(name="idocup", referencedColumnName="idocup", onDelete="NO ACTION")
	 */
	protected $idocup;

	/**
	 * @var Int $idpes_rev Id da pessoa que esta fazendo a revisao no registro
	 * 
	 * ORM\Column(type="integer", nullable=true)
	 * ORM\ManyToOne(targetEntity="Pessoa")
	 * ORM\JoinColumn(name="idpes_rev", referencedColumnName="idpes", onDelete="SET NULL")
	 * 
	 * heranca
	 */
	//protected $idpes_rev;

	/**
	 * @var int $idpes_cad	Id da Pessoa que efetuou o cadastro
	 * 
	 * ORM\Column(type="integer", nullable=true)
	 * ORM\ManyToOne(targetEntity="Pessoa")
	 * ORM\JoinColumn(name="idpes_cad", referencedColumnName="idpes", onDelete="SET NULL")
	 * heranca
	 */
	//protected $idpes_cad;

	/**
	 * @var int $ref_cod_religiao	Referencia de codigo da religiao
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Religiao")
	 * @ORM\JoinColumn(name="ref_cod_religiao", referencedColumnName="cod_religiao", onDelete="NO ACTION")
	 */
	protected $ref_cod_religiao;


	/**
	 * getters and setters
	 */
		
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
		return $this->data_obito = $this->valid("data_obito", $value);
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
		return $nome_mae;
	}

	public function setNomeMae($value)
	{
		$this->nome_mae = $this->valid("nome_mae", $value);
	}

	public function getNomePai()
	{
		return $nome_pai;
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

	public function getDataRev()
	{
		return $this->data_rev;
	}
	
	public function setDataRev($value)
	{
		$this->data_rev = $this->valid("data_rev", $value);
	}

	// public function getOrigemGravacao()
	// {
	// 	return $this->origem_gravacao;
	// }
	
	// public function setOrigemGravacao($value){
	// 	$this->origem_gravacao = $this->valid("origem_gravacao", $value);
	// }

	public function getDataCad()
	{
		return $this->data_cad;
	}
	
	public function setDataCad($value)
	{
		$this->data_cad = $this->valid("data_cad", $value);
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

	public function getIdpesMae()
	{
		return $this->idpes_mae;
	}
	
	public function setIdpesMae($value)
	{
		$this->idpes_mae = $this->valid("idpes_mae", $value);
	}

	public function getIdpesPai()
	{
		return $this->idpes_pai;
	}
	
	public function setIdpesPai($value)
	{
		$this->idpes_pai = $this->valid("idpes_pai", $value);
	}

	public function getIdpesResponsavel()
	{
		return $this->idpes_responsavel;
	}
	
	public function setIdpesResponsavel($value)
	{
		$this->idpes_responsavel = $this->valid("idpes_responsavel", $value);
	}

	public function getIdmunNascimento()
	{
		return $this->idmun_nascimento;
	}
	
	public function setIdmunNascimento($value)
	{
		$this->idmun_nascimento = $this->valid("idmun_nascimento", $value);
	}

	public function getIdpaisEstrangeiro()
	{
		return $this->idpais_estrangeiro;
	}
	
	public function setIdpaisEstrangeiro($value)
	{
		$this->idpais_estrangeiro = $this->valid("idpais_estrangeiro", $value);
	}

	public function getIdesco()
	{
		return $this->idesco;
	}
	
	public function setIdesco($value)
	{
		$this->idesco = $this->valid("idesco", $value);
	}

	public function getIdeciv()
	{
		return $this->ideciv;
	}
	
	public function setIdeciv($value)
	{
		$this->ideciv = $this->valid("ideciv", $value);
	}

	public function getIdpesCon()
	{
		return $this->idpes_con;
	}
	
	public function setIdpesCon($value)
	{
		$this->idpes_con = $this->valid("idpes_con", $value);
	}

	public function getIdocup()
	{
		return $this->idocup;
	}
	
	public function setIdocup($value)
	{
		$this->idocup = $this->valid("idocup", $value);
	}

	public function getIdpesRev()
	{
		return $this->idpes_rev;
	}
	
	public function setIdpesRev($value)
	{
		$this->idpes_rev = $this->valid("idpes_rev", $value);
	}

	public function getIdpesCad()
	{
		return $this->idpes_cad;
	}
	
	public function setIdpesCad($value)
	{
		$this->idpes_cad = $this->valid("idpes_cad", $value);
	}

	public function getRefCodReligiao()
	{
		return $this->ref_cod_religiao;
	}
	
	public function setRefCodReligiao($value)
	{
		$this->ref_cod_religiao = $this->valid("ref_cod_religiao", $value);
	}

	public function setData($data)
	{
		$this->setId(isset($data['id']) ? $data['id'] : null);
		if (!empty($data['data_nasc']))
			$this->setDataNasc(isset($data['data_nasc']) ? $data['data_nasc'] : null);
		$this->setSexo(isset($data['sexo']) ? $data['sexo'] : null);
		if (!empty($data['data_uniao']))
			$this->setDataUniao(isset($data['data_uniao']) ? $data['data_uniao'] : null);
		if (!empty($data['data_obito']))
			$this->setDataObito(isset($data['data_obito']) ? $data['data_obito'] : null);		
		$this->setNacionalidade(isset($data['nacionalidade']) ? $data['nacionalidade'] : null);
		if (!empty($data['data_chegada_brasil']))
			$this->setDataChegadaBrasil(isset($data['data_chegada_brasil']) ? $data['data_chegada_brasil'] : null);
		if (!empty($data['ultima_empresa']))
			$this->setUltimaEmpresa(isset($data['ultima_empresa']) ? $data['ultima_empresa'] : null);
		if (!empty($data['nome_mae']))
			$this->setNomeMae(isset($data['nome_mae']) ? $data['nome_mae'] : null);
		if (!empty($data['nome_pai']))
			$this->setNomePai(isset($data['nome_pai']) ? $data['nome_pai'] : null);
		if (!empty($data['nome_conjuge']))
			$this->setNomeConjuge(isset($data['nome_conjuge']) ? $data['nome_conjuge'] : null);
		if (!empty($data['nome_responsavel']))
			$this->setNomeResponsavel(isset($data['nome_responsavel']) ? $data['nome_responsavel'] : null);
		if (!empty($data['justificativa_provisorio']))
			$this->setJustificativaProvisorio(isset($data['justificativa_provisorio']) ? $data['justificativa_provisorio'] : null);
		if (!empty($data['data_rev']))
			$this->setDataRev(isset($data['data_rev']) ? $data['data_rev'] : null);
		//$this->setOrigemGravacao(isset($data['origem_gravacao']) ? $data['origem_gravacao'] : null);
		$this->setDataCad(isset($data['data_cad']) ? $data['data_cad'] : null);
		//$this->setOperacao(isset($data['operacao']) ? $data['operacao'] : null);
		$this->setIdsisRev(isset($data['idsis_rev']) ? $data['idsis_rev'] : null);
		//$this->setIdsisCad(isset($data['idsis_cad']) ? $data['idsis_cad'] : null);
		$this->setRefCodSistema(isset($data['ref_cod_sistema']) ? $data['ref_cod_sistema'] : null);
		if (!empty($data['cpf']))
			$this->setCpf(isset($data['cpf']) ? $data['cpf'] : null);
		$this->setIdpesMae(isset($data['idpes_mae']) ? $data['idpes_mae'] : null);
		$this->setIdpesPai(isset($data['idpes_pai']) ? $data['idpes_pai'] : null);
		$this->setIdpesResponsavel(isset($data['idpes_responsavel']) ? $data['idpes_responsavel'] : null);
		$this->setIdmunNascimento(isset($data['idmun_nascimento']) ? $data['idmun_nascimento'] : null);
		$this->setIdpaisEstrangeiro(isset($data['idpais_estrangeiro']) ? $data['idpais_estrangeiro'] : null);
		$this->setIdesco(isset($data['idesco']) ? $data['idesco'] : null);
		$this->setIdeciv(isset($data['ideciv']) ? $data['ideciv'] : null);
		$this->setIdpesCon(isset($data['idpes_con']) ? $data['idpes_con'] : null);
		$this->setIdocup(isset($data['idocup']) ? $data['idocup'] : null);		

		$this->setNome(isset($data['nome']) ? $data['nome'] : null);
		$this->setSituacao(isset($data['situacao']) ? $data['situacao'] : null);
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
				'name' => 'data_nasc',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'sexo',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alpha'),
					array('name' => 'StringToUpper'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						false,
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 1,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'data_uniao',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'data_obito',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'nacionalidade',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'data_chegada_brasil',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ultima_empresa',
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
				'name' => 'nome_mae',
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
				'name' => 'nome_pai',
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
				'name' => 'nome_conjuge',
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
				'name' => 'nome_responsavel',
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
				'name' => 'justificativa_provisorio',
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
				'name' => 'data_rev',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'origem_gravacao',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alpha'),
					array('name' => 'StringToUpper'),
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
				'name' => 'operacao',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alpha'),
					array('name' => 'StringToUpper'),
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
				'name' => 'idsis_rev',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idsis_cad',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ref_cod_sistema',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'cpf',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alnum'),					
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 11,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpes_mae',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpes_pai',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpes_responsavel',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idmun_nascimento',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpais_estrangeiro',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idesco',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ideciv',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpes_con',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idocup',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpes_rev',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpes_cad',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ref_cod_religiao',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));



			$this->inputFilter = $inputFilter;
		}
		return $this->inputFilter;
	}
	 
	/**
	 * [removeInputFilter remove um inputfilter]
	 * @param  Zend\InputFilter\InputFilter	 
	 */
	public function removeInputFilter($input)
    {        
        $inputFilter    = new InputFilter();                        
        $this->inputFilter->remove($input);
        
        return $this->inputFilter;
    }

}