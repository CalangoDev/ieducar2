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
 * Entidade Juridica
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Historico
 * @subpackage  Juridica
 * @version  0.1
 * @example  Classe Juridica
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="historico_juridica")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Juridica extends Entity implements InputFilterAwareInterface 
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")	 
	 */
	protected $id;

	/**
	 * @var int $idpes Identificador da entidade pessoa
	 * @ORM\Column(name="idpes", type="integer", nullable=false)
	 */
	protected $idpes;

	/**
	 * @var string $cnpj CPNJ da Pessoa
	 * @ORM\Column(type="string", length=14, nullable=false)
	 */
	protected $cnpj;

	/**
	 * @var string $insc_estadual
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	protected $insc_estadual;

	/**
	 * @var  string $fantasia
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $fantasia;

	/**
	 * @var string $capital_social
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $capital_social;

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

	public function getCnpj()
	{
		return $this->cnpj;
	}
	
	public function setCnpj($value)
	{
		$this->cnpj = $this->valid("cnpj", $value);
	}

	public function getInscEstadual()
	{
		return $this->insc_estadual;
	}
	
	public function setInscEstadual($value)
	{
		$this->insc_estadual = $this->valid("insc_estadual", $value);
	}

	public function getFantasia()
	{
		return $this->fantasia;
	}
	
	public function setFantasia($value)
	{
		$this->fantasia = $this->valid("fantasia", $value);
	}

	public function getCapitalSocial()
	{
		return $this->capital_social;
	}
	
	public function setCapitalSocial($value)
	{
		$this->capital_social = $this->valid("capital_social", $value);
	}

	
}