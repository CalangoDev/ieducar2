<?php
namespace Portal\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Funcionario
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Portal
 * @subpackage  Funcionario
 * @version  0.1
 * @example  Classe Funcionario
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="portal.funcionario")
 * 
 * OBS: esta classe poderia utilizar heranca da classe Usuario\Entity\Fisica, porem 
 * teria que ver a questao de acoplamento, utilizando tornaria essa ligação forte
 */
class Funcionario extends Entity
{
	
	/**
	 * @var Int $id Identificador da entidade fisica
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="portal.seq_funcionario", initialValue=1, allocationSize=1)	 
	 */
	protected $id;

	/**
	 * @var Integer $id Referencia ao codigo da pessoa do modulo usuario
	 * ORM\Id	 
	 * @ORM\OneToOne(targetEntity="Usuario\Entity\Fisica")
	 * @ORM\JoinColumn(name="ref_cod_pessoa_fj", referencedColumnName="idpes", nullable=false)
	 */
	protected $refCodPessoaFj;
	
	/**
	 * @var string $matricula Matricula do funcionario
	 * 
	 * @ORM\Column(type="string", length=12, nullable=true)
	 */
	protected $matricula;

	/**
	 * @var string $senha
	 * 
	 * @ORM\Column(type="string", length=32, nullable=true)
	 */
	protected $senha;

	/**
	 * @var int $ativo (0 para nao ativo e 1 para ativo)
	 * 
	 * @ORM\Column(type="smallint", length=1, nullable=true)
	 */
	protected $ativo;

	/**
	 * @var int $ref_sec (Referencia de Secretaria?)
	 * 
	 * @todo verificar utilidade desse campo
	 * 
	 * @ORM\Column(name="ref_sec", type="integer", nullable=true)
	 */
	protected $refSec;

	/**
	 * @var string $ramal
	 * 
	 * @ORM\Column(type="string", length=10, nullable=true)
	 */
	protected $ramal;

	/**
	 * @var string $sequencial
	 * 
	 * @todo verificar a utilidade desse campo e o significado
	 * 
	 * @ORM\Column(type="string", length=3, nullable=true)
	 */
	protected $sequencial;

	/**
	 * @var text $opcao_menu
	 * 
	 * @ORM\Column(name="opcao_menu", type="text", nullable=true)
	 */
	protected $opcaoMenu;

	/**
	 * @var  int $ref_cod_setor - Referencia do codigo do setor do usuario
	 * 
	 * @todo coluna nao utilizada, verificar se pode ser depreciado essa coluna, alem do que 
	 * tem outra coluna chamada ref_cod_setor_new que é chave estrangeira para a tabela cod_setor
	 * do schema pmidrh
	 * 
	 * @deprecated 2.0 Campo redudante com $ref_cod_setor_new onde a versão antiga armazena o codigo do setor do usuario
	 * @ORM\Column(name="ref_cod_setor", type="integer", nullable=true)
	 */
	protected $refCodSetor;
	
	/**
	 * @var integer $ref_cod_funcionario_vinculo 
	 * 
	 * Tipos de vinculos de emprego
	 * 
	 * Esta associado assim na antiga versão, numeros definidos na aplicação
	 * 
	 * 5 - Comissionado,
	 * 4 - Contratado,
	 * 3 - Efetivo,
	 * 6 - Estágiario 
	 * 
	 * @ORM\Column(name="ref_cod_funcionario_vinculo", type="integer", nullable=true)
	 */
	protected $refCodFuncionarioVinculo;

	/**
	 * @var integer $tempo_expira_senha Tempo de expiracao da senha
	 * 
	 * @ORM\Column(name="tempo_expira_senha", type="integer", nullable=true)
	 */
	protected $tempoExpiraSenha;

	/**
	 * @var integer $tempo_expira_conta Tempo de Expiracao de conta
	 * 
	 * @ORM\Column(name="tempo_expira_conta", type="integer", nullable=true)
	 */
	protected $tempoExpiraConta;

	/**
	 * @var date $data_troca_senha
	 * 
	 * @ORM\Column(name="data_troca_senha", type="date", nullable=true)
	 */
	protected $dataTrocaSenha;

	/**
	 * @var date $data_reativa_conta data para reativação da conta
	 * 
	 * @ORM\Column(name="data_reativa_conta", type="date", nullable=true)
	 */
	protected $dataReativaConta;

