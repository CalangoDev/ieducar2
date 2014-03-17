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
 * Entidade Religiao
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Usuario
 * @subpackage  Religiao
 * @version  0.1
 * @example  Classe Religiao
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""cadastro"".""religiao""")
 * 
 */
class Religiao extends Entity
{
	/**
	 * @var int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="cod_religiao", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="cadastro.seq_escolaridade", initialValue=1, allocationSize=1)
	 */
	protected $id;

	/**
	 * @var string $nm_religiao Nome da religiao
	 * 
	 * @ORM\Column(type="string", length=50, nullable=false)	 
	 */
	protected $nm_religiao;

	/**
	 * @var datetime  $data_cadastro	Data de Cadastro
	 * 
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	protected $data_cadastro;

	// idpes_exc integer,
 	//  	idpes_cad integer NOT NULL,
	//  	nm_religiao character varying(50) NOT NULL,
	//  	data_cadastro timestamp without time zone NOT NULL,
	//  	data_exclusao timestamp without time zone,
	//  	ativo boolean DEFAULT false,
}