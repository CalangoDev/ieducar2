<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 21/05/16
 * Time: 16:35
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Doctrine\ORM\Mapping as ORM;


/**
 * Entidade Turma
 * @package Escola\Entity
 * @category Entidade
 * @subpackage Turma
 * @version 0.1
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_turma")
 */
class Turma extends Entity
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
     * @var int $maximoAluno
     *
     * @ORM\Column(type="integer", length=10)
     */
    protected $maximoAluno;

    /**
     * @var int $multiSeriada
     *
     * @ORM\Column(type="boolean")
     */
    protected $multiSeriada = false;

    /**
     * @var bool $ativo
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $ativo = true;

    /**
     * @var time $horaInicial
     *
     * @ORM\Column(type="time", nullable=true)
     */
    protected $horaInicial;

    /**
     * @var time $horaFinal
     *
     * @ORM\Column(type="time", nullable=true)
     */
    protected $horaFinal;

    /**
     * @var time $horaInicioIntervalo
     *
     * @ORM\Column(type="time", nullable=true)
     */
    protected $horaInicioIntervalo;

    /**
     * @var time $horaFimIntervalo
     *
     * @ORM\Column(type="time", nullable=true)
     */
    protected $horaFimIntervalo;

    /**
     * @var bool $visivel
     *
     * @ORM\Column(type="boolean")
     */
    protected $visivel;

    /**
     * @var int $tipoBoletim
     * Helper TipoBoletim
     *
     * @ORM\Column(type="integer", length=10)
     */
    protected $tipoBoletim;

    /**
     * @var Entity $anoLetivo
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\AnoLetivo", cascade={"persist"})
     */
    protected $anoLetivo;

    /**
     * @var Date $dataFechamento
     *
     * @ORM\Column(type="date")
     */
    protected $dataFechamento;

    /**
     * @var Entity $comodoPredio
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\ComodoPredio", cascade={"persist"})
     */
    protected $comodoPredio;

    /**
     * @var Entity $turmaTurno
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\TurmaTurno", cascade={"persist"})
     */
    protected $turmaTurno;
    
    /**
     * @var Entity $escolaSerie
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\EscolaSerie", cascade={"persist"})
     */
    protected $escolaSerie;

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
     * @return boolean
     */
    public function isAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param boolean $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
    }

    /**
     * @return Entity
     */
    public function getAnoLetivo()
    {
        return $this->anoLetivo;
    }

    /**
     * @param AnoLetivo $anoLetivo
     */
    public function setAnoLetivo(AnoLetivo $anoLetivo)
    {
        $this->anoLetivo = $this->valid('anoLetivo', $anoLetivo);
    }

    /**
     * @return Entity
     */
    public function getComodoPredio()
    {
        return $this->comodoPredio;
    }

    /**
     * @param ComodoPredio $comodoPredio
     */
    public function setComodoPredio(ComodoPredio $comodoPredio)
    {
        $this->comodoPredio = $this->valid('comodoPredio', $comodoPredio);
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
     * @return Date
     */
    public function getDataFechamento()
    {
        return $this->dataFechamento;
    }

    /**
     * @param Date $dataFechamento
     */
    public function setDataFechamento($dataFechamento)
    {
        $this->dataFechamento = $this->valid('dataFechamento', $dataFechamento);
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
     * @return time
     */
    public function getHoraFimIntervalo()
    {
        return $this->horaFimIntervalo;
    }

    /**
     * @param time $horaFimIntervalo
     */
    public function setHoraFimIntervalo($horaFimIntervalo)
    {
        $this->horaFimIntervalo = $this->valid('horaFimIntervalo', $horaFimIntervalo);
    }

    /**
     * @return time
     */
    public function getHoraFinal()
    {
        return $this->horaFinal;
    }

    /**
     * @param time $horaFinal
     */
    public function setHoraFinal($horaFinal)
    {
        $this->horaFinal = $this->valid('horaFinal', $horaFinal);
    }

    /**
     * @return time
     */
    public function getHoraInicial()
    {
        return $this->horaInicial;
    }

    /**
     * @param time $horaInicial
     */
    public function setHoraInicial($horaInicial)
    {
        $this->horaInicial = $this->valid('horaInicial', $horaInicial);
    }

    /**
     * @return time
     */
    public function getHoraInicioIntervalo()
    {
        return $this->horaInicioIntervalo;
    }

    /**
     * @param time $horaInicioIntervalo
     */
    public function setHoraInicioIntervalo($horaInicioIntervalo)
    {
        $this->horaInicioIntervalo = $this->valid('horaInicioIntervalo', $horaInicioIntervalo);
    }

    /**
     * @return int
     */
    public function getMaximoAluno()
    {
        return $this->maximoAluno;
    }

    /**
     * @param int $maximoAluno
     */
    public function setMaximoAluno($maximoAluno)
    {
        $this->maximoAluno = $this->valid('maximoAluno', $maximoAluno);
    }

    /**
     * @return int
     */
    public function getMultiSeriada()
    {
        return $this->multiSeriada;
    }

    /**
     * @param int $multiSeriada
     */
    public function setMultiSeriada($multiSeriada)
    {
        $this->multiSeriada = $this->valid('multiSeriada', $multiSeriada);
    }

    /**
     * @return int
     */
    public function getTipoBoletim()
    {
        return $this->tipoBoletim;
    }

    /**
     * @param int $tipoBoletim
     */
    public function setTipoBoletim($tipoBoletim)
    {
        $this->tipoBoletim = $this->valid('tipoBoletim', $tipoBoletim);
    }

    /**
     * @return int
     */
    public function getTurmaTurno()
    {
        return $this->turmaTurno;
    }

    /**
     * @param TurmaTurno $turmaTurno
     */
    public function setTurmaTurno(TurmaTurno $turmaTurno)
    {
        $this->turmaTurno = $this->valid('turmaTurno', $turmaTurno);
    }

    /**
     * @return boolean
     */
    public function isVisivel()
    {
        return $this->visivel;
    }

    /**
     * @param boolean $visivel
     */
    public function setVisivel($visivel)
    {
        $this->visivel = $this->valid('visivel', $visivel);
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
                'name' => 'nome',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 255,
                        ]
                    ]
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'sigla',
                'required' => false,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 15
                        ]
                    ]
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'maximoAluno',
                'required' => false,
                'filters' => [
                    ['name' => 'Int']
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'multiSeriada',
                'required' => false,
                'filters' => [
                    ['name' => 'Int']
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'ativo',
                'required' => false,
                'filters' => [
                    ['name' => 'Int']
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'horaInicial',
                'required' => false
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'horaFinal',
                'required' => false
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'horaInicioIntervalo',
                'required' => false
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'horaFimIntervalo',
                'required' => false
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'visivel',
                'required' => false,
                'filters' => [
                    ['name' => 'Int']
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'tipoBoletim',
                'required' => false,
                'filters' => [
                    ['name' => 'Int']
                ]
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'anoLetivo',
                'required' => false
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'dataFechamento',
                'required' => false
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'comodoPredio',
                'required' => false
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'turmaTurno',
                'required' => false
            ]));

            $inputFilter->add($factory->createInput([
                'name' => 'escolaSerie',
                'required' => false
            ]));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}