	/**
	 * @var int $ref_ref_cod_pessoa_fj Auto relacionamento
	 * 
	 * @todo verificar o funcionamento desse auto relacionamento no sistema antigo
	 * 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Fisica", cascade={"persist"})
	 * @ORM\JoinColumn(name="ref_ref_cod_pessoa_fj", referencedColumnName="idpes", onDelete="RESTRICT")	 
	 */
	protected $refRefCodPessoaFj;

	/**
	 * @var int $proibido 
	 * @todo verificar a logica dessa coluna proibido
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $proibido = 0;

	/**
	 * @var int $ref_cod_setor_new Chave estrangeira para a tabela pmidrh.setor
	 * 
	 * @todo  ja tem uma coluna na tabela chamada ref_cod_setor porem nao aponta pra lugar algum
	 * vejo essa coluna como redundante, verificar a compatibilidade com versoes anteriores e o codigo
	 * do sistema antigo, uma das duas colunas devera ser setada como depreciada
	 * 
	 * @ORM\ManyToOne(targetEntity="Drh\Entity\Setor", cascade={"persist"})
	 * @ORM\JoinColumn(name="ref_cod_setor_new", referencedColumnName="cod_setor", onDelete="RESTRICT")
	 */
	protected $refCodSetorNew;

	/**
	 * @var bigint $matricula_new
	 * 
	 * @deprecated 2.0 Campo nao utilizado no sistema antigo e redudante por ja existe um campo chamado matricula
	 * 
	 * @ORM\Column(name="matricula_new", type="bigint", nullable=true)
	 */
	protected $matriculaNew;

	/**
	 * @var  smallint $matricula_permanente Matricula Permanente no sistema
	 * 
	 * @ORM\Column(name="matricula_permanente", type="smallint", nullable=true)
	 */
	protected $matriculaPermanente = 0;

	/**
	 * @var smallint $tipo_menu Define o tipo de menu exibido para o funcionario
	 * 
	 * @todo campo para funcionalidade do sistema antigo, no novo ainda não ta definido
	 * se tera dois tipo de menu
	 * @ORM\Column(name="tipo_menu", type="smallint", nullable=false)
	 */
	protected $tipoMenu = 0;

	/**
	 * @var string $ip_logado Grava o ip do funcionario
	 * 
	 * @ORM\Column(name="ip_logado", type="string", length=15, nullable=true)
	 */
	protected $ipLogado;

	/**
	 * @var datetime $data_login Data que realizou o login
	 * 
	 * @ORM\Column(name="data_login", type="datetime", nullable=true)
	 */
	protected $dataLogin;

	/**
	 * @var string $email
	 * 
	 * @deprecated 2.0 Na Entidade Pessoa já existe essa informação. redundância de dados
	 * 
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $email;

	/**
	 * @var string $status_token 
	 * 
	 * @deprecated 2.0 Coluna não utilizada no antigo sistema, não encontrei nenhuam referência a esse campo
	 * 
	 * @ORM\Column(name="status_token", type="string", length=50, nullable=true)
	 */
	protected $statusToken;

	/**
	 * @var int $superAdmin
	 * (0 para nao superadmin e 1 para superadmin)
	 * 
	 * @ORM\Column(type="smallint", length=1, nullable=true)
	 */
	protected $superAdmin = 0;

