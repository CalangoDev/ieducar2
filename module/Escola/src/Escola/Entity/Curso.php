<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 19/12/15
 * Time: 16:18
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade Curso
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage Curso
 * @version 0.1
 * @example Class Curso
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_curso")
 * @ORM\HasLifecycleCallbacks
 */
class Curso extends Entity
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
     * @var String $nome Nome do Curso
     *
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    protected $nome;

    /**
     * @var String $sigla Sigla do curso
     *
     * @ORM\Column(type="string", length=20, nullable=false)
     */
    protected $sigla;

    /**
     * @var int $quantidadeEtapa Quantidade de etapas do curso
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $quantidadeEtapa;

    /**
     * @var double $cargaHoraria Carga Horaria do Curso
     *
     * @ORM\Column(type="float", nullable=false)
     */
    protected $cargaHoraria;

    /**
     * @var string $atoPoderPublico
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $atoPoderPublico;

    /**
     * @var text $objetivo Objetivo do Curso
     *
     * @ORM\Column(type="text")
     */
    protected $objetivo;

    /**
     * @var text $publicoAlvo
     *
     * @ORM\Column(type="text")
     */
    protected $publicoAlvo;

    /**
     * @var DateTime $dataCadastro Data de cadastro
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $dataCadastro;

    /**
     * @var boolean $ativo
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $ativo = true;

    /**
     * @var boolean $padraoAnoEscolar
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $padraoAnoEscolar = false;

    /**
     * @var double $horaFalta
     *
     * @ORM\Column(type="float", nullable=false)
     */
    protected $horaFalta = 0.00;

    /**
     * @var boolean $multiSeriado
     *
     * @ORM\Column(type="boolean")
     */
    protected $multiSeriado = false;

    /**
     * @var int $instituicao
     * 
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Instituicao", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $instituicao;

    /**
     * @var int $nivelEnsino
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\NivelEnsino", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $nivelEnsino;

    /**
     * @var int $tipoEnsino
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\TipoEnsino", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $tipoEnsino;

    /**
     * @var int $tipoRegime
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\TipoRegime", cascade={"persist"})
     */
    protected $tipoRegime;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     *
     * @ORM\ManyToMany(targetEntity="Escola\Entity\Habilitacao", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="escola_curso_habilitacao",
     *     joinColumns={
     *      @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
     * },
     *     inverseJoinColumns={
     *      @ORM\JoinColumn(name="habilitacao_id", referencedColumnName="id")
     * }
     *     )
     */
    protected $habilitacoes;

    /**
     * default constructor, initializes collections
     */
    public function __construct()
    {
        $this->habilitacoes = new ArrayCollection();
    }

    /**
     * @param Collection $habilitacoes
     */
    public function addHabilitacoes(Collection $habilitacoes)
    {
        foreach ($habilitacoes as $habilitacao) {
            $this->habilitacoes->add($habilitacao);
        }
    }

    /**
     * @param Collection $habilitacoes
     */
    public function removeHabilitacoes(Collection $habilitacoes)
    {
        foreach ($habilitacoes as $habilitacao){
            $this->habilitacoes->removeElement($habilitacao);
        }
    }

    /**
     * Função para gerar o timestamp para o atributo dataCadastro, é executada antes de salvar os dados no banco
     * @access  public
     * @return  void
     * @ORM\PrePersist
     */
    public function timestamp()
    {
        if (is_null($this->getDataCadastro())) $this->setDataCadastro(new \DateTime());
    }

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

    public function getAtoPoderPublico()
    {
        return $this->atoPoderPublico;
    }

    public function setAtoPoderPublico($atoPoderPublico)
    {
        $this->atoPoderPublico = $this->valid('atoPoderPublico', $atoPoderPublico);
    }

    public function getCargaHoraria()
    {
        return $this->cargaHoraria;
    }

    public function setCargaHoraria($cargaHoraria)
    {
        $this->cargaHoraria = $this->valid('cargaHoraria', $cargaHoraria);
    }

    public function getDataCadastro()
    {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro)
    {
        $this->dataCadastro = $this->valid('dataCadastro', $dataCadastro);
    }

    public function getHoraFalta()
    {
        return $this->horaFalta;
    }

    public function setHoraFalta($horaFalta)
    {
        $this->horaFalta = $this->valid('horaFalta', $horaFalta);
    }

    public function getInstituicao()
    {
        return $this->instituicao;
    }

    public function setInstituicao(\Escola\Entity\Instituicao $instituicao)
    {
        $this->instituicao = $this->valid('instituicao', $instituicao);
    }

    public function getNivelEnsino()
    {
        return $this->nivelEnsino;
    }

    public function setNivelEnsino(\Escola\Entity\NivelEnsino $nivelEnsino)
    {
        $this->nivelEnsino = $this->valid('nivelEnsino', $nivelEnsino);
    }

    public function getObjetivo()
    {
        return $this->objetivo;
    }

    public function setObjetivo($objetivo)
    {
        $this->objetivo = $this->valid('objetivo', $objetivo);
    }

    public function getPublicoAlvo()
    {
        return $this->publicoAlvo;
    }

    public function setPublicoAlvo($publicoAlvo)
    {
        $this->publicoAlvo = $this->valid('publicoAlvo', $publicoAlvo);
    }

    public function getQuantidadeEtapa()
    {
        return $this->quantidadeEtapa;
    }

    public function setQuantidadeEtapa($quantidadeEtapa)
    {
        $this->quantidadeEtapa = $this->valid('quantidadeEtapa', $quantidadeEtapa);
    }

    public function getSigla()
    {
        return $this->sigla;
    }

    public function setSigla($sigla)
    {
        $this->sigla = $this->valid('sigla', $sigla);
    }

    public function getTipoEnsino()
    {
        return $this->tipoEnsino;
    }

    public function setTipoEnsino(\Escola\Entity\TipoEnsino $tipoEnsino)
    {
        $this->tipoEnsino = $tipoEnsino;
    }

    public function getTipoRegime()
    {
        return $this->tipoRegime;
    }

    public function setTipoRegime(\Escola\Entity\TipoRegime $tipoRegime = null)
    {
        $this->tipoRegime = $tipoRegime;
    }

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
    }

    public function getMultiSeriado()
    {
        return $this->multiSeriado;
    }

    public function setMultiSeriado($multiSeriado)
    {
        $this->multiSeriado = $this->valid('multiSeriado', $multiSeriado);
    }

    public function getPadraoAnoEscolar()
    {
        return $this->padraoAnoEscolar;
    }

    public function setPadraoAnoEscolar($padraoAnoEscolar)
    {
        $this->padraoAnoEscolar = $this->valid('padraoAnoEscolar', $padraoAnoEscolar);
    }

    public function getHabilitacoes()
    {
        return $this->habilitacoes;
    }

    public function setHabilitacoes($habilitacoes)
    {
        $this->habilitacoes = $habilitacoes;
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
                    array('name' => 'Int'),
                ),
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
                            'max' => 200,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'sigla',
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
                            'max' => 20,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'quantidadeEtapa',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'cargaHoraria',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Float',
                        'options' =>  array(
                            'locale' => 'pt_BR'
                        )
                    )
                )
			)));


            $inputFilter->add($factory->createInput(array(
                'name' => 'atoPoderPublico',
                'required' => false,
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
				'name' => 'objetivo',
				'required' => false,
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'publicoAlvo',
				'required' => false,
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ativo',
				'required' => true,
				'filters' => array(
					array('name' => 'Int')
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'padraoAnoEscolar',
				'required' => true,
				'filters' => array(
					array('name' => 'Int')
				),
			)));

			$inputFilter->add($factory->createInput(array(
                'name' => 'horaFalta',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Float',
                        'options' =>  array(
                            'locale' => 'pt_BR'
                        )
                    )
                )
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'multiSeriado',
				'required' => false,
				'validators' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'instituicao',
				'required' => true,
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'nivelEnsino',
				'required' => true,
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tipoEnsino',
				'required' => true,
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tipoRegime',
				'required' => false,
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'habilitacoes',
				'required' => false,
			)));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
