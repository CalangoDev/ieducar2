<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/05/16
 * Time: 09:58
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade ComodoPredio (Comodos do predio)
 * @package Escola\Entity
 * @category Entidade
 * @subpackage ComodoPredio
 * @version 0.1
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_infra_predio_comodo")
 */
class ComodoPredio extends Entity
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
     * @var float $area
     *
     * @ORM\Column(type="float", nullable=false)
     */
    protected $area;

    /**
     * @var bool $ativo
     *
     * @ORM\Column(type="boolean")
     */
    protected $ativo = true;

    /**
     * @var int $comodoFuncao
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\ComodoFuncao", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $comodoFuncao;

    /**
     * @var int $predio
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Predio", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $predio;

    /**
     * getters ands setters
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

    public function getArea()
    {
        return $this->area;
    }

    public function setArea($area)
    {
        $this->area = $this->valid('area', $area);
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $this->valid('descricao', $descricao);
    }

    public function isAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
    }

    public function getComodoFuncao()
    {
        return $this->comodoFuncao;
    }

    public function setComodoFuncao(\Escola\Entity\ComodoFuncao $comodoFuncao)
    {
        $this->comodoFuncao = $this->valid('comodoFuncao', $comodoFuncao);
    }

    public function getPredio()
    {
        return $this->predio;
    }

    public function setPredio(\Escola\Entity\Predio $predio)
    {
        $this->predio = $this->valid('predio', $predio);
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
                            'min' => 1,
                            'max' => 255,
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
                'name' => 'area',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Float',
                        'options' => array(
                            'min' => 1,
                            'locale' => 'en_US'
                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'ativo',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'comodoFuncao',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'predio',
                'required' => true,
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}