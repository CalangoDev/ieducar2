<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 28/04/16
 * Time: 11:45
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade SequenciaSerie (Sequencia de enturmação)
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage SequenciaSerie
 * @version 0.1
 * @example Class SequenciaSerie
 * @copyright Copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_sequencia_serie")
 * @ORM\HasLifecycleCallbacks
 */
class SequenciaSerie extends Entity
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
     * @var int $serieOrigem
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Serie", cascade={"persist"})
     */
    protected $serieOrigem;

    /**
     * @var int $serieDestino
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\Serie", cascade={"persist"})
     */
    protected $serieDestino;

    /**
     * getters and setters
     */
    public function getId()
    {
        return $this->id;
    }

    public function getSerieOrigem()
    {
        return $this->serieOrigem;
    }

    public function setSerieOrigem(\Escola\Entity\Serie $serieOrigem)
    {
        $this->serieOrigem = $this->valid('serieOrigem', $serieOrigem);
    }

    public function getSerieDestino()
    {
        return $this->serieDestino;
    }

    public function setSerieDestino(\Escola\Entity\Serie $serieDestino)
    {
        $this->serieDestino = $this->valid('serieDestino', $serieDestino);
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
                'name' => 'serieOrigem',
                'required' => true
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'serieDestino',
                'required' => true
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

    
}