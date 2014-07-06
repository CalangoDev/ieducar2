<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Fisica;
use Usuario\Form\Fisica as FisicaForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;


/**
 * Controlador que gerencia pessoas fisicas
 * 
 * @category Usuario
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class FisicaController extends ActionController
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
	 * Mostra as pessoas fisicas cadastradas
	 * @return  void
	 */
	public function indexAction()
	{
		// $dados = $this->getEntityManager()->getRepository('Usuario\Entity\Fisica')->findAll();
		$query = $this->getEntityManager()->createQuery('SELECT f FROM Usuario\Entity\Fisica f');

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
		$query = $this->getEntityManager()->createQuery("
			SELECT

				f

			FROM

				Usuario\Entity\Fisica f
			
			WHERE

				f.nome LIKE :query 

			OR 

				f.cpf LIKE :query
		");
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
		$fisica = new Fisica;
		$form = new FisicaForm();
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);			
			$fisica->setDataNasc($fisica->getDataNasc()->format('d-m-Y'));
			$form->get('submit')->setAttribute('value', 'Editar');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Usuario\Entity\Fisica'));
		$form->bind($fisica);

		if ($request->isPost()){
			
			/**
			 * [$pessoa->origem_gravacao origem da gravacao U = usuario]
			 * @var string
			 */
			$fisica->setOrigemGravacao("U");
			/**
			 * $id vindo do formulario se id > 0 é update/alteracao se nao é insercao
			 */
			$id  = (int) $this->params()->fromPost('id', 0);			
			$fisica->setOperacao(($id > 0) ? "A" : "I");						

			$fisica->setIdsisCad(1);
						
			$date = new \DateTime($this->params()->fromPost('dataNasc'), new \DateTimeZone('America/Sao_Paulo'));
			$data = $request->getPost()->toArray();			
			$data['dataNasc'] = $date->format('Y-m-d');
			// $fisica->setDataNasc($date->format('Y-m-d'));			
			
			$form->setInputFilter($fisica->getInputFilter());			

			/**
			 * Removendo filters de inputs nao recebidos pelo o formulario
			 */			
			$fisica->removeInputFilter('origemGravacao');
			$fisica->removeInputFilter('operacao');
			$fisica->removeInputFilter('idsisCad');
			$cpf  = $this->params()->fromPost('cpf', 0);
			if ($cpf == '')
				$fisica->removeInputFilter('cpf');
			$dataNasc  = $this->params()->fromPost('dataNasc', 0);
			if ($dataNasc == '')
				$fisica->removeInputFilter('dataNasc');

			$form->setData($data);			
			if ($form->isValid()){				
				// $data = $form->getData();				
				// unset($data['submit']);
				// $fisica->setData($data);
				/**
				 * Persistindo os dados
				 */
				$this->getEntityManager()->persist($fisica);
				$this->getEntityManager()->flush();
				$this->flashMessenger()->addSuccessMessage('Pessoa Salva');
				/**
				 * Redirecionando para lista de pessoas fisicas
				 */
				return $this->redirect()->toUrl('/usuario/fisica');
			} else{
				var_dump("nao valido");
			}
		}
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){
			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);			
			$form->get('submit')->setAttribute('value', 'Edit');
		}

		return new ViewModel(array(
			'form' => $form
		));
	}


	/**
	 * Excluir uma pessoa
	 * @return void
	 */
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);	
		if ($id == 0)
			throw new \Exception("Código Obrigatório");

		try{		
			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);		
			$this->getEntityManager()->remove($fisica);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/usuario/fisica');
	}
	
}