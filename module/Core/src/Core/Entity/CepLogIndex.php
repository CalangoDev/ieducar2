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
 * Entidade CepLogIndex - Representa os index do cep
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Core
 * @subpackage  Cep CepLogIndex
 * @version  0.1
 * @example  Classe CEP CepLogIndex
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="cep.cep_log_index")
 * 
 */
class CepLogIndex extends Entity
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
	 * @var string $cep
	 * 
	 * @ORM\Column(type="string", length=5, nullable=false)
	 */
	protected $cep;

	/**
	 * @var string $uf - lista dos estados
	 * 
	 * @ORM\Column(type="string", length=2, nullable=false)
	 */
	protected $uf;

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

	public function getCep()
	{
		return $this->cep;
	}

	public function setCep($value)
	{
		$this->cep = $this->valid("cep", $value);
	}

	public function getUf()
	{
		return $this->uf;
	}

	public function setUf($value)
	{
		$this->uf = $this->valid("uf", $value);
	}
	
	/**
	 * @todo make filters 
	 */
}