	public function setData($data)
	{			
		// var_dump($data);
		$this->setId( isset($data['id']) ? $data['id'] : null );		
		$this->setRefCodPessoaFj( isset($data['refCodPessoaFj']) ? $data['refCodPessoaFj'] : null );
		$this->setMatricula( isset($data['matricula']) ? $data['matricula'] : null );
		$this->setSenha( isset($data['senha']) ? $data['senha'] : null );
		$this->setAtivo( isset($data['ativo']) ? $data['ativo'] : null);
		$this->setRefSec( isset($data['refSec']) ? $data['refSec'] : null );
		$this->setRamal( isset($data['ramal']) ? $data['ramal'] : null );
		$this->setSequencial( isset($data['sequencial']) ? $data['sequencial'] : null );
		$this->setOpcaoMenu( isset($data['opcaoMenu']) ? $data['opcaoMenu'] : null );
		$this->setRefCodSetor( isset($data['refCodSetor']) ? $data['refCodSetor'] : null );
		$this->setRefCodFuncionarioVinculo( isset($data['refCodFuncionarioVinculo']) ? $data['refCodFuncionarioVinculo'] : null );
		$this->setTempoExpiraSenha( isset($data['tempoExpiraSenha']) ? $data['tempoExpiraSenha'] : null );
		$this->setTempoExpiraConta( isset($data['tempoExpiraConta']) ? $data['tempoExpiraConta'] : null);
		$this->setDataTrocaSenha( isset($data['dataTrocaSenha']) ? $data['dataTrocaSenha'] : null );
		$this->setDataReativaConta( isset($data['dataReativaConta']) ? $data['dataReativaConta'] : null );
		$this->setRefRefCodPessoaFj( isset($data['refRefCodPessoaFj']) ? $data['refRefCodPessoaFj'] : null);
		$this->setProibido( isset($data['proibido']) ? $data['proibido'] : null );
		$this->setRefCodSetorNew( isset($data['refCodSetorNew']) ? $data['refCodSetorNew'] : null );		
		$this->setMatriculaNew( isset($data['matriculaNew']) ? $data['matriculaNew'] : null );
		$this->setMatriculaPermanente( isset($data['matriculaPermanente']) ? $data['matriculaPermanente'] : null );
		$this->setTipoMenu( isset($data['tipoMenu']) ? $data['tipoMenu'] : null );
		$this->setIpLogado( isset($data['ipLogado']) ? $data['ipLogado'] : null );
		$this->setDataLogin( isset($data['dataLogin']) ? $data['dataLogin'] : null );
		$this->setEmail( isset($data['email']) ? $data['email'] : null );		
				
		// if (!empty($data['url']))
		// 	$this->setUrl(isset($data['url']) ? $data['url'] : null);		
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

	public function getRefCodPessoaFj()
	{
		return $this->refCodPessoaFj;
	}
	
	public function setRefCodPessoaFj($value)
	{		
		// $this->ref_cod_pessoa_fj = $this->valid("ref_cod_pessoa_fj", $value);
		$this->refCodPessoaFj = $value;
	}

	public function getMatricula()
	{
		return $this->matricula;
	}
	
	public function setMatricula($value)
	{
		$this->matricula = $this->valid("matricula", $value);
	}

	public function getSenha()
	{
		return $this->senha;
	}
	
	public function setSenha($value)
	{
		$this->senha = md5($this->valid("senha", $value));
	}

	public function getAtivo()
	{
		return $this->ativo;
	}
	
	public function setAtivo($value)
	{
		$this->ativo = $this->valid("ativo", $value);
	}

	public function getRefSec()
	{
		return $this->refSec;
	}
	
	public function setRefSec($value)
	{
		$this->refSec = $this->valid("refSec", $value);
	}

	public function getRamal()
	{
		return $this->ramal;
	}
	
	public function setRamal($value)
	{
		$this->ramal = $this->valid("ramal", $value);
	}

	public function getSequencial()
	{
		return $this->sequencial;
	}
	
	public function setSequencial($value)
	{
		$this->sequencial = $this->valid("sequencial", $value);
	}

	public function getOpcaoMenu()
	{
		return $this->opcaoMenu;
	}
	
	public function setOpcaoMenu($value)
	{
		$this->opcaoMenu = $this->valid("opcaoMenu", $value);
	}

	public function getRefCodSetor()
	{
		return $this->refCodSetor;
	}
	
	public function setRefCodSetor($value)
	{
		$this->refCodSetor = $this->valid("refCodSetor", $value);
	}

	public function getRefCodFuncionarioVinculo()
	{
		return $this->refCodFuncionarioVinculo;
	}
	
	public function setRefCodFuncionarioVinculo($value)
	{
		$this->refCodFuncionarioVinculo = $this->valid("refCodFuncionarioVinculo", $value);
	}

	public function getTempoExpiraSenha()
	{
		return $this->tempoExpiraSenha;
	}
	
	public function setTempoExpiraSenha($value)
	{
		$this->tempoExpiraSenha = $this->valid("tempoExpiraSenha", $value);
	}

	public function getTempoExpiraConta()
	{
		return $this->tempoExpiraConta;
	}
	
	public function setTempoExpiraConta($value)
	{
		$this->tempoExpiraConta = $this->valid("tempoExpiraConta", $value);
	}

	public function getDataTrocaSenha()
	{
		return $this->dataTrocaSenha;
	}
	
	public function setDataTrocaSenha($value)
	{
		$this->dataTrocaSenha = $this->valid("dataTrocaSenha", $value);
	}

	public function getDataReativaConta()
	{
		return $this->dataReativaConta;
	}
	
	public function setDataReativaConta($value)
	{
		$this->dataReativaConta = $this->valid("dataReativaConta", $value);
	}

	public function getRefRefCodPessoaFj()
	{
		return $this->refRefCodPessoaFj;
	}
	
	public function setRefRefCodPessoaFj($value)
	{
		$this->refRefCodPessoaFj = $this->valid("refRefCodPessoaFj", $value);
	}

	public function getProibido()
	{
		return $this->proibido;
	}
	
	public function setProibido($value)
	{
		$this->proibido = $this->valid("proibido", $value);
	}

	public function getRefCodSetorNew()
	{
		return $this->refCodSetorNew;
	}
	
	public function setRefCodSetorNew($value)
	{
		$this->refCodSetorNew = $value;
	}

	public function getMatriculaNew()
	{
		return $this->matriculaNew;
	}
	
	public function setMatriculaNew($value)
	{
		$this->matriculaNew = $this->valid("matriculaNew", $value);
	}

	public function getMatriculaPermanente()
	{
		return $this->matriculaPermanente;
	}
	
	public function setMatriculaPermanente($value)
	{
		$this->matriculaPermanente = $this->valid("matriculaPermanente", $value);
	}
 
	public function getTipoMenu()
	{
		return $this->tipoMenu;
	}
	
	public function setTipoMenu($value)
	{
		$this->tipoMenu = $this->valid("tipoMenu", $value);
	}

	public function getIpLogado()
	{
		return $this->ipLogado;
	}
	
	public function setIpLogado($value)
	{
		$this->ipLogado = $this->valid("ipLogado", $value);
	}

	public function getDataLogin()
	{
		return $this->dataLogin;
	}
	
	public function setDataLogin($value)
	{
		$this->dataLogin = $this->valid("dataLogin", $value);
	}

	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($value)
	{
		$this->email = $this->valid("email", $value);
	}

	public function getSuperAdmin()
	{
		return $this->superAdmin;
	}

	public function setSuperAdmin($value)
	{
		$this->superAdmin = $this->valid("superAdmin", $value);
	}

	
	/**
	 * [$inputFilter description]
	 * @var [type]
	 */
	protected $inputFilter;

	/**
	 * Configura os filtros dos campos da entidade
	 * 
	 * @return Zend\InputFilter\Inputfilter
	 */
	public function getInputFilter()
	{
		if (!$this->inputFilter){
			$inputFilter = new InputFilter();
			$factory = new InputFactory();

			// $inputFilter->add($factory->createInput(array(
			// 	'name' => 'id',
			// 	'required' => true,	
			// 	'filters' => array(
			// 		array('name' => 'Int'),
			// 	),			
			// )));			

			$inputFilter->add($factory->createInput(array(
				'name' => 'matricula',
				'required' => false,
				'filters'	=>	array(
					array('name'	=>	'StripTags'),
					array('name'	=>	'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						false,
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 12,
						),
					),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'senha',
				'required' => false,
				'filters'	=>	array(
					array('name'	=>	'StripTags'),
					array('name'	=>	'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						false,
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 32,
						),
					),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ativo',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),					
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'refSec',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ramal',
				'required' => false,
				'allow_empty' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						false,
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 0,
							'max' => 10,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'sequencial',
				'required' => false,
				'allow_empty' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						false,
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 0,
							'max' => 3,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'opcaoMenu',
				'required' => false,
				'allow_empty' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
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
				'name' => 'refCodFuncionarioVinculo',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tempoExpiraSenha',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tempoExpiraConta',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'dataTrocaSenha',
				'required' => false,				
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'dataReativaConta',
				'required' => false,				
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'proibido',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));


			$inputFilter->add($factory->createInput(array(
				'name' => 'matriculaNew',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'matriculaPermanente',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tipoMenu',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ipLogado',
				'required' => false,
				'allow_empty' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						false,
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 0,
							'max' => 15,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'dataLogin',
				'required' => false,				
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'email',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'EmailAddress',
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'statusToken',
				'required' => false,
				'allow_empty' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						false,
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 0,
							'max' => 50,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'superAdmin',
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