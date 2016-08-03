<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/01/16
 * Time: 19:49
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Entidade Escola
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage Escola
 * @version 0.1
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_escola")
 */
class Escola extends Entity
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
     * @var string $sigla
     *
     * @ORM\Column(type="string", length=30)
     */
    protected $sigla;

    /**
     * @var int $juridica
     *
     * @ORM\OneToOne(targetEntity="Usuario\Entity\Juridica", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="idpes")
     */
    protected $juridica;

    /**
     * @var int $redeEnsino
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\RedeEnsino", cascade={"persist"})
     */
    protected $redeEnsino;

    /**
     * @var int $localizacao
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Localizacao", cascade={"persist"})
     */
    protected $localizacao;

    /**
     * @var boolean $ativo
     *
     * @ORM\Column(type="boolean")
     */
    protected $ativo = true;

    /**
     * @var boolean $bloquearLancamento
     *
     * Bloquea os lancamentos diarios em anos letivos encerrados
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $bloquearLancamento = false;

    /**
     * @var string $codigoInep
     *
     * @ORM\Column(type="string", length=8)
     */
    protected $codigoInep;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     *
     * @ORM\ManyToMany(targetEntity="Escola\Entity\Curso", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="escola_escola_curso",
     *     joinColumns={
     *      @ORM\JoinColumn(name="escola_id", referencedColumnName="id")
     * },
     *     inverseJoinColumns={
     *      @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
     * }
     *     )
     */
    protected $cursos;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Usuario\Entity\Telefone", mappedBy="escola", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     */
    protected $telefones;


    /**
     * @var Entity $anosLetivos
     * @ORM\OneToMany(targetEntity="Escola\Entity\AnoLetivo", mappedBy="escola", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     */
    protected $anosLetivos;


    /**
     * default constructor, initializes collections
     */
    public function __construct()
    {
        $this->cursos = new ArrayCollection();
        $this->telefones = new ArrayCollection();
        $this->anosLetivos = new ArrayCollection();
    }

    /**
     * @param Collection $cursos
     */
    public function addCursos(Collection $cursos)
    {
        foreach ($cursos as $curso) {
            $this->cursos->add($curso);
        }
    }

    /**
     * @param Collection $cursos
     */
    public function removeCursos(Collection $cursos)
    {
        foreach ($cursos as $curso){
            $this->cursos->removeElement($curso);
        }
    }

    /**
     * getters and setters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getLocalizacao()
    {
        return $this->localizacao;
    }

    public function setLocalizacao(\Escola\Entity\Localizacao $localizacao)
    {
        $this->localizacao = $this->valid('localizacao', $localizacao);
    }

    public function getJuridica()
    {
        return $this->juridica;
    }

    public function setJuridica(\Usuario\Entity\Juridica $juridica)
    {
        $this->juridica = $this->valid('pessoa', $juridica);
    }

    public function getRedeEnsino()
    {
        return $this->redeEnsino;
    }

    public function setRedeEnsino(\Escola\Entity\RedeEnsino $redeEnsino)
    {
        $this->redeEnsino = $redeEnsino;
    }

    public function getSigla()
    {
        return $this->sigla;
    }

    public function setSigla($sigla)
    {
        $this->sigla = $this->valid('sigla', $sigla);
    }

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
    }

    public function getBloquearLancamento()
    {
        return $this->bloquearLancamento;
    }

    public function setBloquearLancamento($bloquearLancamento)
    {
        $this->bloquearLancamento = $this->valid('bloquearLancamento', $bloquearLancamento);
    }

    public function getCursos()
    {
        return $this->cursos;
    }

    public function getCodigoInep()
    {
        return $this->codigoInep;
    }

    public function setCodigoInep($codigoInep)
    {
        $this->codigoInep = $this->valid('codigoInep', $codigoInep);
    }

    public function addTelefones(Collection $telefones)
    {
        foreach ($telefones as $telefone){
            $telefone->setEscola($this);
            $this->telefones->add($telefone);
        }
    }

    public function removeTelefones(Collection $telefones)
    {
        foreach ($telefones as $telefone){
            $telefone->setEscola(null);
            $this->telefones->removeElement($telefone);
        }
    }

    public function getTelefones()
    {
        return $this->telefones;
    }

    /**
     * @return Entity
     */
    public function getAnosLetivos()
    {
        return $this->anosLetivos;
    }

    /**
     * @param Collection $anosLetivos
     */
    public function addAnosLetivos(Collection $anosLetivos)
    {
        foreach ($anosLetivos as $anoLetivo){
            if ($anoLetivo->getAno() != ""){
                $anoLetivo->setEscola($this);
                $this->anosLetivos->add($anoLetivo);
            }
        }
    }

    public function removeAnosLetivos(Collection $anosLetivos)
    {
        foreach ($anosLetivos as $anosLetivo){
            if ($anosLetivo->getAno() != ""){
                $anosLetivo->setEscola(null);
                $this->anosLetivos->removeElement($anosLetivo);
            }
        }
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
                'name' => 'sigla',
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
                            'max' => 30,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'ativo',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'juridica',
                'required' => true,
                'continue_if_empty' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'redeEnsino',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'localizacao',
                'required' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'cursos',
                'required' => false
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'bloquearLancamento',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'codigoInep',
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
                            'max' => 8,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'telefones',
                'required' => false
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'anosLetivos',
                'required' => false
            )));

            $this->inputFilter = $inputFilter;

        }
        return $this->inputFilter;
    }

}