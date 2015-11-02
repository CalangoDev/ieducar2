<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
{
	
    public function indexAction()
    {
        /*$pessoa = new \Usuario\Entity\Pessoa();
        $pessoa->nome = "JOAO";
        $pessoa->tipo = "F";
        $pessoa->situacao = "A";
        $pessoa->origem_gravacao = "M";
        $pessoa->operacao = "I";
        $pessoa->idsis_cad = 1;
        $pessoa->idpes_cad = 1;
        $pessoa->idpes_rev = 1;
        $this->getEntityManager()->persist($pessoa);
        $this->getEntityManager()->flush();*/
        //self::deletePessoaHistorico($pessoa);
        //echo $this->getServiceLocator()->get('myService');
        return new ViewModel();
    }

    public function deletePessoaHistorico($pessoa)
    {                
        //var_dump($this->serviceManager());
        //$sm = $this->serviceManager()->get('Doctrine\ORM\EntityManager');
        /*
		$historico = new \Historico\Entity\Pessoa();
    	$historico->idpes = $pessoa->id;
    	$historico->nome = $pessoa->nome;
    	$historico->data_cad = $pessoa->data_cad;
    	$historico->url = $pessoa->url;
    	$historico->tipo = $pessoa->tipo;
    	$historico->data_rev = $pessoa->data_rev;
    	$historico->email = $pessoa->email;
    	$historico->situacao = $pessoa->situacao;
    	$historico->origem_gravacao = $pessoa->origem_gravacao;
    	$historico->operacao = $pessoa->operacao;
    	$historico->idsis_rev = $pessoa->idsis_rev;
    	$historico->idsis_cad = $pessoa->idsis_cad;
    	$historico->idpes_rev = $pessoa->idpes_rev;
    	$historico->idpes_cad = $pessoa->idpes_cad;*/
    	// $this->getEntityManager()->persist($historico);
     //    $this->getEntityManager()->flush();
        /*
        $em = new EntityManager;
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        var_dump($em);*/
        // $sm->persist($historico);
        // $sm->flush();
    }
}
