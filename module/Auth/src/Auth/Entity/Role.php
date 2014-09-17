<?php
namespace Auth\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

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
 * @ORM\HasLifecycleCallbacks
 */

class Role extends Entity
{
	/**
	 * @var int $id Identificador da entidade Role
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")	 
	 */
	protected $id;

	/**
	 * @var int $funcionario Id do funcionario 
	 * 	 
	 * @ORM\ManyToOne(targetEntity="Portal\Entity\Funcionario", cascade={"persist"})
	 * @ORM\JoinColumn(name="funcionario_id", referencedColumnName="id", onDelete="RESTRICT")
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
	protected $privilegio = 0;

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
		$this->funcionario = $value;
	}

	public function getResource()
	{
		return $this->resource;
	}
	
	public function setResource($value)
	{
		$this->resource = $value;
	}

	public function getPrivilegio()
	{
		return $this->privilegio;
	}
	
	public function setPrivilegio($value)
	{
		$this->privilegio = $this->valid("privilegio", $value);
	}

	/**
	 * Funcao para checar se o privilegio é um inteiro 0 ou 1
	 * @access  public
	 * @return  Exception 
	 * @ORM\PrePersist
	 */
	public function checkPrivilegio()
	{			
		if (($this->getPrivilegio() != 0) && ($this->getPrivilegio() != 1))			
			throw new EntityException("O atributo privilégio recebeu um valor inválido: \"" . $this->getPrivilegio(). "\"", 1);
	}

	/**
	 * [$inputFilter description]
	 * @var [type]
	 */
	protected $inputFilter;

	/**
	 * Configura os filtros dos campos da entidade
	 * 
	 * @return Zend\InputFilter\InputFilter
	 */
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

			$inputFilter->add($factory->createInput(array(
				'name' => 'privilegio',
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