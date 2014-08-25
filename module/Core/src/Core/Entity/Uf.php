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
 * Entidade Uf - Representa os Estados, com os ranges de ceps
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Core
 * @subpackage  Cep Uf
 * @version  0.1
 * @example  Classe CEP Uf
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="cep.uf")
 * 
 */
class Uf extends Entity
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="cep.seq_uf", initialValue=1, allocationSize=1)
	 */
	protected $id;

	/**
	 * @var string $uf
	 * 
	 * @ORM\Column(type="string", length=2, nullable=false, name="UF")
	 */
	protected $uf;

	/**
	 * @var string $nome
	 * 
	 * @ORM\Column(type="string", length=72, nullable=false, name="Nome")
	 */
	protected $nome;

	/**
	 * @var string $cep1
	 * 
	 * @ORM\Column(type="string", length=5, nullable=false, name="Cep1")
	 */
	protected $cep1;

	/**
	 * @var string $cep2
	 * 
	 * @ORM\Column(type="string", length=5, nullable=false, name="Cep2")
	 */
	protected $cep2;

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

	public function getUf()
	{
		return $this->uf;
	}

	public function setUf($value)
	{
		$this->uf = $this->valid("uf", $value);
	}

	public function getNome()
	{
		return $this->nome;
	}

	public function setNome($value)
	{
		$this->nome = $this->valid("nome", $value);
	}

	public function getCep1()
	{
		return $this->cep1;
	}

	public function setCep1($value)
	{
		$this->cep1 = $this->valid("cep1", $value);
	}

	public function getCep2()
	{
		return $this->cep2;
	}

	public function setCep2($value)
	{
		$this->cep2 = $this->valid("cep2", $value);
	}
	/**
	 * @todo make filters 
	 */
}