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
 * @author EduardoJunior <ej@calangodev.com.br>
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

    public function getId()
    {
        return $this->id;
    }

    public function getRg()
    {
        return $this->getRg;
    }

    public function setRg($rg)
    {
        $this->rg = $this->valid("rg", $rg);
    }

    public function getDataExpedicaoRg()
    {
        return $this->dataExpedicaoRg;
    }

    public function setDataExpedicaoRg($dataExpedicaoRg)
    {
        $this->dataExpedicaoRg = $this->valid("dataExpedicaoRg", $dataExpedicaoRg);
    }

    public function getSiglaUfExpedicaoRg()
    {
        return $this->siglaUfExpedicaoRg;
    }

    public function setSiglaUfExpedicaoRg($siglaUfExpedicaoRg)
    {
        $this->siglaUfExpedicaoRg = $this->valid("siglaUfExpedicaoRg", $siglaUfExpedicaoRg);
    }

    public function getTipoCertidaoCivil()
    {
        return $this->tipoCertidaoCivil;
    }

    public function setTipoCertidaoCivil($tipoCertidaoCivil)
    {
        $this->tipoCertidaoCivil = $this->valid("tipoCertidaoCivil", $tipoCertidaoCivil);
    }

    public function getTermo()
    {
        return $this->termo;
    }

    public function setTermo($termo)
    {
        $this->termo = $this->valid("termo", $termo);
    }

    public function getLivro()
    {
        return $this->livro;
    }

    public function setLivro($livro)
    {
        $this->livro = $this->valid("livro", $livro);
    }

    public function getFolha()
    {
        return $this->folha;
    }

    public function setFolha($folha)
    {
        $this->folha = $this->valid("folha", $folha);
    }

    public function getDataEmissaoCertidaoCivil()
    {
        return $this->dataEmissaoCertidaoCivil;
    }

    public function setDataEmissaoCertidaoCivil($dataEmissaoCertidaoCivil)
    {
        $this->dataEmissaoCertidaoCivil = $this->valid("dataEmissaoCertidaoCivil", $dataEmissaoCertidaoCivil);
    }

    public function getSiglaUfCertidaoCivil()
    {
        return $this->siglaUfCertidaoCivil;
    }

    public function setSiglaUfCertidaoCivil($siglaUfCertidaoCivil)
    {
        $this->siglaUfCertidaoCivil = $this->valid("siglaUfCertidaoCivil", $siglaUfCertidaoCivil);
    }

    public function getCartorioCertidaoCivil()
    {
        return $this->cartorioCertidaoCivil;
    }

    public function setCartorioCertidaoCivil($cartorioCertidaoCivil)
    {
        $this->cartorioCertidaoCivil = $this->valid("cartorioCertidaoCivil", $cartorioCertidaoCivil);
    }

    public function getNumeroCarteiraTrabalho()
    {
        return $this->numeroCarteiraTrabalho;
    }

    public function setNumeroCarteiraTrabalho($numeroCarteiraTrabalho)
    {
        $this->numeroCarteiraTrabalho = $this->valid("numeroCarteiraTrabalho", $numeroCarteiraTrabalho);
    }

    public function getSerieCarteiraTrabalho()
    {
        return $this->serieCarteiraTrabalho;
    }

    public function setSerieCarteiraTrabalho($serieCarteiraTrabalho)
    {
        $this->serieCarteiraTrabalho = $this->valid("serieCarteiraTrabalho", $serieCarteiraTrabalho);
    }

    public function getDataEmissaoCarteiraTrabalho()
    {
        return $this->dataEmissaoCarteiraTrabalho;
    }

    public function setDataEmissaoCarteiraTrabalho($dataEmissaoCarteiraTrabalho)
    {
        $this->dataEmissaoCarteiraTrabalho = $this->valid("dataEmissaoCarteiraTrabalho", $dataEmissaoCarteiraTrabalho);
    }

    public function getSiglaUfCarteiraTrabalho()
    {
        return $this->siglaUfCarteiraTrabalho;
    }

    public function setSiglaUfCarteiraTrabalho($siglaUfCarteiraTrabalho)
    {
        $this->siglaUfCarteiraTrabalho = $this->valid("siglaUfCarteiraTrabalho", $siglaUfCarteiraTrabalho);
    }

    public function getNumeroTituloEleitor()
    {
        return $this->numeroTituloEleitor;
    }

    public function setNumeroTituloEleitor($numeroTituloEleitor)
    {
        $this->numeroTituloEleitor = $this->valid("numeroTituloEleitor", $numeroTituloEleitor);
    }

    public function getZonaTituloEleitor()
    {
        return $this->zonaTituloEleitor;
    }

    public function setZonaTituloEleitor($zonaTituloEleitor)
    {
        $this->zonaTituloEleitor = $this->valid("zonaTituloEleitor", $zonaTituloEleitor);
    }

    public function getSecaoTituloEleitor()
    {
        return $this->secaoTituloEleitor;
    }

    public function setSecaoTituloEleitor($secaoTituloEleitor)
    {
        $this->secaoTituloEleitor = $this->valid("secaoTituloEleitor", $secaoTituloEleitor);
    }

    public function getOrgaoEmissorRg()
    {
        return $this->orgaoEmissorRg;
    }

    public function setOrgaoEmissorRg($orgaoEmissorRg)
    {
        $this->orgaoEmissorRg = $this->valid("orgaoEmissorRg", $orgaoEmissorRg);
    }

    public function getDataCad()
    {
        return $this->dataCad;
    }

    public function setDataCad($dataCad)
    {
        $this->dataCad = $this->valid("dataCad", $dataCad);
    }

    public function getCertidaoNascimento()
    {
        return $this->certidaoNascimento;
    }

    public function setCertidaoNascimento($certidaoNascimento)
    {
        $this->certidaoNascimento = $this->valid("certidaoNascimento", $certidaoNascimento);
    }

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
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'dataExpedicaoRg',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    'name' => new \Zend\Validator\Date(),
                ),
            )));

            // @todo ver como fica inputfilter de relacionamentos

            $inputFilter->add($factory->createInput(array(
                'name' => 'tipoCertidaoCivil',
                'required' => false,
                'validators' => array(
                    'name' => new \Zend\Filter\Digits(),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'termo',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'max' => 8,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'livro',
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
                            'max' => 8,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'folha',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'max' => 4,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'dataEmissaoCertidaoCivil',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    'name' => new \Zend\Validator\Date(),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'cartorioCertidaoCivil',
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
                            'max' => 150,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'numeroCarteiraTrabalho',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'max' => 9,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'serieCarteiraTrabalho',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'max' => 5,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'dataEmissaoCarteiraTrabalho',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    'name' => new \Zend\Validator\Date(),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'numeroTituloEleitor',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'max' => 13,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'zonaTituloEleitor',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'max' => 4,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'secaoTituloEleitor',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Digits',
                        'options' => array(
                            'max' => 4,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'dataCad',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    'name' => new \Zend\Validator\Date(),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}