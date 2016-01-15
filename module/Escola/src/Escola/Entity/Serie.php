<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/01/16
 * Time: 09:17
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade Serie
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage Serie
 * @version 0.1
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_serie")
 */
class Serie extends Entity
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
     * @ORM\Column(type="string", length=255)
     */
    protected $nome;

    /**
     * @var int $etapaCurso
     *
     * @ORM\Column(type="integer", length=10)
     */
    protected $etapaCurso;

    /**
     * @var boolean $concluinte
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $concluinte = false;

    /**
     * @var float $cargaHoraria
     *
     * @ORM\Column(type="float")
     */
    protected $cargaHoraria;

    /**
     * @var boolean $ativo
     *
     * @ORM\Column(type="boolean")
     */
    protected $ativo = true;

    /**
     * @var int $intervalo
     *
     * @ORM\Column(type="integer", length=10)
     */
    protected $intervalo;

    /**
     * @var decimal $idadeInicial
     *
     * @ORM\Column(type="decimal", precision=3, scale=0, nullable=true)
     */
    protected $idadeInicial;

    /**
     * @var decimal $idadeFinal
     *
     * @ORM\Column(type="decimal", precision=3, scale=0, nullable=true)
     */
    protected $idadeFinal;

    /**
     * @var text $observacaoHistorico
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $observacaoHistorico;

    /**
     * @var int $diasLetivos
     *
     * @ORM\Column(type="integer", length=10, nullable=true)
     */
    protected $diasLetivos;

    /**
     * @var int $curso
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Curso", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $curso;

    /**
     * @var int regraAvaliacao
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\RegraAvaliacao", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $regraAvaliacao;

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

    public function getCargaHoraria()
    {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria($cargaHoraria)
    {
        $this->cargaHoraria = $this->valid('cargaHoraria', $cargaHoraria);
    }

    public function getDiasLetivos()
    {
        return $this->diasLetivos;
    }

    public function setDiasLetivos($diasLetivos)
    {
        $this->diasLetivos = $this->valid('diasLetivos', $diasLetivos);
    }

    public function getCurso()
    {
        return $this->curso;
    }

    public function setCurso(\Escola\Entity\Curso $curso)
    {
        $this->curso = $this->valid('curso', $curso);
    }

    public function getEtapaCurso()
    {
        return $this->etapaCurso;
    }

    public function setEtapaCurso($etapaCurso)
    {
        $this->etapaCurso = $this->valid('etapaCurso', $etapaCurso);
    }

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    public function getIdadeFinal()
    {
        return $this->idadeFinal;
    }

    public function setIdadeFinal($idadeFinal)
    {
        $this->idadeFinal = $this->valid('idadeFinal', $idadeFinal);
    }

    public function getIdadeInicial()
    {
        return $this->idadeInicial;
    }

    public function setIdadeInicial($idadeInicial)
    {
        $this->idadeInicial = $this->valid('idadeInicial', $idadeInicial);
    }

    public function getIntervalo()
    {
        return $this->intervalo;
    }

    public function setIntervalo($intervalo)
    {
        $this->intervalo = $this->valid('intervalo', $intervalo);
    }

    public function getObservacaoHistorico()
    {
        return $this->observacaoHistorico;
    }

    public function setObservacaoHistorico($observacaoHistorico)
    {
        $this->observacaoHistorico = $this->valid('observacaoHistorico', $observacaoHistorico);
    }

    public function getRegraAvaliacao()
    {
        return $this->regraAvaliacao;
    }

    public function setRegraAvaliacao(\Escola\Entity\RegraAvaliacao $regraAvaliacao = null)
    {
        $this->regraAvaliacao = $this->valid('regraAvaliacao', $regraAvaliacao);
    }

    public function getConcluinte()
    {
        return $this->concluinte;
    }

    public function setConcluinte($concluinte)
    {
        $this->concluinte = $this->valid('concluinte', $concluinte);
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
                'name' => 'etapaCurso',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'concluinte',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'cargaHoraria',
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
                'name' => 'intervalo',
                'required' =>  true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'idadeInicial',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Digits'
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'idadeFinal',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Digits'
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'observacaoHistorico',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'diasLetivos',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'curso',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'regraAvaliacao',
                'required' => false,
                'filters'=> array(
                    array('name' => 'Int')
                )
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }




}