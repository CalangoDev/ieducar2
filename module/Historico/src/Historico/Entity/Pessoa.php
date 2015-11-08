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
 * @ORM\Table(name="historico_pessoa")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Pessoa extends Entity implements InputFilterAwareInterface 
{

	protected $inputFilter;

	/**
	 * @var  Int $id
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO") 	 
	 */
	protected $id;

	/**
	 * @var  Int $id Identificador da entidade pessoa	 
	 * @ORM\Column(name="idpes", type="integer", nullable=false)
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
		$this->email = $data['email'];
		$this->situacao = $data['situacao'];
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