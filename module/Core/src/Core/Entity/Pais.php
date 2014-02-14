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
 * Entidade Pais
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Core
 * @subpackage  Pais
 * @version  0.1
 * @example  Classe Municipio
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="pais")
 * 
 */
class Pais extends Entity
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="idpais", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="public.seq_pai", initialValue=1, allocationSize=1)
	 */
	protected $id;
}