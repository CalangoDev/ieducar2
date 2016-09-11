<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 03/09/16
 * Time: 09:28
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class TipoTurma
 * @package Escola\Entity
 * @category Entity
 * @subpackage TipoTurma
 * @version 0.1
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_turma_tipo")
 */
class TipoTurma extends Entity
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
     * @var string $sigla
     *
     * @ORM\Column(type="string", length=15)
     */
    protected $sigla;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $this->valid('nome', $nome);
    }

    /**
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param string $sigla
     */
    public function setSigla($sigla)
    {
        $this->sigla = $this->valid('sigla', $sigla);
    }

    /**
     * @var Zend\InputFilter\InputFilter $inputFilter
     */
    protected $inputFilter;

    /**
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
                'name' => 'sigla',
                'required' => false,
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
                            'max' => 15,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}