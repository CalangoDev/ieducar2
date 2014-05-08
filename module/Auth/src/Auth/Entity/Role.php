<?php
namespace Auth\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;

/**
 * Entidade Role
 * 
 * Armazena as roles para os usuarios do sistema que estão na tabela portal.funcionario e diz qual 
 * recursos ele pode ter permissão ou não (allow ou deny)
 * 
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category Entidade
 * @package Auth
 * @version 0.1
 * @copyright Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * @example DER http://www.eduardojunior.com/ieducar/ieducar_der_auth.png 
 * 
 * @ORM\Entity
 * @ORM\Table(name="portal.role")
 *  
 */

class Role extends Entity
{
	/**
	 * @var int $id Identificador da entidade Role
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="portal.seq_role", initialValue=1, allocationSize=1)
	 */
	protected $id;

	/**
	 * @var int $funcionario Id do funcionario 
	 * 
	 * @ORM\ManyToOne(targetEntity="Usuario\Entity\Fisica", cascade={"persist"})
	 * @ORM\JoinColumn(name="funcionario_id", referencedColumnName="idpes", onDelete="RESTRICT")
	 */
	protected $funcionario;

	/**
	 * @var int $resource Recurso Id
	 * 
	 * @ORM\ManyToOne(targetEntity="Resource", cascade={"persist"})
	 * @ORM\JoinColumn(name="resource_id", referencedColumnName="id", onDelete="RESTRICT")
	 */
	protected $resource;

	/**
	 * @var int $privilegio 
	 * 
	 * 0 - allow
	 * 1 - deny
	 * 
	 * @ORM\Column(type="smallint", nullable=false)
	 */
	protected $privilegio = 1;

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

	public function getFuncionario()
	{
		return $this->funcionario;
	}
	
	public function setFuncionario($value)
	{
		$this->funcionario = $this->valid("funcionario", $value);
	}

	public function getResource()
	{
		return $this->resource;
	}
	
	public function setResource($value)
	{
		$this->resource = $this->valid("resource", $value);
	}

	public function getPrivilegio()
	{
		return $this->privilegio;
	}
	
	public function setPrivilegio($value)
	{
		$this->privilegio = $this->valid("privilegio", $value);
	}

}