<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 19/12/15
 * Time: 16:18
 */
namespace Escola\Entity;

use Core\Entity\Entity;
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
 *
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
     * @ORM\Column(type="double", nullable=false)
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
     * @ORM\Column(type="double", nullable=false)
     */
    protected $horaFalta;

    /**
     * @var boolean $multiSeriado
     *
     * @ORM\Column(type="boolean")
     */
    protected $multiSeriado = false;

    // TODO: falta as referencias

}