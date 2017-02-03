<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 22/09/16
 * Time: 17:46
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Class EscolaSerieDisciplina
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entity
 * @package Escola\Entity
 * @subpackage EscolaSerieDisciplina
 * @version 0.1
 * @example Class EscolaSerieDisciplina
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_escola_serie_disciplina")
 */
class EscolaSerieDisciplina extends Entity
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
     * @var float $cargaHoraria
     *
     * @ORM\Column(type="float")
     */
    protected $cargaHoraria;

    /**
     * @var Entity $escolaSerie
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\EscolaSerie", cascade={"persist"}, inversedBy="disciplinas")
     */
    protected $escolaSerie;

    /**
     * @var Entity $componenteCurricular
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\ComponenteCurricular", cascade={"persist"})
     */
    protected $componenteCurricular;

    /**
     * getters and setters
     */

    /**
     * @return int
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
     * @return Entity
     */
    public function getComponenteCurricular()
    {
        return $this->componenteCurricular;
    }

    /**
     * @param ComponenteCurricular $componenteCurricular
     */
    public function setComponenteCurricular(ComponenteCurricular $componenteCurricular)
    {
        $this->componenteCurricular = $this->valid('componenteCurricular', $componenteCurricular);
    }

    /**
     * @return Entity
     */
    public function getEscolaSerie()
    {
        return $this->escolaSerie;
    }

    /**
     * @param EscolaSerie $escolaSerie
     */
    public function setEscolaSerie(EscolaSerie $escolaSerie)
    {
        $this->escolaSerie = $this->valid('escolaSerie', $escolaSerie);
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
        if (!$this->inputFilter) {
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
                'name' => 'escolaSerie',
                'required' => true,
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'componenteCurricular',
                'required' => true
            ]));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;

    }

}