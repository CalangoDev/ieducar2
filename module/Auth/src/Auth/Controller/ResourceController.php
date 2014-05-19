<?php
namespace Auth\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;
use Auth\Entity\Resource;
use Auth\Form\Resource as ResourceForm;

/**
 * Controlador que gerencia os resources do sistema
 * 
 * Manager Resources of system
 * 
 * @category Auth
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class ResourceController extends ActionController
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

	/**
	 * Mostra as regras cadastradas
	 * @return void 
	 */
	public function indexAction()
	{
		$dados = $this->getEntityManager()->getRepository('Auth\Entity\Resource')->findAll();

		return new ViewModel(array(
			'dados' => $dados
		));
	}

	public function saveAction()
	{
		$resource = new Resource;
		$form = new ResourceForm();
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){			
			$resource = $this->getEntityManager()->find('Auth\Entity\Resource', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Auth\Entity\Resource'));
		$form->bind($resource);

		if ($request->isPost()){			
			$form->setInputFilter($resource->getInputFilter());			
			$form->setData($request->getPost());				
			if ($form->isValid()){				
				/**
				 * Persistindo os dados
				 * se for insert persist os dados se nao só o flush
				 */
				$id = (int) $this->params()->fromPost('id', 0);
				if ($id == 0){
					$this->getEntityManager()->persist($resource);
					$this->flashMessenger()->addSuccessMessage('Novo recurso salvo');
				} else {
					$this->flashMessenger()->addSuccessMessage('Recurso foi alterado!');
				}
				$this->getEntityManager()->flush();
				
				/**
				 * Redirecionando para lista de resource
				 */
				return $this->redirect()->toUrl('/auth/resource');
			} 
		} 

		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){			
			$resource = $this->getEntityManager()->find('Auth\Entity\Resource', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}

		return new ViewModel(array(
			'form' => $form
		));

	}

	/**
	 * Excluir uma regra
	 * @return void
	 */
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);	
		if ($id == 0)
			throw new \Exception("Código Obrigatório");

		try{		
			$resource = $this->getEntityManager()->find('Auth\Entity\Resource', $id);
			$this->getEntityManager()->remove($resource);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/auth/resource');
	}

}