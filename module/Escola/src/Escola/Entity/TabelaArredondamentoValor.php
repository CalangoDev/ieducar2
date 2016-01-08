<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/01/16
 * Time: 19:50
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade TabelaArredondamentoValor
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @package Escola
 * @version 0.1
 * @example Class TabelaArredondamentoValor
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_tabela_arredondamento_valor")
 */
class TabelaArredondamentoValor extends Entity
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
     * @var string $nome
     *
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    protected $nome;

    /**
     * @var string $descricao
     *
     * @ORM\Column(type="string", length=25)
     */
    protected $descricao;

    /**
     * @var decimal $valorMinimo
     *
     * @ORM\Column(type="decimal", precision=5, scale=3, nullable=false)
     */
    protected $valorMinimo;

    /**
     * @var decimal $valorMaximo
     *
     * @ORM\Column(type="decimal", precision=5, scale=3, nullable=false)
     */
    protected $valorMaximo;

    /**
     * @ORM\ManyToOne(targetEntity="Escola\Entity\TabelaArredondamento", inversedBy="notas")
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

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $this->valid('descricao', $descricao);
    }

    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }

    public function setValorMinimo($valorMinimo)
    {
        $this->valorMinimo = $this->valid('valorMinimo', $valorMinimo);
    }

    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }

    public function setValorMaximo($valorMaximo)
    {
        $this->valorMaximo = $this->valid('valorMaximo', $valorMaximo);
    }

    public function getTabelaArredondamento()
    {
        return $this->tabelaArredondamento;
    }

    public function setTabelaArredondamento(\Escola\Entity\TabelaArredondamento $tabelaArredondamento)
    {
        $this->tabelaArredondamento = $this->valid('tabelaArredondamento', $tabelaArredondamento);
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
                            'max' => 5,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'descricao',
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
                            'max' => 25,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'valorMinimo',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Float',
//                        'options' => array(
//                            'min' => 0,
//                            'locale' => 'pt_BR'
//                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'valorMaximo',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Float'
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'tabelaArredondamento',
                'required' => true,
            )));

        }

        return $this->inputFilter;
    }

}