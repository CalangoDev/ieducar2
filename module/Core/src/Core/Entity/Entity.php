<?php
namespace Core\Entity;

// use Zend\InputFilter\Factory as InputFactory;
// use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
// use Zend\InputFilter\InputFilterInterface;
// use Zend\InputFilter\Exception\InvalidArgumentException;

abstract class Entity implements InputFilterAwareInterface
{

	/**
	 * Magic getter to expose protected properties.
	 * 
	 * @param string $property
	 * @return  mixed
	 */
	public function __get($property)
	{
		return $this->$property;
	}

	/**
	 * Magic setter to save protected properties.
	 * 
	 * @param  string $property
	 * @return  mixed $value
	 */
	public function __set($property, $value)
	{
		$this->$property = $value;
	}

	/**
	 * Convert the object to an array
	 * 
	 * @return  array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	
}