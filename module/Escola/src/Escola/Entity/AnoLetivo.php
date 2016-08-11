<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/06/16
 * Time: 16:40
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade Ano Letivo
 * @package Escola\Entity
 * @category Entidade
 * @subpackage Ano Letivo
 * @version 0.1
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_ano_letivo")
 */
class AnoLetivo extends Entity
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
     * @var int $ano
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $ano;

    /**
     * @var int $andamento 0 nao iniciado, 1 iniciado, 2 finalizado
     *
     * @ORM\Column(type="smallint", length=1, nullable=false)
     */
    protected $andamento = 0;

    /**
     * @var bool $ativo
     *
     * @ORM\Column(type="boolean")
     */
    protected $ativo = 1;

    /**
     * TODO: Campo ainda sem utilidade no sistema novo
     * @var int $turmasPorAno
     *
     * @ORM\Column(type="integer")
     */
    protected $turmasPorAno = 1;

    /**
     * @var int $escola
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Escola", cascade={"persist"}, inversedBy="anosLetivos")
     */
    protected $escola;


    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Escola\Entity\AnoLetivoModulo", mappedBy="anoLetivo", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     */
    protected $anoLetivoModulos;

    public function __construct()
    {
        $this->anoLetivoModulos = new ArrayCollection();
    }

    /**
     * @param Collection $anoLetivoModulos
     */
    public function addAnoLetivoModulos(Collection $anoLetivoModulos)
    {
        foreach ($anoLetivoModulos as $anoLetivoModulo){
            $anoLetivoModulo->setAnoLetivo($this);
            $this->anoLetivoModulos->add($anoLetivoModulo);
        }
    }

    public function removeAnoLetivoModulos(Collection $anoLetivoModulos)
    {
        foreach ($anoLetivoModulos as $anoLetivoModulo){
            $anoLetivoModulo->setAnoLetivo(null);
            $this->anoLetivoModulos->removeElement($anoLetivoModulo);
        }
    }

    /**
     * getters and setters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getAndamento()
    {
        return $this->andamento;
    }

    public function setAndamento($andamento)
    {
        $this->andamento = $this->valid('andamento', $andamento);
    }

    public function getAno()
    {
        return $this->ano;
    }

    public function setAno($ano)
    {
        $this->ano = $this->valid('ano', $ano);
    }

    public function getAnoLetivoModulos()
    {
        return $this->anoLetivoModulos;
    }

    public function setAnoLetivoModulos($anoLetivoModulos)
    {
        $this->anoLetivoModulos = $this->valid('anoLetivoModulos', $anoLetivoModulos);
    }

    public function getEscola()
    {
        return $this->escola;
    }

    public function setEscola(\Escola\Entity\Escola $escola)
    {
        $this->escola = $this->valid('escola', $escola);
    }

    public function getTurmasPorAno()
    {
        return $this->turmasPorAno;
    }

    public function setTurmasPorAno($turmasPorAno)
    {
        $this->turmasPorAno = $this->valid('turmasPorAno', $turmasPorAno);
    }

    public function isAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
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
                'name' => 'ano',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'andamento',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int')
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
                'name' => 'turmasPorAno',
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
                'name' => 'anoLetivoModulos',
                'required' => true,
                'validators' => array(
                    array(
                        //'name' => new \Escola\Validator\AnoLetivoModulo(),
                        'name' => 'Escola\Validator\AnoLetivoModulo',
//                        'options' => array(
//                            'messages' => array(
//                                //'dataInicioEmpty' => 'teste'
//                            )
//                        )
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}