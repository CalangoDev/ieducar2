<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 10:54
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade TipoDispensa
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @package Escola
 * @subpackage TipoDispensa
 * @version 0.1
 * @example Class TipoDispensa
 * @copyright Copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_tipo_dispensa")
 */
class TipoDispensa extends Entity
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
     * @ORM\Column(type="string", length=255)
     */
    protected $nome;

    /**
     * @var text $descricao
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descricao;

    /**
     * @var boolean $ativo
     *
     * @ORM\Column(type="boolean")
     */
    protected $ativo = true;

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

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
    }

    protected $inputFilter;

    /**
     * Configura os filtros dos campos da entidades
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
                    array('name' => 'Int'),
                )
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
                'name' => 'ativo',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }


}