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
 * Entidade CepUnico - Representa as cidades que tem somente um cep
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Core
 * @subpackage  Cep CepUnico
 * @version  0.1
 * @example  Classe CEP CepUnico
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="cep_cep_unico")
 * 
 */
class CepUnico extends Entity
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="seq", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")	 
	 */
	protected $id;

	/**
	 * @var string $nome
	 * 
	 * @ORM\Column(name="Nome", type="string", length=50, nullable=false)
	 */
	protected $nome;

	/**
	 * @var string $nomeSemAcento
	 * 
	 * @ORM\Column(name="Nome_Sem_Acento", type="string", length=50, nullable=true)
	 */
	protected $nomeSemAcento;

	/**
	 * @var string $cep
	 * 
	 * @ORM\Column(type="string", length=9, nullable=true, name="Cep")
	 */
	protected $cep;

	/**
	 * @var string $uf
	 * 
	 * @ORM\Column(type="string", length=2, nullable=false, name="UF")
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

	public function getNome()
	{
		return $this->nome;
	}

	public function setNome($value)
	{
		$this->nome = $this->valid("nome", $value);
	}

	public function getNomeSemAcento()
	{
		return $this->nomeSemAcento;
	}

	public function setNomeSemAcento($value)
	{
		$this->nomeSemAcento = $this->valid("nomeSemAcento", $value);
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