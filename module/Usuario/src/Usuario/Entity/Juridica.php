<?php
namespace Usuario\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Doctrine\ORM\Mapping\Index;

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
 * @ORM\Table(name="cadastro_juridica")
 * ORM\Table(name="cadastro_juridica", indexes={@index(name="un_juridica_cnpj", columns={"cnpj"})})
 */
//class Juridica extends Pessoa implements EventSubscriber
class Juridica extends Pessoa
{
//	public function getSubscribedEvents ()
//    {
//        return array(
//
//        );
//    }

	/**
	 * @var string $cnpj
	 * @ORM\Column(type="string", length=14, nullable=true)
	 */
	protected $cnpj;

	/**
	 * @var string $inscricaoEstadual
	 * @ORM\Column(type="string", length=20, nullable=true)
	 */
	protected $inscricaoEstadual;

	/**
	 * @var string $fantasia
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $fantasia;

	/**
	 * @var string $capitalSocial
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $capitalSocial;

	/**
	 * getters and setters
	 */
    public function getCapitalSocial()
    {
        return $this->capitalSocial;
    }

    public function setCapitalSocial($capitalSocial)
    {
        $this->capitalSocial = $this->valid('capitalSocial', $capitalSocial);
    }

    public function getCnpj()
    {
        return $this->cnpj;
    }

    public function setCnpj($cnpj)
    {
        $this->cnpj = $this->valid('cnpj', $cnpj);
    }

    public function getFantasia()
    {
        return $this->fantasia;
    }

    public function setFantasia($fantasia)
    {
        $this->fantasia = $this->valid('fantasia', $fantasia);
    }

    public function getInscricaoEstadual()
    {
        return $this->inscricaoEstadual;
    }

    public function setInscricaoEstadual($inscricaoEstadual)
    {
        $this->inscricaoEstadual = $this->valid('inscricaoEstadual', $inscricaoEstadual);
    }

	/**
	 * Configura os filtros dos campos da entidade
	 * 
	 * @return Zend\InputFilter\InputFilter
	 */
	public function getInputFilter()
	{
        parent::getInputFilter();

		$factory = new InputFactory();

        $this->inputFilter->add($factory->createInput(array(
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

        $this->inputFilter->add($factory->createInput(array(
            'name' => 'inscricaoEstadual',
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

        $this->inputFilter->add($factory->createInput(array(
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

        $this->inputFilter->add($factory->createInput(array(
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

		return $this->inputFilter;
	}

}