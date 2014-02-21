<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
{
	private $em;

	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}

	public function getEntityManager()
	{
		if (null === $this->em){
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}
    /**
     * @var  Int $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE") 
     * @SequenceGenerator(sequenceName="historico.seq_pessoa", initialValue=1, allocationSize=1)     
     */
    //protected $id;

    public function indexAction()
    {
    	
    	// $pessoa = new \Usuario\Entity\Pessoa();
    	// $pessoa->nome = "NOME2";
    	// $pessoa->tipo = "F";
    	// $pessoa->situacao = "A";
    	// $pessoa->origem_gravacao = "M";
    	// $pessoa->operacao = "I";
    	// $pessoa->idsis_cad = 1;
    	// $pessoa->idpes_cad = 1;
    	// $pessoa->idpes_rev = 1;
    	
    	// $this->getEntityManager()->persist($pessoa);
    	
    	//delete
    	//$teste = $this->getEntityManager()->find('Usuario\Entity\Pessoa', 71);
    	if ($teste) {
    		//$this->getEntityManager()->remove($teste);                		
    	}

        //update
        // $teste2 = $this->getEntityManager()->find('Usuario\Entity\Pessoa', 72);        
        // var_dump($teste2->nome);

        // $this->getEntityManager()->flush();
    	// $pessoa = $objectManager->getRepository('Usuario\Entity\Pessoa')->findOneBy(array('id' => 47));
    	// $objectManager->remove($pessoa);
    	// $objectManager->flush();    	
        return new ViewModel();
    }
}
