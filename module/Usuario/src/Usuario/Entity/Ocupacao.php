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
 * Entidade Ocupacao
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Usuario
 * @subpackage  Ocupacao
 * @version  0.1
 * @example  Classe Ocupacao
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""cadastro"".""ocupacao""")
 * 
 */
class Ocupacao extends Entity
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="idocup", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="cadastro.seq_ocupacao", initialValue=1, allocationSize=1)
	 */
	protected $id;
}