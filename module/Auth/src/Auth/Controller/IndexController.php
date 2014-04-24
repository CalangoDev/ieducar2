<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Auth\Form\Login as LoginForm;
// use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

/**
 * Controlador que gerencia a autenticacao
 * 
 * @category Auth
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class IndexController extends ActionController
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

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

    // public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    // {
    //     $this->serviceLocator = $serviceLocator;
    // }

    public function indexAction()
    { 
    	$form = new LoginForm();
    	$request = $this->getRequest();

    	if ($request->isPost()){
    		$data = $this->getRequest()->getPost();
    		// var_dump($data);
    		// var_dump($data['senha']);
    		$senha = md5($data['senha']);
    		// var_dump($senha);
    		// FAZ AUTENTICACAO
    		$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
    		$adapter = $authService->getAdapter();
    		$adapter->setIdentityValue($data['matricula']);
    		$adapter->setCredentialValue($senha);
    		$authResult = $authService->authenticate();    		
    		
    		if ($authResult->isValid()) {    			
        		// return $this->redirect()->toRoute('home');
        		$identity = $authResult->getIdentity();                
        		return $this->redirect()->toUrl('/');
    		} else {
    			$this->flashMessenger()->addMessage(array("error" => "Matrícula ou senha inválidos"));
    		}
   			//$this->flashMessenger()->addMessage(array("success" => "Atendimento Realizado com sucesso!"));                
			//$this->flashMessenger()->addMessage(array("error" => "Class: " . get_class($e)));
   			//$this->flashMessenger()->addMessage(array("error" => "Message: " . $e->getMessage()));
    	}


        return new ViewModel(array(
        	'form' => $form
        ));
    }

    public function logadoAction()
    {        
    	$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');        
    	// $loggedUser = $authService->getIdentity();    	
    	
    	($user = $authService->getIdentity()) ? $this->flashMessenger()->addMessage(array("sucess" => 'Você logou com sucesso!')) : $this->flashMessenger()->addMessage(array("error" => "Você não está logado!"));
    		
    	return new ViewModel();
    }
}
