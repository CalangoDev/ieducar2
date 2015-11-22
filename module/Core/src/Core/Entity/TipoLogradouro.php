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
 * Entidade TipoLogradouro
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Core
 * @subpackage  TipoLogradouro
 * @version  0.1
 * @example  Classe TipoLogradouro
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="urbano_tipo_logradouro")
 */
class TipoLogradouro extends Entity
{
	/**
	 * @var string $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")	 
	 */
	protected $id;

	/**
	 * @var string $descricao	 
	 * @ORM\Column(type="string", length=40, nullable=false)
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

	public function getDescricao()
	{
		return $this->descricao;
	}

	public function setDescricao($value)
	{
		$this->descricao = $this->valid("descricao", $value);
	}

	protected $inputFilter;

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
                'name' => 'descricao',
                'required' => true,
                'filters'	=>	array(
                    array('name'	=>	'StripTags'),
                    array('name'	=>	'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 40,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
		}

        return $this->inputFilter;
	}
}