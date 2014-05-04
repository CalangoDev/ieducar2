<?php
namespace Usuario\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Index;
use Doctrine\Common\EventSubscriber;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Juridica
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Usuario
 * @subpackage  Juridica
 * @version  0.1
 * @example  Classe Juridica
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""cadastro"".""juridica""", indexes={@index(name="un_juridica_cnpj", columns={"cnpj"})})
 * @ORM\HasLifecycleCallbacks
 */
class Juridica extends Pessoa implements EventSubscriber
{
	public function getSubscribedEvents ()
    {
        return array(
                        
        );
    }
	/**
	 * @var Int $id Identificador da entidade fisica
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="cadastro.seq_juridica", initialValue=1, allocationSize=1)	 
	 */
	protected $id;

	/**
	 * @var string $cnpj
	 * @ORM\Column(type="string", length=14, nullable=false)
	 */
	protected $cnpj;

	/**
	 * @var string $insc_estadual
	 * @ORM\Column(name="insc_estadual", type="string", length=20, nullable=true)
	 */
	protected $inscEstadual;

	/**
	 * @var string $fantasia
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $fantasia;

	/**
	 * @var string $capital_social
	 * @ORM\Column(name="capital_social", type="string", length=255, nullable=true)
	 */
	protected $capitalSocial;

	/**
	 * getters and setters
	 */
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
		return $this->inscEstadual;
	}
	
	public function setInscEstadual($value)
	{
		$this->inscEstadual = $this->valid("inscEstadual", $value);
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
		return $this->capitalSocial;
	}
	
	public function setCapitalSocial($value)
	{
		$this->capitalSocial = $this->valid("capitalSocial", $value);
	}

	public function setData($data)
	{
		
	}

	/**
	 * Gatilhos
	 * 
	 * Verificar se a pessoa é uma pessoa fisica antes de inserir a pessoa juridica, se for lancar exceção - OK
	 */
	
	/**
	 * Funcao para checar se o tipo do usuario é dierente de J se for lanca exception
	 * @access public
	 * @return  Exception 
	 * @ORM\PrePersist
	 */
	public function checkTipo()
	{		
		if(($this->getTipo() != "J") && ($this->getTipo() != "P") && ($this->getTipo() != ""))
			throw new EntityException("O Identificador " . $this->getId() . " já está cadastrado como Pessoa Física: " . $this->getTipo(), 1);
	}

	/**
	 * [$inputFilter recebe os filtros]
	 * @var Zend\InputFilter\InputFilter
	 */
	protected $inputFilter;

	/**
	 * Configura os filtros dos campos da entidade
	 * 
	 * @return Zend\InputFilter\InputFilter
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
					array('name' => 'Int')
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'cnpj',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alnum'),					
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 14,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'inscEstadual',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alnum'),					
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 20,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpesRev',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'dataRev',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'origemGravacao',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alpha'),
					array('name' => 'StringToUpper'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 1,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpesCad',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'operacao',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alpha'),
					array('name' => 'StringToUpper'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 1,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idsisRev',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idsisCad',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'fantasia',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),				
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 255,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'capitalSocial',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 255,
						),
					),
				),
			)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

}