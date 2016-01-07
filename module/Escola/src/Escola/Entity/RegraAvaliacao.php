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
use Zend\InputFIlter\InputFilter;
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
     * @var int $instituicao
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Instituicao", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $instituicao;

    /**
     * @var int $formulaMedia
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\FormulaMedia", cascade={"persist"})
     */
    protected $formulaMedia;


    /**
     *
    formula_recuperacao_id   | integer               | default 0                                                    |
    tabela_arredondamento_id | integer               |                                                              |
     */
}