<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/11/15
 * Time: 15:31
 */
namespace Usuario\Entity;

use Core\Entity\Entity;
use Core\Entity\Uf;
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
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $rg;

    /**
     * @ORM\Column(type="date", name="data_emissao_rg", nullable=true)
     */
    protected $dataEmissaoRg;

    /**
     * //, cascade={"persist"}
     * @ORM\ManyToOne(targetEntity="Core\Entity\Uf")
     * @ORM\JoinColumn(name="sigla_uf_emissao_rg", onDelete="NO ACTION", nullable=true)
     */
    protected $siglaUfEmissaoRg;

    /**
     * Atributo que armazena o tipo de certidao civil, existem 3 tipos
     * - nascimento(novo formato), so apresenta um input certidao_nascimento
     * - nascimento(antigo formato), apresenta os inputs termo, livro, folha
     * - casamento - apresenta os inputs termo, livro, folha
     *
     * @ORM\Column(type="integer", length=2, name="tipo_cert_civil", nullable=true)
     */
    protected $tipoCertidaoCivil;

    /**
     * @ORM\Column(type="string", length=8, name="num_termo", nullable=true)
     */
    protected $termo;

    /**
     * @ORM\Column(type="string", length=8, name="num_livro", nullable=true)
     */
    protected $livro;

    /**
     * @ORM\Column(type="integer", length=4, name="num_folha", nullable=true)
     */
    protected $folha;

    /**
     * @ORM\Column(type="date", name="data_emissao_cert_civil", nullable=true)
     */
    protected $dataEmissaoCertidaoCivil;

    /**
     * @ORM\ManyToOne(targetEntity="Core\Entity\Uf")
     * @ORM\JoinColumn(name="sigla_uf_cert_civil", onDelete="NO ACTION", nullable=true)
     */
    protected $siglaUfCertidaoCivil;

    /**
     * @ORM\Column(type="string", length=150, name="cartorio_cert_civil", nullable=true)
     */
    protected $cartorioCertidaoCivil;

    /**
     * @ORM\Column(type="string", length=9, name="num_cart_trabalho", nullable=true)
     */
    protected $numeroCarteiraTrabalho;

    /**
     * @ORM\Column(type="integer", length=5, name="serie_cart_trabalho", nullable=true)
     */
    protected $serieCarteiraTrabalho;

    /**
     * @ORM\Column(type="date", name="data_emissao_cart_trabalho", nullable=true)
     */
    protected $dataEmissaoCarteiraTrabalho;

    /**
     * @ORM\ManyToOne(targetEntity="Core\Entity\Uf")
     * @ORM\JoinColumn(name="sigla_uf_cart_trabalho", onDelete="NO ACTION", nullable=true)
     */
    protected $siglaUfCarteiraTrabalho;

    /**
     * @ORM\Column(type="string", length=13, name="num_tit_eleitor", nullable=true)
     */
    protected $numeroTituloEleitor;

    /**
     * @ORM\Column(type="integer", length=4, name="zona_tit_eleitor", nullable=true)
     */
    protected $zonaTituloEleitor;

    /**
     * @ORM\Column(type="integer", length=4, name="secao_tit_eleitor", nullable=true)
     */
    protected $secaoTituloEleitor;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario\Entity\OrgaoEmissorRg", cascade={"persist"})
     * ORM\JoinColumn(name="idorg_exp_rg", onDelete="NO ACTION")
     */
    protected $orgaoEmissorRg;

    /**
     * @var  DateTime $data_cad Data de cadastro
     * @ORM\Column(name="data_cad", type="datetime", nullable=false)
     */
    protected $dataCad;

    /**
     * @ORM\Column(type="string", length=50, name="certidao_nascimento", nullable=true)
     */
    protected $certidaoNascimento;

    /**
     * @ORM\OneToOne(targetEntity="Usuario\Entity\Fisica", mappedBy="documento")
     */
    protected $fisica;

    /**
     * Função para gerar o timestamp para o atributo data_cad, é executada antes de salvar os dados no banco
     * @access  public
     * @return  void
     * @ORM\PrePersist
     */
    public function timestamp()
    {
        if (is_null($this->getDataCad())) {
            $this->setDataCad(new \DateTime());
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRg()
    {
        return $this->rg;
    }

    public function setRg($rg)
    {
        $this->rg = $this->valid("rg", $rg);
    }

    public function getDataEmissaoRg()
    {

        if (!empty($this->dataEmissaoRg)){

            $dataEmissaoRg = $this->dataEmissaoRg->format('d-m-Y');
            return $dataEmissaoRg;

        }

        return $this->dataEmissaoRg;

    }

    public function setDataEmissaoRg($dataEmissaoRg)
    {
        $this->dataEmissaoRg = $this->valid("dataEmissaoRg", $dataEmissaoRg);
    }

    public function getSiglaUfEmissaoRg()
    {
        return $this->siglaUfEmissaoRg;
    }

    public function setSiglaUfEmissaoRg(Uf $siglaUfEmissaoRg = null)
    {
        $this->siglaUfEmissaoRg = $this->valid("siglaUfEmissaoRg", $siglaUfEmissaoRg);
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

        if (!empty($this->dataEmissaoCertidaoCivil)) {

            $dataEmissaoCertidaoCivil = $this->dataEmissaoCertidaoCivil->format('d-m-Y');
            return $dataEmissaoCertidaoCivil;

        }

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

    public function setSiglaUfCertidaoCivil(Uf $siglaUfCertidaoCivil = null)
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

        if (!empty($this->dataEmissaoCarteiraTrabalho))
            return $this->dataEmissaoCarteiraTrabalho->format('d-m-Y');

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

    public function setSiglaUfCarteiraTrabalho(Uf $siglaUfCarteiraTrabalho = null)
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

    public function setOrgaoEmissorRg(OrgaoEmissorRg $orgaoEmissorRg = null)
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

    public function getFisica()
    {
        return $this->fisica;
    }

    public function setFisica(Fisica $fisica)
    {
        $this->fisica = $this->valid('fisica', $fisica);
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
                'name' => 'dataEmissaoRg',
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
                'filters' => array(
                    array('name' => 'Int'),
                    array(
                        'name' => 'Null',
                        'options' => array(
                            'type' => 'all'
                        ),
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 1,
                            'max' => 99,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'termo',
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
                'filters' => array(
                    array('name' => 'Int'),
                    array(
                        'name' => 'Null',
                        'options' => array(
                            'type' => 'all'
                        ),
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 1,
                            'max' => 9999,
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
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max' => 9,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'serieCarteiraTrabalho',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                    array(
                        'name' => 'Null',
                        'options' => array(
                            'type' => 'all'
                        ),
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 1,
                            'max' => 99999,
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
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max' => 13,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'zonaTituloEleitor',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                    array(
                        'name' => 'Null',
                        'options' => array(
                            'type' => 'all'
                        ),
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 1,
                            'max' => 9999,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'secaoTituloEleitor',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                    array(
                        'name' => 'Null',
                        'options' => array(
                            'type' => 'all'
                        ),
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 1,
                            'max' => 9999,
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

            $inputFilter->add($factory->createInput(array(
                'name' => 'siglaUfEmissaoRg',
                'required' => true,
                'continue_if_empty' => true,
                'filters'     => array(
                    array('name' => 'Null'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'siglaUfCertidaoCivil',
                'required' => true,
                'continue_if_empty' => true,
                'filters'     => array(
                    array('name' => 'Null'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'siglaUfCarteiraTrabalho',
                'required' => true,
                'continue_if_empty' => true,
                'filters'     => array(
                    array('name' => 'Null'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'orgaoEmissorRg',
                'required' => true,
                'continue_if_empty' => true,
                'filters'     => array(
                    array('name' => 'Int'),
                    array('name' => 'Null'),
                ),
//                'filters' => array(
//                    array(
//                        'name' => 'Null',
//                        'options' => array(
//                            'type' => 'all'
//                        ),
//                    ),
//                ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

}