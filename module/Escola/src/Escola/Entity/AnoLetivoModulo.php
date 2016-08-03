<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/06/16
 * Time: 17:52
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade Ano Letivo Modulo
 * @package Escola\Entity
 * @category Entidade
 * @subpackage Ano Letivo Modulo
 * @version 0.1
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_ano_letivo_modulo")
 * @ORM\HasLifecycleCallbacks
 */
class AnoLetivoModulo extends Entity
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
     * @var int $anoLetivo
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\AnoLetivo", cascade={"persist"}, inversedBy="anoLetivoModulos")
     */
    protected $anoLetivo;

    /**
     * @var int $sequencial
     *
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $sequencial;

    /**
     * @var DateTime $dataInicio
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $dataInicio;

    /**
     * @var DateTime $dataFim
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $dataFim;

    /**
     * @var int $modulo
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Modulo", cascade={"persist"})
     */
    protected $modulo;

    /**
     * getters and setters
     */

    public function getId()
    {
        return $this->id;
    }

    public function getAnoLetivo()
    {
        return $this->anoLetivo;
    }

    public function setAnoLetivo(\Escola\Entity\AnoLetivo $anoLetivo)
    {
        $this->anoLetivo = $this->valid('anoLetivo', $anoLetivo);
    }

    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $this->valid('dataInicio', $dataInicio);
    }

    public function getDataFim()
    {
        return $this->dataFim;
    }

    public function setDataFim($dataFim)
    {
        $this->dataFim = $this->valid('dataFim', $dataFim);
    }

    public function getModulo()
    {
        return $this->modulo;
    }

    public function setModulo(\Escola\Entity\Modulo $modulo)
    {
        $this->modulo = $this->valid('modulo', $modulo);
    }

    public function getSequencial()
    {
        return $this->sequencial;
    }

    public function setSequencial($sequencial)
    {
        $this->sequencial = $this->valid('sequencial', $sequencial);
    }

    /**
     * Funcao para gerar a sequencia do campo sequencial
     * @ORM\PrePersist
     */
    public function generatedSequencial()
    {
        if (is_null($this->sequencial)){
            $this->sequencial = 1;
        }
        $this->sequencial = $this->sequencial++;
    }

    /**
     * @var Zend\InputFilter\InputFilter $inputFilter
     */
    protected $inputFilter;

    /**
     * Configura os filtros
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
                'name' => 'anoLetivo',
                'required' => true
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'dataInicio',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'dataFim',
                'required' => true
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'modulo',
                'required' => true
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'sequencial',
                'required' => true
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}