<?php
namespace Drh\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Setor
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Drh
 * @subpackage  Setor
 * @version  0.1
 * @example  Classe Setor
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""pmidrh"".""setor""")
 * 
 */
class Setor extends Entity
{
	/**
	 * @var  int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="cod_setor", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="pmidrh.setor_cod_setor_seq", initialValue=1, allocationSize=1)
	 */
	protected $id;

	/**
	 * @var string $nome Nome do setor
	 * 
	 * @ORM\Column(name="nm_setor", type="string", length=255, nullable=false)
	 */
	protected $nome;

	/**
	 * @var int $ref_cod_pessoa_exc Ref da Pessoa(funcionario) que excluiu o registro
	 * 
	 * @ORM\ManyToOne(targetEntity="Portal\Entity\Funcionario", cascade={"persist"})
	 * @ORM\JoinColumn(name="ref_cod_pessoa_exc", referencedColumnName="ref_cod_pessoa_fj", onDelete="SET NULL")
	 */
	protected $pessoa_exclu;

	/**
	 * @var int $ref_cod_pessoa_cad Ref da pessoa(funcionario) que cadastrou o registro
	 * 
	 * @ORM\ManyToOne(targetEntity="Portal\Entity\Funcionario", cascade={"persist"})
	 * @ORM\JoinColumn(name="ref_cod_pessoa_cad", referencedColumnName="ref_cod_pessoa_fj", onDelete="SET NULL")
	 */
	protected $pessoa_cad;

	/**
	 * @var int $ref_cod_setor Ref do codigo setor pai
	 * 
	 * @ORM\ManyToOne(targetEntity="Setor", cascade={"persist"})
	 * @ORM\JoinColumn(name="ref_cod_setor", referencedColumnName="cod_setor", onDelete="SET NULL")
	 */
	protected $ref_cod_setor;

	/**
	 * @var string $sigla_setor Sigla do setor
	 * 
	 * @ORM\Column(name="sgl_setor", type="string", length=15, nullable=false)
	 */
	protected $sigla_setor;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($value)
	{
		$this->id = $this->valid("id", $value);
	}

	public function getNome()
	{
		return $this->nome;
	}
	
	public function setNome($value)
	{
		$this->nome = $this->valid("nome", $value);
	}
}