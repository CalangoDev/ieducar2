<?php
namespace Drh\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * Entidade Setor
 * 
 * @author  Eduardo Junior <ej@calangodev.com.br>
 * @category  Entidade
 * @package  Drh
 * @subpackage  Setor
 * @version  0.1
 * @example  Classe Setor
 * @copyright  Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 * 
 * @ORM\Entity
 * @ORM\Table(name="drh_setor")
 */
class Setor extends Entity
{

	/**
	 * @var  int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")	 
	 */
	protected $id;

	/**
	 * @var string $nome Nome do setor
	 * 
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	protected $nome;

	/**
	 * @var int $ref_cod_setor Ref do codigo setor pai
	 * 
	 * @ORM\ManyToOne(targetEntity="Setor", cascade={"persist"})
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL")
	 */
	protected $parentSetor;

	/**
	 * @var string $sigla Sigla do setor
	 * 
	 * @ORM\Column(type="string", length=25, nullable=false)
	 */
	protected $sigla;

	/**
	 * @var smallint $ativo
	 *
	 * 0 - Inativo
	 * 1 - Ativo
	 * @ORM\Column(type="smallint", nullable=false)
	 */
	protected $ativo = 1;

	/**
	 * @var smallint $nivel 
	 * 
	 * @todo  Nao sei a que tipo de nivel isso se refere no sistema
	 * @ORM\Column(type="smallint", nullable=false)
	 */
	protected $nivel = 1;

	/**
	 * @var text $endereco Endereço do setor
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $endereco;

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

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $this->valid('ativo', $ativo);
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $this->valid('endereco', $endereco);
    }

    public function getNivel()
    {
        return $this->nivel;
    }

    public function setNivel($nivel)
    {
        $this->nivel = $this->valid('nivel', $nivel);
    }

    public function getParentSetor()
    {
        return $this->parentSetor;
    }

    public function setParentSetor(Setor $parentSetor = null)
    {
        $this->parentSetor = $this->valid('parentSetor', $parentSetor);
    }

    public function getSigla()
    {
        return $this->sigla;
    }

    public function setSigla($sigla)
    {
        $this->sigla = $this->valid('sigla', $sigla);
    }

	/**
	 * [$intputFilter description]
	 * @var Zend\InputFilter\InputFilter
	 */
	protected $inputFilter;

	/**
	 * Configura os filtros dos campos da entidade
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
				'filters'	=>	array(
					array('name'	=>	'StripTags'),
					array('name'	=>	'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',						
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 255,
						),
					),
				),				
			)));

            $inputFilter->add($factory->createInput(array(
                'name' => 'parentSetor',
                'required' => false,
            )));


			$inputFilter->add($factory->createInput(array(
				'name' => 'sigla',
				'required' => true,
				'filters'	=>	array(
					array('name'	=>	'StripTags'),
					array('name'	=>	'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',						
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 25,
						),
					),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'ativo',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

            $inputFilter->add($factory->createInput(array(
                'name' => 'nivel',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'endereco',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
            )));

			$this->inputFilter = $inputFilter;
		}		
		return $this->inputFilter;
	}
}