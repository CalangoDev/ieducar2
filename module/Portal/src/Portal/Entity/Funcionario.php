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
 * @ORM\Table(name="""portal"".""funcionario""")
 * 
 * OBS: esta classe poderia utilizar heranca da classe Usuario\Entity\Fisica, porem 
 * teria que ver a questao de acoplamento, utilizando tornaria essa ligação forte
 */
class Funcionario extends Entity
{
	
	/**
	 * @var Integer $id Referencia ao codigo da pessoa do modulo usuario
	 * 
	 * @ORM\Id
	 * @ORM\OneToOne(targetEntity="Usuario\Entity\Fisica")
	 * @ORM\JoinColumn(name="ref_cod_pessoa_fj", referencedColumnName="idpes", onDelete="RESTRICT")
	 */
	protected $id;

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
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $ref_sec;

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
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $opcao_menu;

	/**
	 * @var  int $ref_cod_setor - Referencia do codigo do setor do usuario
	 * 
	 * @todo coluna nao utilizada, verificar se pode ser depreciado essa coluna, alem do que 
	 * tem outra coluna chamada ref_cod_setor_new que é chave estrangeira para a tabela cod_setor
	 * do schema pmidrh
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $ref_cod_setor;
	
	/**
	 * @var integer $ref_cod_funcionario_vinculo 
	 * 
	 * @todo verificar de onde vem essa informacao 
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $ref_cod_funcionario_vinculo;

	/**
	 * @var integer $tempo_expira_senha Tempo de expiracao da senha
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $tempo_expira_senha;

	/**
	 * @var integer $tempo_expira_conta Tempo de Expiracao de conta
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $tempo_expira_conta;

	/**
	 * @var date $data_troca_senha
	 * 
	 * @ORM\Column(type="date", nullable=true)
	 */
	protected $data_troca_senha;

	/**
	 * @var date $data_reativa_conta data para reativação da conta
	 * 
	 * @ORM\Column(type="date", nullable=true)
	 */
	protected $data_reativa_conta;

	/**
	 * @var int $ref_ref_cod_pessoa_fj Auto relacionamento
	 * 
	 * @todo verificar o funcionamento desse auto relacionamento no sistema antigo
	 * 
	 * @ORM\ManyToOne(targetEntity="Funcionario", cascade={"persist"})
	 * @ORM\JoinColumn(name="ref_ref_cod_pessoa_fj", referencedColumnName="ref_cod_pessoa_fj", onDelete="RESTRICT")
	 */
	protected $ref_ref_cod_pessoa_fj;

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
	protected $ref_cod_setor_new;

	/**
	 * @var bigint $matricula_new
	 * 
	 * @todo  coluna redundante, ja existe uma chamada matricula e é a que esta sendo utilizada pelo o 
	 * antigo sistema, esse campo poderá ser depreciado ou removido do sistema
	 * 
	 * @ORM\Column(type="bigint", nullable=true)
	 */
	protected $matricula_new;

	/**
	 * @var  smallint $matricula_permanente Matricula Permanente no sistema
	 * 
	 * @ORM\Column(type="smallint", nullable=true)
	 */
	protected $matricula_permanente = 0;

	/**
	 * @var smallint $tipo_menu Define o tipo de menu exibido para o funcionario
	 * 
	 * @todo campo para funcionalidade do sistema antigo, no novo ainda não ta definido
	 * se tera dois tipo de menu
	 * @ORM\Column(type="smallint", nullable=false)
	 */
	protected $tipo_menu = 0;

	/**
	 * @var string $ip_logado Grava o ip do funcionario
	 * 
	 * @ORM\Column(type="string", length=15, nullable=true)
	 */
	protected $ip_logado;

