<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 01/04/16
 * Time: 20:21
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade ComponenteCurricularAnoEscolar (Serie)
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @package Escola
 * @subpackage ComponenteCurricularAnoEscolar
 * @version 0.1
 * @example Class ComponenteCurricularAnoEscolar
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_componente_curricular_ano_escolar")
 */
class ComponenteCurricularAnoEscolar extends Entity
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
     * @var String $cargaHoraria
     *
     * @todo revisar esse atributo, tem duplicidade dele, exemplo: no cadastro serie ja tem esse campo, e na
     * configuracao do componente novamente. na tela de escola serie aparece novamente para herdar do componente ou
     * setar uma customizada. O que torna o campo desnecessario na Entidade Serie
     *
     * @ORM\Column(type="float")
     */
    protected $cargaHoraria;

    /**
     * @var int $serie (ano escolar)
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Serie", cascade={"persist"})
     */
    protected $serie;

    /**
     * @var int $serie (ano escolar)
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\ComponenteCurricular", cascade={"persist"})
     */
    protected $componenteCurricular;
    

    /**
     * getters and setters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getCargaHoraria()
    {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria($cargaHoraria)
    {
        $this->cargaHoraria = $this->valid('cargaHoraria', $cargaHoraria);
    }

    public function getComponenteCurricular()
    {
        return $this->componenteCurricular;
    }

    public function setComponenteCurricular(\Escola\Entity\ComponenteCurricular $componenteCurricular)
    {
        $this->componenteCurricular = $this->valid('componenteCurricular', $componenteCurricular);
    }

    public function getSerie()
    {
        return $this->serie;
    }

    public function setSerie(\Escola\Entity\Serie $serie)
    {
        $this->serie = $this->valid('serie', $serie);
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
                'name' => 'cargaHoraria',
                'required' => false,
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
                'name' => 'serie',
                'required' => true,
//                'validators' => [
//                    [
//                        'name' => 'not_empty'
//                    ]
//                ]
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'componenteCurricular',
                'required' => true
            )));
            
            $this->inputFilter = $inputFilter;

        }

        return $this->inputFilter;
    }
}