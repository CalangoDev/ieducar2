<?php
namespace Drh\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Usuario\Entity\Fisica;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
/**
 * Entidade Funcionario
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Drh
 * @subpackage  Funcionario
 * @version  0.1
 * @example  Classe Funcionario
 * @copyright  Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 * 
 * @ORM\Entity
 * @ORM\Table(name="drh_funcionario")
 *
 */
class Funcionario extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * unique=true
     * @ORM\OneToOne(targetEntity="Usuario\Entity\Fisica", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="idpes")
     */
    protected $fisica;

	/**
	 * @var string $matricula Matricula do funcionario
	 * 
	 * @ORM\Column(type="string", length=12, nullable=false, unique=true)
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
	 * @var string $ramal
	 * 
	 * @ORM\Column(type="string", length=10, nullable=true)
	 */
	protected $ramal;

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
	protected $vinculo;

	/**
	 * @var integer $tempo_expira_conta Tempo de Expiracao de conta
	 *
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $tempoExpiraConta;

	/**
	 * @var date $data_troca_senha
	 * 
	 * @ORM\Column(type="date", nullable=true)
	 */
	protected $dataTrocaSenha;

	/**
	 * @var date $data_reativa_conta data para reativação da conta
	 * 
	 * @ORM\Column(type="date", nullable=true)
	 */
	protected $dataReativaConta;

	/**
	 * @var int $banido
	 * 0 - Não
     * 1 - Sim
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $banido = 0;

	/**
	 * @var int $codigoSetor Chave estrangeira para a tabela pmidrh.setor
	 * 
	 * @ORM\ManyToOne(targetEntity="Drh\Entity\Setor", cascade={"persist"})
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="RESTRICT")
	 */
	protected $codigoSetor;

	/**
	 * @var  smallint $matricula_permanente Matricula Permanente no sistema
	 * 
	 * @ORM\Column(name="matricula_permanente", type="smallint", nullable=true)
	 */
	protected $matriculaPermanente = 0;

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
	 * @var int $superAdmin
	 * (0 para nao superadmin e 1 para superadmin)
	 * 
	 * @ORM\Column(type="smallint", length=1, nullable=true)
	 */
	protected $superAdmin = 0;

	/**
	 * getters and setters
	 */

    public function getId()
    {
        return $this->id;
    }

    public function getFisica()
    {
        return $this->fisica;
    }

    public function setFisica(\Usuario\Entity\Fisica $fisica)
    {
        $this->fisica = $this->valid('fisica', $fisica);
    }

	public function getMatricula()
	{
		return $this->matricula;
	}

    public function setMatricula($matricula)
    {
        $this->matricula = $this->valid('matricula', $matricula);
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $this->valid('senha', md5($senha));
    }

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
    }

    public function getRamal()
    {
        return $this->ramal;
    }

    public function setRamal($ramal)
    {
        $this->ramal = $this->valid('ramal', $ramal);
    }

    public function getVinculo()
    {
        return $this->vinculo;
    }

    public function setVinculo($vinculo)
    {
        $this->vinculo = $this->valid('vinculo', $vinculo);
    }

    public function getTempoExpiraConta()
    {
        return $this->tempoExpiraConta;
    }

    public function setTempoExpiraConta($tempoExpiraConta)
    {
        $this->tempoExpiraConta = $this->valid('tempoExpiraConta', $tempoExpiraConta);
    }

    public function getDataTrocaSenha()
    {
        return $this->dataTrocaSenha;
    }

    public function setDataTrocaSenha($dataTrocaSenha)
    {
        $this->dataTrocaSenha = $this->valid('dataTrocaSenha', $dataTrocaSenha);
    }

    public function getDataReativaConta()
    {
        return $this->dataReativaConta;
    }

    public function setDataReativaConta($dataReativaConta)
    {
        $this->dataReativaConta = $this->valid('dataReativaConta', $dataReativaConta);
    }

    public function getBanido()
    {
        return $this->banido;
    }

    public function setBanido($banido)
    {
        $this->banido = $this->valid('banido', $banido);
    }

    public function getCodigoSetor()
    {
        return $this->codigoSetor;
    }

    public function setCodigoSetor(\Drh\Entity\Setor $codigoSetor = null)
    {
        $this->codigoSetor = $this->valid('codigoSetor', $codigoSetor);
    }

    public function getMatriculaPermanente()
    {
        return $this->matriculaPermanente;
    }

    public function setMatriculaPermanente($matriculaPermanente)
    {
        $this->matriculaPermanente = $this->valid('matriculaPermanente', $matriculaPermanente);
    }

    public function getIpLogado()
    {
        return $this->ipLogado;
    }

    public function setIpLogado($ipLogado)
    {
        $this->ipLogado = $this->valid('ipLogado', $ipLogado);
    }

    public function getDataLogin()
    {
        return $this->dataLogin;
    }

    public function setDataLogin($dataLogin)
    {
        $this->dataLogin = $this->valid('dataLogin', $dataLogin);
    }

    public function getSuperAdmin()
    {
        return $this->superAdmin;
    }

    public function setSuperAdmin($superAdmin)
    {
        $this->superAdmin = $this->valid('superAdmin', $superAdmin);
    }

    /**
     * [$inputFilter recebe os filtros]
     * @var Zend\InputFilter\InputFilter
     */
    protected $inputFilter;

	/**
	 * Configura os filtros dos campos da entidade
	 * 
	 * @return Zend\InputFilter\Inputfilter
	 */
	public function getInputFilter()
	{
        //herdando o inputfilter de fisica
        if (!$this->inputFilter){

            $factory = new InputFactory();
            $inputFilter = new InputFilter();

            $inputFilter->add($factory->createInput(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));


            $inputFilter->add($factory->createInput(array(
                'name' => 'matricula',
                'required' => true,
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
                'required' => true,
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
                'name' => 'vinculo',
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
                'name' => 'banido',
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
                'name' => 'superAdmin',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'fisica',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'codigoSetor',
                'required' => false
            )));

            $this->inputFilter = $inputFilter;
        }

		return $this->inputFilter;
	}
}