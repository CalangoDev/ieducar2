<?php
namespace Core\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Ro - Representa os ceps do estado de Rondônia, onde a cidade não tem cep único
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Core
 * @subpackage  Cep Ro
 * @version  0.1
 * @example  Classe CEP Ro
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="cep_ro")
 * 
 */
class Ro extends Entity
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")	 
	 */
	protected $id;

	/**
	 * @var string $cidade
	 * 
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $cidade;

	/**
	 * @var string $logradouro
	 * 
	 * @ORM\Column(type="string", length=70, nullable=true)
	 */
	protected $logradouro;

	/**
	 * @var string $bairro
	 * 
	 * @ORM\Column(type="string", length=72, nullable=true)
	 */
	protected $bairro;

	/**
	 * @var string $cep
	 * 
	 * @ORM\Column(type="string", length=9, nullable=false)
	 */
	protected $cep;

	/**
	 * @var string $tipoLogradouro
	 * 
	 * @ORM\Column(name="tp_logradouro", type="string", length=20, nullable=true)
	 */
	protected $tipoLogradouro;

	/**
	 * Getters and Setters
	 */
	public function getId()
	{
		return $this->id;
	}

	public function setId($value)
	{
		$this->id = $this->valid("id", $value);
	}

	public function getCidade()
	{
		return $this->cidade;
	}

	public function setCidade($value)
	{
		$this->cidade = $this->valid("cidade", $value);
	}

	public function getLogradouro()
	{
		return $this->logradouro;
	}

	public function setLogradouro($value)
	{
		$this->logradouro = $this->valid("logradouro", $value);
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

	public function getTipoLogradouro()
	{
		return $this->tipoLogradouro;
	}

	public function setTipoLogradouro($value)
	{
		$this->tipoLogradouro = $this->valid("tipoLogradouro", $value);
	}

	/**
	 * @todo make filters 
	 */
}