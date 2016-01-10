<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/01/16
 * Time: 13:39
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade TabelaArredondamento
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage TabelaArrendondamento
 * @version 0.2
 * @example Class TabelaArredondamento
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_tabela_arredondamento")
 */
class TabelaArredondamento extends Entity
{

    public function __construct()
    {
        $this->notas = new ArrayCollection();
    }

    /**
     * @var int $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $nome Nome da Tabela
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $nome;

    /**
     * @var smallint $tipoNota
     *
     * Pode ser nota numerica ou conceitual ( 1 = numerica, 2 = conceitual )
     *
     * @ORM\Column(type="smallint", length=1, nullable=false)
     */
    protected $tipoNota = 1;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Escola\Entity\TabelaArredondamentoValor", mappedBy="tabelaArredondamento",
     *     cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     */
    protected $notas;

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

    /**
     * @param Collection $notas
     */
    public function addNotas(Collection $notas)
    {
        foreach ($notas as $nota) {
            if ($nota->getNome() != "") {
                $nota->setTabelaArredondamento($this);
                $this->notas->add($nota);
            }
        }
    }

    /**
     * @param Collection $notas
     */
    public function removeNotas(Collection $notas)
    {
        foreach ($notas as $nota) {
            if ($nota->getNome() != ""){
                $nota->setTabelaArredondamento(null);
                $this->notas->removeElement($nota);
            }
        }
    }

    public function getNotas()
    {
        return $this->notas;
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
                'name' => 'notas',
                'required' => false,
                'continue_if_empty' => true,
            )));

            $this->inputFilter = $inputFilter;

        }

        return $this->inputFilter;
    }

}