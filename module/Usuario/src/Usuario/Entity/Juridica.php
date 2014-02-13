<?php
namespace Usuario\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\EventSubscriber;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Juridica
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Usuario
 * @subpackage  Juridica
 * @version  0.1
 * @example  Classe Juridica
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""cadastro"".""juridica""")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Juridica extends Pessoa implements EventSubscriber
{
	public function getSubscribedEvents ()
    {
        return array(
                        
        );
    }
	/**
	 * @var Int $id Identificador da entidade fisica
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="cadastro.seq_juridica", initialValue=1, allocationSize=1)	 
	 */
	protected $id;


	/**
	 * [$inputFilter recebe os filtros]
	 * @var Zend\InputFilter\InputFilter
	 */
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


			$this->inputFilter = $inputFilter;
		}
		return $this->inputFilter;
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

}