<?php
namespace Auth\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;
use Auth\Entity\Role;
use Auth\Form\Role as RoleForm;


use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
/**
 * Controlador que gerencia as regras de permissões para cada usuario
 * 
 * @category Auth
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class RoleController extends ActionController
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
		// $dados = $this->getEntityManager()->getRepository('Auth\Entity\Role')->findAll();
		$query = $this->getEntityManager()->createQuery('SELECT r FROM Auth\Entity\Role r');

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
		$query = $this->getEntityManager()->createQuery("SELECT re.nome FROM Auth\Entity\Role r LEFT JOIN r.resource re WHERE re.nome LIKE :query");
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
		$role = new Role;
		$form = new RoleForm($this->getEntityManager());
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){			
			$role = $this->getEntityManager()->find('Auth\Entity\Role', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Auth\Entity\Role'));
		$form->bind($role);

		if ($request->isPost()){			
			$form->setInputFilter($role->getInputFilter());
			// $setor->removeInputFilter('sigla_setor');
			$form->setData($request->getPost());				
			if ($form->isValid()){				
				/**
				 * Persistindo os dados
				 * se for insert persist os dados se nao só o flush
				 */
				$id = (int) $this->params()->fromPost('id', 0);
				if ($id == 0){
					$this->getEntityManager()->persist($role);
					$this->flashMessenger()->addSuccessMessage('Nova Regra salva');
				} else {
					$this->flashMessenger()->addSuccessMessage('Permissão foi alterada!');
				}
				$this->getEntityManager()->flush();
				
				/**
				 * Redirecionando para lista de setores
				 */
				return $this->redirect()->toUrl('/auth/role');
			} 
		} 

		/**
		 * @todo refatorar a logica do get id, estou praticamente duplicando codigo 		 
		 */
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){			
			$role = $this->getEntityManager()->find('Auth\Entity\Role', $id);
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
			$role = $this->getEntityManager()->find('Auth\Entity\Role', $id);
			$this->getEntityManager()->remove($role);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/auth/role');
	}
}