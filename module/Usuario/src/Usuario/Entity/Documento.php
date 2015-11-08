<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/11/15
 * Time: 15:31
 */
namespace Usuario\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade Documento
 *
 * Recebe o documento da pessoa fisica
 *
 * @uthor EduardoJunior <ej@calangodev.com.br>
 * @category Entidade
 * @package Usuario
 * @subpackage Documento
 * @version 0.1
 * @example Classe Documento
 * @copyright Copyright (c) 2015 - CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="cadastro_documento")
 *
 */
class Documento extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $rg;

    /**
     * @ORM\Column(type="date", name="data_exp_rg", nullable=true)
     */
    protected $dataExpedicaoRg;

    /**
     * @ORM\OneToOne(targetEntity="Core\Entity\Uf")
     * @ORM\JoinColumn(name="sigla_uf_exp_rg", onDelete="NO ACTION")
     */
    protected $siglaUfExpedicaoRg;

    /**
     * Atributo que armazena o tipo de certidao civil, existem 3 tipos
     * - nascimento(novo formato), so apresenta um input certidao_nascimento
     * - nascimento(antigo formato), apresenta os inputs termo, livro, folha
     * - casamento - apresenta os inputs termo, livro, folha
     *
     * @ORM\Column(type="decimal", length=2, name="tipo_cert_civil")
     */
    protected $tipoCertidaoCivil;

    /**
     * @ORM\Column(type="decimal", length=8, name="num_termo")
     */
    protected $termo;

    /**
     * @ORM\Column(type="string", length=8, name="num_livro")
     */
    protected $livro;

    /**
     * @ORM\Column(type="decimal", length=4, name="num_folha")
     */
    protected $folha;

    /**
     * @ORM\Column(type="date", name="data_emissao_cert_civil")
     */
    protected $dataEmissaoCertidaoCivil;

    /**
     * @ORM\OneToOne(targetEntity="Core\Entity\Uf")
     * @ORM\JoinColumn(name="sigla_uf_cert_civil", onDelete="NO ACTION")
     */
    protected $siglaUfCertidaoCivil;

    /**
     * @ORM\Column(type="string", length=150, name="cartorio_cert_civil")
     */
    protected $cartorioCertidaoCivil;

    /**
     * @ORM\Column(type="decimal", length=9, name="num_cart_trabalho")
     */
    protected $numeroCarteiraTrabalho;

    /**
     * @ORM\Column(type="decimal", length=5, name="serie_cart_trabalho")
     */
    protected $serieCarteiraTrabalho;

    /**
     * @ORM\Column(type="date", name="data_emissao_cart_trabalho")
     */
    protected $dataEmissaoCarteiraTrabalho;

    /**
     * @ORM\OneToOne(targetEntity="Core\Entity\Uf")
     * @ORM\JoinColumn(name="sigla_uf_cart_trabalho", onDelete="NO ACTION")
     */
    protected $siglaUfCarteiraTrabalho;

    /**
     * @ORM\Column(type="decimal", length=13, name="num_tit_eleitor")
     */
    protected $numeroTituloEleitor;

    /**
     * @ORM\Column(type="decimal", length=4, name="zona_tit_eleitor")
     */
    protected $zonaTituloEleitor;

    /**
     * @ORM\Column(type="decimal", length=4, name="secao_tit_eleitor")
     */
    protected $secaoTituloEleitor;

    /**
     * @todo criar entidade OrgaoEmissorRg
     *
     * @ORM\OneToOne(targetEntity="Usuario\Entity\OrgaoEmissorRg")
     * @ORM\JoinColumn(name="idorg_exp_rg", onDelete="NO ACTION")
     */
    protected $orgaoEmissorRg;

    /**
     * @var  DateTime $data_cad Data de cadastro
     * @ORM\Column(name="data_cad", type="datetime", nullable=false)
     */
    protected $dataCad;

    /**
     * @ORM\Column(type="string", length=50, name="certidao_nascimento")
     */
    protected $certidaoNascimento;

}