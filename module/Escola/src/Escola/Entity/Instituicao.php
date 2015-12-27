<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/12/15
 * Time: 17:29
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade Instituicao
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @package Escola
 * @subpackage Instituicao
 * @version 0.1
 * @example Class Instituicao
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_instituicao")
 * @ORM\HasLifecycleCallbacks
 */
class Instituicao extends Entity
{

    public function __construct()
    {
        $this->telefones = new ArrayCollection();
    }

    /**
     * @var Int $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var String $nome Nome da instituicao
     *
     * @ORM\Column(type="string", nullable=false, length=200)
     */
    protected $nome;

    /**
     * @var String $responsavel Nome do responsavel pela instituicao
     *
     * @ORM\Column(type="string", nullable=true, length=200)
     */
    protected $responsavel;

    /**
     * @var EnderecoExterno $enderecoExterno Entity Endereço Externo
     *
     * @todo mudar local da entity enderecoexterno para o core
     *
     * @ORM\ManyToOne(targetEntity="Usuario\Entity\EnderecoExterno", cascade={"persist"})
     * @ORM\JoinColumn(name="enderecoExterno")
     */
    protected $enderecoExterno;

    /**
     * @var DateTime $dataCadastro Data de cadastro
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $dataCadastro;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Usuario\Entity\Telefone", mappedBy="instituicao", cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     * @todo mover entity Telefone para Core e testar depois
     */
    protected $telefones;

    /**
     * @var boolean $ativo
     *
     * @ORM\Column(type="boolean")
     */
    protected $ativo = true;

    /**
     * Função para gerar o timestamp para o atributo dataCadastro, é executada antes de salvar os dados no banco
     * @access  public
     * @return  void
     * @ORM\PrePersist
     */
    public function timestamp()
    {
        if (is_null($this->getDataCadastro())) {
            $this->setDataCadastro(new \DateTime());
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDataCadastro()
    {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro)
    {
        $this->dataCadastro = $this->valid('dataCadastro', $dataCadastro);
    }

    public function getEnderecoExterno()
    {
        return $this->enderecoExterno;
    }

    public function setEnderecoExterno($enderecoExterno)
    {
        $this->enderecoExterno = $this->valid('enderecoExterno', $enderecoExterno);
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $this->valid('nome', $nome);
    }

    public function getResponsavel()
    {
        return $this->responsavel;
    }

    public function setResponsavel($responsavel)
    {
        $this->responsavel = $this->valid('responsavel', $responsavel);
    }

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
    }

    public function addTelefones(Collection $telefones)
    {
        foreach ($telefones as $telefone){
            $telefone->setInstituicao($this);
            $this->telefones->add($telefone);
        }
    }

    public function removeTelefones(Collection $telefones)
    {
        foreach ($telefones as $telefone){
            $telefone->setInstituicao(null);
            $this->telefones->removeElement($telefone);
        }
    }

    public function getTelefones()
    {
        return $this->telefones;
    }



    // filter
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
                            'max' => 200,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'responsavel',
                'required' => false,
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
                            'max' => 200,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'enderecoExterno',
                'required' => true,
                'continue_if_empty' => true,
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'telefones',
                'required' => false
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'ativo',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $this->inputFilter = $inputFilter;

        }

        return $this->inputFilter;

    }

}
