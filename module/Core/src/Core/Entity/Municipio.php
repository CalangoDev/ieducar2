<?php
namespace Core\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Municipio
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Core
 * @subpackage  Municipio
 * @version  0.1
 * @example  Classe Municipio
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="municipio")
 * 
 */
class Municipio extends Entity
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="idmun", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="public.seq_municipio", initialValue=1, allocationSize=1)
	 */
	protected $id;
}