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
use Usuario\Entity\Documento;

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
 * @ORM\Table(name="cadastro_fisica")
 * @ORM\HasLifecycleCallbacks
 * 
 */
//class Fisica extends Entity implements EventSubscriber
class Fisica extends Pessoa
{	
	/**
	 * @var Int $id Identificador da entidade fisica
	 * 
	 * ORM\Id
	 * ORM\Column(type="integer", nullable=false)
	 * ORM\GeneratedValue(strategy="SEQUENCE")
	 * SequenceGenerator(sequenceName="cadastro.seq_fisica", initialValue=1, allocationSize=1)	 
	 */
	// protected $id;

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
	 * @ORM\Column(name="data_nasc", type="date", nullable=true)
	 */
	protected $dataNasc;

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
	 * @throws EntityException If o atributo sexo recebeu um valor inválido
	 */
	public function checkSexo()
	{	
		if(($this->getSexo() != "M") && ($this->getSexo() != "F") && ($this->getSexo() != "") )
			throw new EntityException("O atributo sexo recebeu um valor inválido: \"" . $this->getSexo() . "\"", 1);
	}


	/**
	 * @var Datetime $data_uniao Data de Uniao ???
	 * 
	 * Não achei utilidade dessa variavel no sistema.
	 * 
	 * @todo verificar necessidade desse campo
	 * @ORM\Column(name="data_uniao", type="datetime", nullable=true) 
	 */
	protected $dataUniao;

	/**
	 * @var Datetime $data_obito Date do óbito ???
	 * 
	 * Não achei utilidade dessa variavel no sistema
	 * @todo verificar necessidade desse campo
	 * @ORM\Column(name="data_obito", type="datetime", nullable=true)
	 */
	protected $dataObito;

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
	 * @throws EntityException If O atributo nacionalidade recebeu um valor inválido:
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
	 * @ORM\Column(name="data_chegada_brasil", type="datetime", nullable=true)
	 */
	protected $dataChegadaBrasil;

	/**
	 * @var String $ultima_empresa	Ultima empresa que trabalhou
	 * 
	 * @ORM\Column(name="ultima_empresa", type="string", length=150, nullable=true)
	 */
	protected $ultimaEmpresa;
	

	/**
	 * @var string $nome_mae	Nome da Mae
	 * @deprecated deprecated since version 2.0
	 * 
	 * ORM\Column(name="nome_mae", type="string", length=150, nullable=true)
	 */
	//protected $nomeMae;

	/**
	 * @var string $nome_pai	Nome do pai
	 * @deprecated deprecated since version 2.0
	 * 
	 * ORM\Column(name="nome_pai", type="string", length=150, nullable=true)
	 */
	//protected $nomePai;

	/**
	 * @var string $nome_conjuge	Nome do conjuge
     * @deprecated deprecated since version 2.0
     * @todo criar relacionamento com pessoa responsavel
	 * 
	 * ORM\Column(name="nome_conjuge", type="string", length=150, nullable=true)
	 */
	//protected $nomeConjuge;

	/**
	 * @var string $nome_responsavel	Nome do responsavel
     * @deprecated deprecated since version 2.0
     *
     * @todo criar relacionamento com pessoa responsavel
	 * 
	 * ORM\Column(name="nome_responsavel", type="string", length=150, nullable=true)
	 */
	//protected $nomeResponsavel;

	/**
	 * @var string $justificativa_provisorio	????
	 *
	 * @todo verificar essa info
	 *  
	 * @ORM\Column(name="justificativa_provisorio", type="string", length=150, nullable=true)
	 */
	protected $justificativaProvisorio;	

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
	 * @ORM\Column(name="ref_cod_sistema", type="integer", nullable=true)
	 */
	protected $refCodSistema;

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
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Fisica", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_mae", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoaMae;

	/**
	 * @var int $pessoa_pai Id pessoa do pai
	 * 
	 * @todo  verificar esse relacionamento
	 * 	 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Fisica", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_pai", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoaPai;

	/**
	 * @var int $pessoa_responsavel	Id da pessoa responsavel
	 * 
	 * @todo verificar esse relacionamento
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Pessoa")
	 * @ORM\JoinColumn(name="idpes_responsavel", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoaResponsavel;

	/**
	 * @var Int $municipioNascimento Naturalidade
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\ManyToOne(targetEntity="Core\Entity\CepUnico", cascade={"all"})
	 * @ORM\JoinColumn(referencedColumnName="seq")
	 */
	protected $municipioNascimento;

