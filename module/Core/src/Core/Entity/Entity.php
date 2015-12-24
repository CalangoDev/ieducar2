<?php
namespace Core\Entity;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Exception\InvalidArgumentException;

abstract class Entity implements InputFilterAwareInterface
{    

	/**
     * Filters
     * 
     * @var InputFilter
     */
    protected $inputFilter = null;


    /**
     * Removido os metodos magicos, decisao tomada depois de pesquisa, sobre a lentidao em usar os metodos magicos
     */
	/**
	 * Magic getter to expose protected properties.
	 * 
	 * @param string $property
	 * @return  mixed
	 */
	// public function __get($property)
	// {
	// 	return $this->$property;
	// }

	/**
	 * Magic setter to save protected properties.
	 * 
	 * @param  string $property
	 * @return  mixed $value
	 */
	// public function __set($property, $value)
	// {
 //        var_dump($property);
	// 	$this->$property = $this->valid($property, $value);
	// }

	/**
	 * Convert the object to an array
	 * 
	 * @return  array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	/**
     * @param InputFilterInterface $inputFilter
     * @return void
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new EntityException("Not used");
    }

    /**
     * Set all entity data based in an array with data
     *
     * @param array $data
     * @return void
     */
    // public function setData($data)
    // {
    //     foreach($data as $key => $value) {
    //         $this->__set($key, $value);
    //     }
    // }

    /**
     * Return all entity data in array format
     *
     * @return array
     */
    public function getData()
    {
        var_dump("function getdata");
        $data = get_object_vars($this);
        unset($data['inputFilter']);        
        return array_filter($data);
    }

    /**
     * Entity filters
     *
     * @return InputFilter
     */
    public function getInputFilter() {}


    /**
     * Filter and validate data
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function valid($key, $value)
    {    
        //var_dump("KEY: " . $key . " VALUE: " . $value);
        if (!$this->getInputFilter())
            return $value;

        try {
            $filter = $this->getInputFilter()->get($key);
        } catch(InvalidArgumentException $e) {
            //não existe filtro para esse campo
            return $value;
        }    

        $filter->setValue($value);
        if(!$filter->isValid())
            throw new EntityException("Input inválido: $key = $value");

        return $filter->getValue($key);
    }

    /**
     * [removeInputFilter remove um inputfilter]
     * @param  Zend\InputFilter\InputFilter  
     */
    public function removeInputFilter($input)
    {        
        $inputFilter    = new InputFilter();                        
        $this->inputFilter->remove($input);
        
        return $this->inputFilter;
    }
	
    /*
     * Used by TableGateway
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getData();
    }
}