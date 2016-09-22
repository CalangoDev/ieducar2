<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 20/09/16
 * Time: 17:30
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Class ComponenteCurricularTurma
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entity
 * @package Escola\Entity
 * @subpackage ComponenteCurricularTurma
 * @version 0.1
 * @example Class ComponenteCurricularTurma
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_componente_curricular_turma")
 */
class ComponenteCurricularTurma extends Entity
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
     * @var Entity $serie
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Serie", cascade={"persist"})
     */
    protected $serie;

    /**
     * @var Entity $componenteCurricular
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\ComponenteCurricular", cascade={"persist"})
     */
    protected $componenteCurricular;

    /**
     * @var Entity $turma
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Turma", cascade={"persist"})
     */
    protected $turma;

    /**
     * @var float $cargaHoraria
     *
     * @ORM\Column(type="float")
     */
    protected $cargaHoraria;

    /**
     * getters and setters
     */
    /**
     * @return Int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getCargaHoraria()
    {
        return $this->cargaHoraria;
    }

    /**
     * @param float $cargaHoraria
     */
    public function setCargaHoraria($cargaHoraria)
    {
        $this->cargaHoraria = $this->valid('cargaHoraria', $cargaHoraria);
    }

    /**
     * @return mixed
     */
    public function getComponenteCurricular()
    {
        return $this->componenteCurricular;
    }

    /**
     * @param mixed $componenteCurricular
     */
    public function setComponenteCurricular(ComponenteCurricular $componenteCurricular)
    {
        $this->componenteCurricular = $this->valid('componenteCurricular', $componenteCurricular);
    }

    /**
     * @return Entity
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * @param Serie $serie
     */
    public function setSerie(Serie $serie)
    {
        $this->serie = $this->valid('serie', $serie);
    }

    /**
     * @return Entity
     */
    public function getTurma()
    {
        return $this->turma;
    }

    /**
     * @param Turma $turma
     */
    public function setTurma(Turma $turma)
    {
        $this->turma = $this->valid('turma', $turma);
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

            $inputFilter->add($factory->createInput([
                'name' => 'id',
                'required' => true,
                'filters' => [
                    ['name' => 'Int']
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'cargaHoraria',
                'required' => false,
                'validators' => [
                    [
                        'name' => 'Float',
                        'options' => [
                            'min' => 1,
                            'locale' => 'en_US'
                        ]
                    ]
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'serie',
                'required' => true,
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'componenteCurricular',
                'required' => true
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'turma',
                'required' => true,
            ]));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}