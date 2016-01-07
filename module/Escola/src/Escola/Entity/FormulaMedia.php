<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/01/16
 * Time: 08:41
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade FormulaMedia
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage FormulaMedia
 * @version 0.2
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_formula_media")
 */
class FormulaMedia extends Entity
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
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $nome;

    /**
     * @var string $formulaMedia
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $formulaMedia;

    /**
     * @var smallint $tipoFormula
     *
     * @ORM\Column(type="smallint", length=1)
     */
    protected $tipoFormula = 1;

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

    public function getFormulaMedia()
    {
        return $this->formulaMedia;
    }

    public function setFormulaMedia($formulaMedia)
    {
        $this->formulaMedia = $this->valid('formulaMedia', $formulaMedia);
    }

    public function getTipoFormula()
    {
        return $this->tipoFormula;
    }

    public function setTipoFormula($tipoFormula)
    {
        $this->tipoFormula = $this->valid('tipoFormula', $tipoFormula);
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
                    array('name' => 'Int'),
                ),
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
                'name' => 'formulaMedia',
                'required' => true,
                'error_message' => 'Token inválido.',
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
//                    array('name' => 'Whitelist', 'options' => array(
//                        'list' => array(
//                            'gold', 'teste'
//                        )
//                    )),
                    array('name' => 'Escola\Filter\FormulaMedia', 'options' => array(
                        'list' => array(
                            'Se', 'Et', 'Rc',
                            'E1', 'E2', 'E3', 'E4', 'E5', 'E6', 'E7', 'E8', 'E9', 'E10',
                            '/', '*', 'x', '+',
                            '(', ')'
                        )
                    ))
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 50,
                        )
                    ),
                )
            )));


            $inputFilter->add($factory->createInput(array(
                'name' => 'tipoFormula',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int')
                )
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}