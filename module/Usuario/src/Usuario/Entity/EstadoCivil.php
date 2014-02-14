<?php
namespace Usuario\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Estado Civil
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Usuario
 * @subpackage  Estado Civil
 * @version  0.1
 * @example  Classe EstadoCivil
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""cadastro"".""estado_civil""")
 * 
 */
class EstadoCivil extends Entity
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="ideciv", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="cadastro.seq_estado_civil", initialValue=1, allocationSize=1)
	 */
	protected $id;
}