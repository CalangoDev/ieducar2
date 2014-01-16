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
class Fisica extends Entity implements EventSubscriber
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
	 * @ORM\Column(name="""idpes""", type="integer", nullable=false);
	 * @ORM\OneToOne(targetEntity="Pessoa")
	 */
	protected $id;

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
		if(($this->sexo != "M") && ($this->sexo != "F"))
			throw new EntityException("O atributo sexo recebeu um valor inválido: \"" . $this->sexo. "\"", 1);
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
		if (($this->nacionalidade >= 1) && ($this->nacionalidade <= 3))			
			throw new EntityException("O atributo nacionalidade recebeu um valor inválido: \"" . $this->nacionalidade. "\"", 1);
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
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $data_rev;

	/**
	 * @var string $origem_gravacao	Origem da gravacao
	 * @ORM\Column(type="string", length=1, nullable=false)
	 */
	protected $origem_gravacao;

	/**
	 * Funcao para checar se origem de gravacao é diferente de M, U, C ou O
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkOrigemGravacao()
	{
		if(($this->origem_gravacao != "M") && ($this->origem_gravacao != "U") && ($this->origem_gravacao != "C") && ($this->origem_gravacao != "O"))
			throw new EntityException("O atributo origem_gravacao recebeu um valor inválido: \"" . $this->origem_gravacao. "\"", 1);
	}

	/**
	 * @var  DateTime $data_cad Data de cadastro
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	protected $data_cad;

	/**
	 * Função para gerar o timestamp para o atributo data_cad, é executada antes de salvar os dados no banco
	 * @access  public
	 * @return  void 
	 * @ORM\PrePersist
	 */
	public function timestamp()
	{
		if (is_null($this->data_cad)) {
			$this->data_cad = new \DateTime();
		}		
	}

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
	 * Funcao para checar se a string de operacao é diferente de I, A ou E se sim remove a variavel $this->operacao
	 * @access  public
	 * @return  Exception 
	 * @ORM\PrePersist
	 */
	public function checkOperacao()
	{
		if (($this->operacao != "I") && ($this->operacao != "A") && ($this->operacao != "E"))			
			throw new EntityException("O atributo operacao recebeu um valor inválido: \"" . $this->operacao. "\"", 1);
	}

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
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pessoa")
	 * @ORM\JoinColumn(name="idpes_mae", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $idpes_mae;

	/**
	 * @var int $idpes_pai Id pessoa do pai
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pessoa")
	 * @ORM\JoinColumn(name="idpes_pai", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $idpes_pai;

	/**
	 * @var int $idpes_responsavel	Id da pessoa responsavel
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pessoa")
	 * @ORM\JoinColumn(name="idpes_responsavel", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $idpes_responsavel;

	/**
	 * @var Int $idmun_nascimento Naturalidade obtem o id na tabela public.municipio
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Municipio")
	 * @ORM\JoinColumn(name="idmun_nascimento", referencedColumnName="idmun", onDelete="NO ACTION")
	 */
	protected $idmun_nascimento;

	/**
	 * @var Int $idpais_estrangeiro	Armazena id do pais se for estrangeiro obtem o id na tabela public.pais
	 * 	 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pais")
	 * @ORM\JoinColumn(name="idpais_estrangeiro", referencedColumnName="idpais", onDelete="NO ACTION")
	 */
	protected $idpais_estrangeiro;

	/**
	 * @var Int $idesco	Código da Escola, chave estrangeira para o campo idesco na relacao cadastro.escolaridade	 
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Escolaridade")
	 * @ORM\JoinColumn(name="idesco", referencedColumnName="idesco", onDelete="NO ACTION")
	 */
	protected $idesco;

	/**
	 * @var Int $ideciv Id do Estado Civil, chave estrangeira para o campo ideciv na relacao cadastro.estado_civil
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="EstadoCivil")
	 * @ORM\JoinColumn(name="ideciv", referencedColumnName="ideciv", onDelete="NO ACTION")
	 */
	protected $ideciv;

	/**
	 * @var int $idpes_con	Id Pessoa Conjugue
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pessoa")
	 * @ORM\JoinColumn(name="idpes_con", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $idpes_con;

	/**
	 * @var int $idocup	ID da Ocupacao
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Ocupacao")
	 * @ORM\JoinColumn(name="idocup", referencedColumnName="idocup", onDelete="NO ACTION")
	 */
	protected $idocup;

	/**
	 * @var Int $idpes_rev Id da pessoa que esta fazendo a revisao no registro
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pessoa")
	 * @ORM\JoinColumn(name="idpes_rev", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $idpes_rev;

	/**
	 * @var int $idpes_cad	Id da Pessoa que efetuou o cadastro
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Pessoa")
	 * @ORM\JoinColumn(name="idpes_cad", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $idpes_cad;

	/**
	 * @var int $ref_cod_religiao	Referencia de codigo da religiao
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 * @ORM\ManyToOne(targetEntity="Religiao")
	 * @ORM\JoinColumn(name="ref_cod_religiao", referencedColumnName="cod_religiao", onDelete="NO ACTION")
	 */
	protected $ref_cod_religiao;

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
				'required' => true,//false
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