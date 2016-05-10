<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 03/05/16
 * Time: 19:56
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade Modulo
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage Modulo
 * @version 0.1
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_modulo")
 */
class Modulo extends Entity
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
     * @var string $nome
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $nome;

    /**
     * @var text $descricao
     *
     * @ORM\Column(type="text")
     */
    protected $descricao;

    /**
     * @var integer $numeroMeses
     *
     * @ORM\Column(type="integer", nullable=false, length=2)
     */
    protected $numeroMeses;

    /**
     * @var integer $numeroSemanas
     *
     * @ORM\Column(type="integer", nullable=false, length=10)
     */
    protected $numeroSemanas;

    /**
     * @var boolean $ativo
     *
     * @ORM\Column(type="boolean")
     */
    protected $ativo = true;

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

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $this->valid('descricao', $descricao);
    }

    public function getNumeroMeses()
    {
        return $this->numeroMeses;
    }

    public function setNumeroMeses($numeroMeses)
    {
        $this->numeroMeses = $this->valid('numeroMeses', $numeroMeses);
    }

    public function getNumeroSemanas()
    {
        return $this->numeroSemanas;
    }

    public function setNumeroSemanas($numeroSemanas)
    {
        $this->numeroSemanas = $this->valid('numeroSemanas', $numeroSemanas);
    }

    public function isAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
    }

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
                            'min' => 1,
                            'max' => 200,
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

            $inputFilter->add($factory->createInput(array(
                'name' => 'numeroMeses',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'numeroSemanas',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'ativo',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));
            
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}