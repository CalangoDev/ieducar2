<?php
namespace Auth\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Auth\Entity\Resource;
use Auth\Form\Resource as ResourceForm;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

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
	 * Mostra as regras cadastradas
	 * @return void 
	 */
	public function indexAction()
	{
		// $dados = $this->getEntityManager()->getRepository('Auth\Entity\Resource')->findAll();
		$query = $this->getEntityManager()->createQuery('SELECT r FROM Auth\Entity\Resource r');

		$dados = new Paginator(
			new DoctrinePaginator(new ORMPaginator($query))
		);		
		
		$dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

		return new ViewModel(array(
			'dados' => $dados
		));
	}

	/**
	 * Busca
	 */
	public function buscaAction()
	{
		$q = (string) $this->params()->fromPost('q');
		// $q = (string) $this->params()->fromRoute('query');
		// var_dump($q);
		// $query = $em->createQuery("SELECT u FROM CmsUser u LEFT JOIN u.articles a WITH a.topic LIKE '%foo%'");
		$query = $this->getEntityManager()->createQuery("SELECT r FROM Auth\Entity\Resource r WHERE r.nome LIKE :query OR r.descricao LIKE :query ");
		$query->setParameter('query', "%".$q."%");		
		$dados = $query->getResult();		
		
		$view = new ViewModel(array(
			'dados' => $dados
		));
		$view->setTerminal(true);

		return $view;
	}

	public function saveAction()
	{
		$resource = new Resource;
		$form = new ResourceForm();
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){			
			$resource = $this->getEntityManager()->find('Auth\Entity\Resource', $id);
			$form->get('submit')->setAttribute('value', 'Editar');
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