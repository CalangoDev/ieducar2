<?php
namespace Drh\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Drh\Entity\Funcionario;
use Drh\Form\Funcionario as FuncionarioForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

// use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Controlador que gerencia os funcionarios
 * 
 * @category Portal
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class FuncionarioController extends ActionController
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
	 * Mostra os funcionarios cadastrados
	 * @return void 
	 */
	public function indexAction()
	{
		// $dados = $this->getEntityManager()->getRepository('Portal\Entity\Funcionario')->findAll();
		$query = $this->getEntityManager()->createQuery('SELECT f FROM Portal\Entity\Funcionario f');

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

				Portal\Entity\Funcionario f

			LEFT JOIN

				f.refCodPessoaFj fj

			WHERE

				f.matricula LIKE :query 

			OR 

				fj.nome LIKE :query
		");
		$query->setParameter('query', "%".$q."%");		
		$dados = $query->getResult();		
		
		$view = new ViewModel(array(
			'dados' => $dados
		));
		$view->setTerminal(true);

		return $view;
	}

	/**
	 * Inseri um novo funcionario no banco, depende que exista uma pessoa fisica no sistema para fazer a inserção
	 * parametro deve ser passado pela url
	 */
	public function saveAction()
	{
		$funcionario = new Funcionario;
		$form = new FuncionarioForm($this->getEntityManager());
		// $form = new FuncionarioForm();
		$request = $this->getRequest();

		// $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		$id = (int) $this->getEvent()->getRouteMatch()->getParam('refCodPessoaFj');
		if ($id > 0){			
			$funcionario = $this->getEntityManager()->find('Portal\Entity\Funcionario', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Portal\Entity\Funcionario'));		
		$form->bind($funcionario);

		if ($request->isPost()){
			$id = (int) $this->params()->fromPost('refCodPessoaFj', 0);
			$form->setInputFilter($funcionario->getInputFilter());
			$form->setData($request->getPost());						
			if ($form->isValid()){								
				$refCodPessoaFj = $form->get('refCodPessoaFj')->getValue();				
				/**
				 * Buscar a Pessoa Fisica pelo o ID passado e associar a entity fisica com a de funcionario
				 * 
				 * Pode ser feito usando hydrator como no codigo depois das duas linhas seguintes que esta comentado
				 */				
				$pessoaFisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $refCodPessoaFj);
				$funcionario->setRefCodPessoaFj($pessoaFisica);				
				// $hydrator = new DoctrineHydrator($this->getEntityManager(), 'Usuario\Entity\Fisica');
				// $fisica = new \Usuario\Entity\Fisica;
				// $dados = array(
				// 	'id' => $ref_cod_pessoa_fj
				// );
				// $fisica = $hydrator->hydrate($dados, $fisica);
				// $funcionario->setRefCodPessoaFj($fisica);
				
				/**
				 * Persistindo os dados
				 */
				
				// $this->getEntityManager()->persist($funcionario);
				$this->getEntityManager()->flush();
				$this->flashMessenger()->addSuccessMessage('Novo Funcionário Inserido');
				/**
				 * @todo verificar quando tiver inserindo ou editando 
				 * personalisar mensagem de funcionario inserido ou nao
				 * e verificar questoes de tempo de renovacao de conta e tempo para expirar a senha
				 * checar se essas funcionalidades vao ser codificadas
				 */				 
				return $this->redirect()->toUrl('/portal/funcionario');
			} 	
		}
		// $id = (int) $this->params()->fromRoute('id', 0);
		$id = (int) $this->params()->fromRoute('refCodPessoaFj', 0);
		if ($id >0){			
			$funcionario = $this->getEntityManager()->find('Portal\Entity\Funcionario', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}

		return new ViewModel(array(
			'form' => $form
		));
	}

	/**
	 * Excluir um funcionario
	 * @return void
	 */
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);	
		if ($id == 0)
			throw new \Exception("Código Obrigatório");

		try{		
			$funcionario = $this->getEntityManager()->find('Portal\Entity\Funcionario', $id);
			$this->getEntityManager()->remove($funcionario);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/portal/funcionario');
	}

}