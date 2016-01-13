<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 13/01/16
 * Time: 18:45
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade AreaConhecimento
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage AreaConhecimento
 * @version 0.2
 * @example Class AreaConhecimento
 * @copyright Copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_area_conhecimento")
 */
class AreaConhecimento extends Entity
{
    /**
     * @var Int $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var String $nome
     *
     * @ORM\Column(type="string", length=60)
     */
    protected $nome;

    /**
     * @var String $secao
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $secao;

    /**
     * getters and setters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $this->valid('nome', $nome);
    }

    public function getSecao()
    {
        return $this->secao;
    }

    public function setSecao($secao)
    {
        $this->secao = $this->valid('secao', $secao);
    }

    /**
     * @var Zend\InputFilter\InputFilter $inputFilter
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
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'nome',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 0,
                            'max' => 60,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'secao',
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
                            'max' => 50,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;

        }
        return $this->inputFilter;
    }

}