	/**
	 * @var Int $pais_estrangeiro	Armazena id do pais se for estrangeiro obtem o id na tabela public.pais
	 * 
	 * @todo falta ajustar esse relacionamento	 
	 * 	 
	 * @ORM\ManyToOne(targetEntity="Core\Entity\Pais")
	 * @ORM\JoinColumn(name="idpais_estrangeiro", referencedColumnName="idpais", onDelete="NO ACTION")
	 */
	protected $paisEstrangeiro;

	/**
	 * @var Int $escola	Código da Escolaridade, chave estrangeira para o campo idesco na relacao cadastro.escolaridade	 
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
	protected $estadoCivil;

	/**
	 * @var int $pessoa_conjugue	Id Pessoa Conjuge
	 * 
	 * @todo falta ajustar esse relacionamento
	 * 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Pessoa")
	 * @ORM\JoinColumn(name="idpes_con", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoaConjuge;

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
	protected $refCodReligiao;

	/**
	 * @var int $raca Referencia a raça da pessoa fisica
	 * 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Raca")
	 * @ORM\JoinColumn(name="idraca", referencedColumnName="cod_raca", onDelete="SET NULL", nullable=true)
	 */
	protected $raca;

	/**
	 * @var string $foto Caminho da imagem de foto
	 *
	 * @ORM\Column(type="string", length=120, nullable=true)
	 */
	protected $foto;

	/**
	 * @var documento $documento Entity Documento
	 *
	 * ORM\ManyToOne(targetEntity="Usuario\Entity\Documento", cascade={"persist"}, mappedBy="fisica")
	 */
	//protected $documento;

    /**
     * @var documento $documento Entity Documento
     * @ORM\OneToOne(targetEntity="Usuario\Entity\Documento", inversedBy="fisica", cascade={"all"})
     */
    protected $documento;

	/**
	 * getters and setters
	 */
		
	public function getDataNasc()
	{
		return $this->dataNasc;
	}

	public function setDataNasc($value)
	{
		$this->dataNasc = $value;
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
		return $this->dataUniao;
	}

	public function setDataUniao($value)
	{
		$this->dataUniao = $this->valid("dataUniao", $value);
	}

	public function getDataObito()
	{
		return $this->dataObito;
	}

	public function setDataObito($value)
	{
		return $this->dataObito = $this->valid("dataObito", $value);
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
		return $this->dataChegadaBrasil;
	}

	public function setDataChegadaBrasil($value)
	{
		$this->dataChegadaBrasil = $this->valid("dataChegadaBrasil", $value);
	}

	public function getUltimaEmpresa()
	{
		return $this->ultimaEmpresa;
	}

	public function setUltimaEmpresa($value)
	{
		$this->ultimaEmpresa = $this->valid("ultimaEmpresa", $value);
	}

	public function getJustificativaProvisorio()
	{
		return $this->justificativaProvisorio;
	}
	
	public function setJustificativaProvisorio($value)
	{
		$this->justificativaProvisorio = $this->valid("justificativaProvisorio", $value);
	}

	// public function getDataRev()
	// {
	// 	return $this->dataRev;
	// }
	
	// public function setDataRev($value)
	// {
	// 	$this->dataRev = $this->valid("dataRev", $value);
	// }

	// public function getOrigemGravacao()
	// {
	// 	return $this->origem_gravacao;
	// }
	
	// public function setOrigemGravacao($value){
	// 	$this->origem_gravacao = $this->valid("origem_gravacao", $value);
	// }

	public function getDataCad()
	{
		return $this->dataCad;
	}
	
	public function setDataCad($value)
	{
		$this->dataCad = $this->valid("dataCad", $value);
	}

	public function getRefCodSistema()
	{
		return $this->refCodSistema;
	}
	
	public function setRefCodSistema($value)
	{
		$this->refCodSistema = $this->valid("refCodSistema", $value);
	}

	public function getCpf()
	{
		return $this->cpf;
	}
	
	public function setCpf($value)
	{
		$this->cpf = $this->valid("cpf", $value);
	}

	public function getIdmunNascimento()
	{
		return $this->idmunNascimento;
	}
	
	public function setIdmunNascimento($value)
	{
		$this->idmunNascimento = $this->valid("idmunNascimento", $value);
	}

