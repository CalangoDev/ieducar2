<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/11/15
 * Time: 16:36
 */
namespace Usuario\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade Telefone
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @package Usuario
 * @version 0.1
 * @example Classe Telefone
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="cadastro_telefone")
 * @ORM\HasLifecycleCallbacks
 */
class Telefone extends Entity
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
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    protected $ddd;

    /**
     * @ORM\Column(type="string", length=11, nullable=false)
     */
    protected $numero;

    /**
     * @ORM\Column(type="date")
     */
    protected $dataCadastro;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario\Entity\Pessoa", inversedBy="telefones")
     * @ORM\JoinColumn(referencedColumnName="idpes")
     */
    protected $pessoa;

    /**
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Instituicao", inversedBy="instituicao")
     */
    protected $instituicao;

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
        $this->dataCadastro = $this->valid("dataCadastro", $dataCadastro);
    }

    public function getDdd()
    {
        return $this->ddd;
    }

    public function setDdd($ddd)
    {
        $this->ddd = $this->valid('ddd', $ddd);
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $this->valid('numero', $numero);
    }

    public function getPessoa()
    {
        return $this->pessoa;
    }

    public function setPessoa(\Usuario\Entity\Pessoa $pessoa = null)
    {
        $this->pessoa = $pessoa;
    }

    public function getInstituicao()
    {
        return $this->instituicao;
    }

    public function setInstituicao(\Escola\Entity\Instituicao $instituicao = null)
    {
        $this->instituicao = $instituicao;
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
                    array('name' => 'Int')
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'ddd',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options'=> array(
                            'encoding' => 'UTF-8',
                            'max' => 3,
                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'numero',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'continue_if_empty' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max' => 11
                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'pessoa',
                'required' => true
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'instituicao',
                'required' => false
            )));

            $this->inputFilter = $inputFilter;

        }

        return $this->inputFilter;
    }
}