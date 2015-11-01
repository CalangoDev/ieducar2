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
 * Entidade Resource
 * 
 * Armazena os recursos do sistema ex: 'Application\Controller\Index.index', 'Application\Controller\Index.save',
 * Ou seja Modulo\Controller\Controlador.acao
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category Entidade
 * @package Auth
 * @version 0.1
 * @copyright Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * @example DER http://www.eduardojunior.com/ieducar/ieducar_der_auth.png 
 * 
 * @ORM\Entity
 * @ORM\Table(name="portal_resource")
 *  
 */
class Resource extends Entity
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
	 * @var string $nome nome do recurso
	 * 
	 * @ORM\Column(type="string", length=120, nullable=false)
	 */
	protected $nome;

	/**
	 * @var text $descricao descricao do recurso
	 * 
	 * @ORM\Column(type="text")
	 */
	protected $descricao;

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

	public function getDescricao()
	{
		return $this->descricao;
	}
	
	public function setDescricao($value)
	{
		$this->descricao = $this->valid("descricao", $value);
	}

	/**
	 * [$inputFilter description]
	 * @var [type]
	 */
	protected $inputFilter;

	/**
	 * Configura os filtros dos campos da entidade
	 * 
	 * @return Zend\InputFilter\Inputfilter
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
				'name' => 'nome',
				'required' => true,
				'filters'	=>	array(
					array('name'	=>	'StripTags'),
					array('name'	=>	'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						true,
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 120,
						),
					),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'descricao',
				'required' => false,
				'filters'	=>	array(
					array('name'	=>	'StripTags'),
					array('name'	=>	'StringTrim'),
				),				
			)));			

			$this->inputFilter = $inputFilter;
		}
		return $this->inputFilter;
	}

}