	public function getIdpaisEstrangeiro()
	{
		return $this->idpaisEstrangeiro;
	}
	
	public function setIdpaisEstrangeiro($value)
	{
		$this->idpaisEstrangeiro = $this->valid("idpaisEstrangeiro", $value);
	}

	public function getRaca()
	{
		return $this->raca;
	}
	
	public function setRaca(Raca $raca = null)
	{
		$this->raca = $this->valid('raca', $raca);
	}

    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto)
    {
        $this->foto = $this->valid("foto", $foto);
    }

	public function getEstadoCivil()
	{
		return $this->estadoCivil;
	}

	public function setEstadoCivil(EstadoCivil $estadoCivil = null)
	{
		$this->estadoCivil = $this->valid("estadoCivil", $estadoCivil);
	}

    public function getPessoaPai()
    {
        return $this->pessoaPai;
    }

    public function setPessoaPai(Fisica $pessoaPai = null)
    {
        $this->pessoaPai = $this->valid("pessoaPai", $pessoaPai);
    }

    public function getPessoaMae()
    {
        return $this->pessoaMae;
    }

    public function setPessoaMae(Fisica $pessoaMae = null)
    {
        $this->pessoaMae = $this->valid("pessoaMae", $pessoaMae);
    }

	public function getDocumento()
	{
		return $this->documento;
	}

	public function setDocumento(Documento $documento = null)
	{
		$this->documento = $documento;
	}

    public function setData($data)
	{
		$this->setId(isset($data['id']) ? $data['id'] : null);
		
		if (!empty($data['dataNasc']))
			$this->setDataNasc(isset($data['dataNasc']) ? $data['dataNasc'] : null);

		if (!empty($data['sexo']))
			$this->setSexo(isset($data['sexo']) ? $data['sexo'] : null);

		if (!empty($data['dataUniao']))
			$this->setDataUniao(isset($data['dataUniao']) ? $data['dataUniao'] : null);

		if (!empty($data['dataObito']))
			$this->setDataObito(isset($data['dataObito']) ? $data['dataObito'] : null);
		
		$this->setNacionalidade(isset($data['nacionalidade']) ? $data['nacionalidade'] : null);
		
		if (!empty($data['dataChegadaBrasil']))
			$this->setDataChegadaBrasil(isset($data['dataChegadaBrasil']) ? $data['dataChegadaBrasil'] : null);
		
		if (!empty($data['ultimaEmpresa']))
			$this->setUltimaEmpresa(isset($data['ultimaEmpresa']) ? $data['ultimaEmpresa'] : null);
		
		if (!empty($data['nomeMae']))
			$this->setNomeMae(isset($data['nomeMae']) ? $data['nomeMae'] : null);
		
		if (!empty($data['nomePai']))
			$this->setNomePai(isset($data['nomePai']) ? $data['nomePai'] : null);
		
		if (!empty($data['nomeConjuge']))
			$this->setNomeConjuge(isset($data['nomeConjuge']) ? $data['nomeConjuge'] : null);
		
		if (!empty($data['nomeResponsavel']))
			$this->setNomeResponsavel(isset($data['nomeResponsavel']) ? $data['nomeResponsavel'] : null);
		
		if (!empty($data['justificativaProvisorio']))
			$this->setJustificativaProvisorio(isset($data['justificativaProvisorio']) ? $data['justificativaProvisorio'] : null);
		
		// if (!empty($data['dataRev']))
		// 	$this->setDataRev(isset($data['dataRev']) ? $data['dataRev'] : null);
		
		//$this->setOrigemGravacao(isset($data['origem_gravacao']) ? $data['origem_gravacao'] : null);
		$this->setDataCad(isset($data['dataCad']) ? $data['dataCad'] : null);
		//$this->setOperacao(isset($data['operacao']) ? $data['operacao'] : null);
		$this->setIdsisRev(isset($data['idsisRev']) ? $data['idsisRev'] : null);
		//$this->setIdsisCad(isset($data['idsis_cad']) ? $data['idsis_cad'] : null);
		$this->setRefCodSistema(isset($data['refCodSistema']) ? $data['refCodSistema'] : null);

		if (!empty($data['cpf']))
			$this->setCpf(isset($data['cpf']) ? $data['cpf'] : null);
		
		$this->setIdpesMae(isset($data['idpesMae']) ? $data['idpesMae'] : null);
		$this->setIdpesPai(isset($data['idpesPai']) ? $data['idpesPai'] : null);
		$this->setIdpesResponsavel(isset($data['idpesResponsavel']) ? $data['idpesResponsavel'] : null);
		$this->setIdmunNascimento(isset($data['idmunNascimento']) ? $data['idmunNascimento'] : null);
		$this->setIdpaisEstrangeiro(isset($data['idpaisEstrangeiro']) ? $data['idpaisEstrangeiro'] : null);
		$this->setIdesco(isset($data['idesco']) ? $data['idesco'] : null);
		$this->setIdeciv(isset($data['ideciv']) ? $data['ideciv'] : null);
		$this->setIdpesCon(isset($data['idpesCon']) ? $data['idpesCon'] : null);
		$this->setIdocup(isset($data['idocup']) ? $data['idocup'] : null);
		$this->setNome(isset($data['nome']) ? $data['nome'] : null);
		$this->setSituacao(isset($data['situacao']) ? $data['situacao'] : null);
		$this->setRaca(isset($data['raca']) ? $data['raca'] : null);
        $this->setEstadoCivil( isset($data['estadoCivil']) ? $data['estadoCivil'] : null );
        $this->setPessoaMae( isset($data['pessoaMae']) ? $data['pessoaMae'] : null);

	}


	/**
	 * [$inputFilter recebe os filtros]
	 * @var Zend\InputFilter\InputFilter
	 */
	//protected $inputFilter;

	/**
	 * Configura os filtros dos campos da entidade
	 * 
	 * @return Zend\InputFilter\InputFilter
	 */
	public function getInputFilter()
	{
        //herdando o inputfilter de pessoa
        parent::getInputFilter();

        $factory = new InputFactory();
        $this->inputFilter->add($factory->createInput(array(
            'name' => 'raca',
			'required' => true,
            //'allow_empty' => true,
			'continue_if_empty' => true,
            'filters'     => array(
                array('name' => 'Null'),
            ),
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'dataNasc',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
				// 'validators' => array(
    //                array(  
    //                 'name'=>'Date',
    //                 'break_chain_on_failure' => true,
    //                 'options' => array(
    //                         'format'=>'y-m-D',
    //                         'messages' => array(
    //                             'dateFalseFormat'=>'Invalid date format, must be dd-mm-yyyy', 
    //                             'dateInvalidDate'=>'Invalid date, must be dd-mm-yyyy'
    //                         ),
    //                     ),      
    //                 ),      
    //                 array(  
    //                     'name'=>'Regex',
    //                     'options'=>array(
    //                         'messages'=>array('regexNotMatch'=>'Invalid date format, must be dd-mm-yyyy'),
    //                         //'pattern'=>'/^\d{1,2}-\d{1,2}-\d{4}$/',
    //                         //'pattern' => '\d{1,2}/\d{1,2}/\d{4}',
    //                         'pattern'=>'/^\d{1,2}-\d{1,2}-\d{4}$/',

    //                     ),      
    //                 ),      
    //             ),
            'validators' => array(
                'name' => new \Zend\Validator\Date(),
            ),
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'sexo',
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
                    false,
					'options' => array(
						'encoding' => 'UTF-8',
						'min' => 1,
						'max' => 1,
					),
                ),
            ),
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'dataUniao',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                'name' => new \Zend\Validator\Date(),
            ),
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'dataObito',
			'required' => false,
			'filters' => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				'name' => new \Zend\Validator\Date(),
			),
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'nacionalidade',
			'required' => false,
			'filters' => array(
				array('name' => 'Int'),
			),
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'dataChegadaBrasil',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                'name' => new \Zend\Validator\Date(),
            ),
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'ultimaEmpresa',
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

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'justificativaProvisorio',
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

        $this->inputFilter->add($factory->createInput(array(
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

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'estadoCivil',
            'required' => false,
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'foto',
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
                            'max' => 120,
                        ),
                    ),
                ),
		)));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'pessoaPai',
            'required' => false
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'pessoaMae',
            'required' => false
        )));

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'documento',
            'required' => true,
            'continue_if_empty' => true,
			'filters' => array(
                array('name' => 'Null'),
                //array('name' => 'Int'),
//				array('name' => 'Null',
//                    'options' => array(
//                        'type' => 'all'
//                    ),
//                )
            ),
        )));

		$this->inputFilter->add($factory->createInput(array(
            'name' => 'municipioNascimento',
            'required' => false
        )));

        return $this->inputFilter;
    }

}