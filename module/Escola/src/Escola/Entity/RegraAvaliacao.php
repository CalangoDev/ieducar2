<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/01/16
 * Time: 07:40
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;


/**
 * Entidade RegraAvaliacao
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage RegraAvaliacao
 * @version 0.2
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_regra_avaliacao")
 */
class RegraAvaliacao extends Entity
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
     * @var String $nome
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $nome;

    /**
     * @var SmallInt $tipoNota
     *
     * @ORM\Column(type="smallint", length=1, nullable=false)
     */
    protected $tipoNota;

    /**
     * @var Smallint $tipoProgressao
     *
     * @ORM\Column(type="smallint", length=1, nullable=false)
     */
    protected $tipoProgressao;

    /**
     * @var decimal $media
     *
     * @ORM\Column(type="decimal", precision=5, scale=3)
     */
    protected $media = 0.000;

    /**
     * @var decimal $porcentagem_presenca
     *
     * @ORM\Column(type="decimal", precision=6, scale=3)
     */
    protected $porcentagemPresenca = 0.000;

    /**
     * @var smallint $parecerDescritivo
     *
     * @ORM\Column(type="smallint", length=1)
     */
    protected $parecerDescritivo = 0;

    /**
     * @var smallint $tipoPresenca
     *
     * @ORM\Column(type="smallint", length=1, nullable=false)
     */
    protected $tipoPresenca;

    /**
     * @var decimal $mediaRecuperacao
     *
     * @ORM\Column(type="decimal", precision=5, scale=3)
     */
    protected $mediaRecuperacao = 0.000;


    /**
     * @var int $formulaMedia
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\FormulaMedia", cascade={"persist"})
     */
    protected $formulaMedia;

    /**
     * @var int $formulaRecuperacao
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\FormulaMedia", cascade={"persist"})
     */
    protected $formulaRecuperacao;

    /**
     * @var int $tabelaArredondamento
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\TabelaArredondamento", cascade={"persist"})
     */
    protected $tabelaArredondamento;

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

    public function getTipoNota()
    {
        return $this->tipoNota;
    }

    public function setTipoNota($tipoNota)
    {
        $this->tipoNota = $this->valid('tipoNota', $tipoNota);
    }

    public function getFormulaMedia()
    {
        return $this->formulaMedia;
    }

    public function setFormulaMedia(\Escola\Entity\FormulaMedia $formulaMedia = null)
    {
        $this->formulaMedia = $this->valid('formulaMedia', $formulaMedia);
    }

    public function getFormulaRecuperacao()
    {
        return $this->formulaRecuperacao;
    }

    public function setFormulaRecuperacao(\Escola\Entity\FormulaMedia $formulaRecuperacao = null)
    {
        $this->formulaRecuperacao = $this->valid('formulaRecuperacao', $formulaRecuperacao);
    }

    public function getMedia()
    {
        return $this->media;
    }

    public function setMedia($media)
    {
        $this->media = $this->valid('media', $media);
    }

    public function getMediaRecuperacao()
    {
        return $this->mediaRecuperacao;
    }

    public function setMediaRecuperacao($mediaRecuperacao)
    {
        $this->mediaRecuperacao = $this->valid('mediaRecuperacao', $mediaRecuperacao);
    }

    public function getTabelaArredondamento()
    {
        return $this->tabelaArredondamento;
    }

    public function setTabelaArredondamento(\Escola\Entity\TabelaArredondamento $tabelaArredondamento = null)
    {
        $this->tabelaArredondamento = $this->valid('tabelaArredondamento', $tabelaArredondamento);
    }

    public function getParecerDescritivo()
    {
        return $this->parecerDescritivo;
    }

    public function setParecerDescritivo($parecerDescritivo)
    {
        $this->parecerDescritivo = $this->valid('parecerDescritivo', $parecerDescritivo);
    }

    public function getPorcentagemPresenca()
    {
        return $this->porcentagemPresenca;
    }

    public function setPorcentagemPresenca($porcentagemPresenca)
    {
        $this->porcentagemPresenca = $this->valid('porcentagemPresenca', $porcentagemPresenca);
    }

    public function getTipoPresenca()
    {
        return $this->tipoPresenca;
    }

    public function setTipoPresenca($tipoPresenca)
    {
        $this->tipoPresenca = $this->valid('tipoPresenca', $tipoPresenca);
    }

    public function getTipoProgressao()
    {
        return $this->tipoProgressao;
    }

    public function setTipoProgressao($tipoProgressao)
    {
        $this->tipoProgressao = $this->valid('tipoProgressao', $tipoProgressao);
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
                            'max' => 50,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'tipoNota',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'tipoProgressao',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'media',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Float',
                        'options' => array(
                            'min' => 0,
                            'locale' => 'en_US'
                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'porcentagemPresenca',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Float',
                        'options' => array(
                            'min' => 0,
                            'locale' => 'en_US'
                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'parecerDescritivo',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'tipoPresenca',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'mediaRecuperacao',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Float',
                        'options' => array(
                            'min' => 0,
                            'locale' => 'en_US'
                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'formulaMedia',
                'required' => false
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'formulaRecuperacao',
                'required' => false
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'tabelaArredondamento',
                'required' => false
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}