	/**
	 * @var datetime $data_login Data que realizou o login
	 * 
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $data_login;

	/**
	 * @var string $email
	 * 
	 * @todo informacao redudante na entitade pessoa ja tem uma coluna para email
	 * verificar depois em qual dessas entidades vai ficar essa informacao
	 * 
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $email;

	/**
	 * @var string $status_token 
	 * 
	 * @todo verificar para que serve essa coluna
	 * 
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $status_token;

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
		return $this->ref_sec;
	}
	
	public function setRefSec($value)
	{
		$this->ref_sec = $this->valid("ref_sec", $value);
	}

	public function getRamal()
	{
		return $this->ramal;
	}
	
	public function setRamal($value)
	{
		$this->ramal = $this->valid("ramal", $value);
	}

	public function getSequencia()
	{
		return $this->sequencia;
	}
	
	public function setSequencia($value)
	{
		$this->sequencia = $this->valid("sequencia", $value);
	}

	public function getOpcaoMenu()
	{
		return $this->opcao_menu;
	}
	
	public function setOpcaoMenu($value)
	{
		$this->opcao_menu = $this->valid("opcao_menu", $value);
	}

	public function getRefCodSetor()
	{
		return $this->ref_cod_setor;
	}
	
	public function setRefCodSetor($value)
	{
		$this->ref_cod_setor = $this->valid("ref_cod_setor", $value);
	}

	public function getRefCodFuncionarioVinculo()
	{
		return $this->ref_cod_funcionario_vinculo;
	}
	
	public function setRefCodFuncionarioVinculo($value)
	{
		$this->ref_cod_funcionario_vinculo = $this->valid("ref_cod_funcionario_vinculo", $value);
	}

	public function getTempoExpiraSenha()
	{
		return $this->tempo_expira_senha;
	}
	
	public function setTempoExpiraSenha($value)
	{
		$this->tempo_expira_senha = $this->valid("tempo_expira_senha", $value);
	}

	public function getTempoExpiraConta()
	{
		return $this->tempo_expira_conta;
	}
	
	public function setTempoExpiraConta($value)
	{
		$this->tempo_expira_conta = $this->valid("tempo_expira_conta", $value);
	}

	public function getDataTrocaSenha()
	{
		return $this->data_troca_senha;
	}
	
	public function setDataTrocaSenha($value)
	{
		$this->data_troca_senha = $this->valid("data_troca_senha", $value);
	}

	public function getDataReativaConta()
	{
		return $this->data_reativa_conta;
	}
	
	public function setDataReativaConta($value)
	{
		$this->data_reativa_conta = $this->valid("data_reativa_conta", $value);
	}

	public function getRefRefCodPessoaFj()
	{
		return $this->ref_ref_cod_pessoa_fj;
	}
	
	public function setRefRefCodPessoaFj($value)
	{
		$this->ref_ref_cod_pessoa_fj = $this->valid("ref_ref_cod_pessoa_fj", $value);
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
		return $this->ref_cod_setor_new;
	}
	
	public function setRefCodSetorNew($value)
	{
		$this->ref_cod_setor_new = $this->valid("ref_cod_setor_new", $value);
	}

	public function getMatriculaNew()
	{
		return $this->matricula_new;
	}
	
	public function setMatriculaNew($value)
	{
		$this->matricula_new = $this->valid("matricula_new", $value);
	}

	public function getMatriculaPermanente()
	{
		return $this->matricula_permanente;
	}
	
	public function setMatriculaPermanente($value)
	{
		$this->matricula_permanente = $this->valid("matricula_permanente", $value);
	}

	public function getTipoMenu()
	{
		return $this->tipo_menu;
	}
	
	public function setTipoMenu($value)
	{
		$this->tipo_menu = $this->valid("tipo_menu", $value);
	}

	public function getIpLogado()
	{
		return $this->ip_logado;
	}
	
	public function setIpLogado($value)
	{
		$this->ip_logado = $this->valid("ip_logado", $value);
	}

	public function getDataLogin()
	{
		return $this->data_login;
	}
	
	public function setDataLogin($value)
	{
		$this->data_login = $this->valid("data_login", $value);
	}

	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($value)
	{
		$this->email = $this->valid("email", $value);
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

			$inputFilter->add($factory->createInput(array(
				'name' => 'id',
				'required' => true,				
			)));

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
				'name' => 'ref_sec',
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
				'name' => 'opcao_menu',
				'required' => false,
				'allow_empty' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ref_cod_setor',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ref_cod_funcionario_vinculo',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tempo_expira_senha',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tempo_expira_conta',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'data_troca_senha',
				'required' => false,				
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'data_reativa_conta',
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
				'name' => 'matricula_new',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'matricula_permanente',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tipo_menu',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ip_logado',
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
				'name' => 'data_login',
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
				'name' => 'status_token',
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


			$this->inputFilter = $inputFilter;
		}
		return $this->inputFilter;
	}
}