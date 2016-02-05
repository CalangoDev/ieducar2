<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/01/16
 * Time: 15:06
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade EscolaSerie
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entity
 * @subpackage EscolaSerie
 * @version 0.1
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_escola_serie")
 */
class EscolaSerie extends Entity
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
     * @var boolean $ativo
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $ativo = true;

    /**
     * @var time $inicioIntervalo
     *
     * @ORM\Column(type="time", nullable=true)
     */
    protected $inicioIntervalo;

    /**
     * @var time $fimIntervalo
     *
     * @ORM\Column(type="time", nullable=true)
     */
    protected $fimIntervalo;

    /**
     * @var boolean $bloquearEnturmacao
     *
     * Bloquea enturmacao sem vagas
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $bloquearEnturmacao = false;

    /**
     * @var boolean $bloquearCadastroTurma
     *
     * Bloquea cadastro de turmas para serie com vagas
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $bloquearCadastroTurma;

    /**
     * @var int $escola
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Escola", cascade={"persist"})
     */
    protected $escola;

    /**
     * @var int $serie
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Serie", cascade={"persist"})
     */
    protected $serie;

    // todo falta relacionamento com componentes curriculares

    /**
     * getters and setters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getEscola()
    {
        return $this->escola;
    }

    public function setEscola(\Escola\Entity\Escola $escola)
    {
        $this->escola = $this->valid('escola', $escola);
    }

    public function getFimIntervalo()
    {
        return $this->fimIntervalo;
    }

    public function setFimIntervalo($fimIntervalo)
    {
        $this->fimIntervalo = $this->valid('fimIntervalo', $fimIntervalo);
    }

    public function getHoraFinal()
    {
        return $this->horaFinal;
    }

    public function setHoraFinal($horaFinal)
    {
        $this->horaFinal = $this->valid('horaFinal', $horaFinal);
    }

    public function getHoraInicial()
    {
        return $this->horaInicial;
    }

    public function setHoraInicial($horaInicial)
    {
        $this->horaInicial = $this->valid('horaInicial', $horaInicial);
    }

    public function getInicioIntervalo()
    {
        return $this->inicioIntervalo;
    }

    public function setInicioIntervalo($inicioIntervalo)
    {
        $this->inicioIntervalo = $this->valid('inicioIntervalo', $inicioIntervalo);
    }

    public function getSerie()
    {
        return $this->serie;
    }

    public function setSerie(\Escola\Entity\Serie $serie)
    {
        $this->serie = $this->valid('serie', $serie);
    }

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
    }

    public function getBloquearCadastroTurma()
    {
        return $this->bloquearCadastroTurma;
    }

    public function setBloquearCadastroTurma($bloquearCadastroTurma)
    {
        $this->bloquearCadastroTurma = $this->valid('bloquearCadastroTurma', $bloquearCadastroTurma);
    }

    public function getBloquearEnturmacao()
    {
        return $this->bloquearEnturmacao;
    }

    public function setBloquearEnturmacao($bloquearEnturmacao)
    {
        $this->bloquearEnturmacao = $this->valid('bloquearEnturmacao', $bloquearEnturmacao);
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
                'name' => 'horaInicial',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'horaFinal',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'inicioIntervalo',
                'required' => false
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'fimIntervalo',
                'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'bloquearEnturmacao',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'bloquearCadastroTurma',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'escola',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'serie',
                'required' => true
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
