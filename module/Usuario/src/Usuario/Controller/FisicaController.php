<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Fisica;
use Usuario\Form\Fisica as FisicaForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

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
		$dados = $this->getEntityManager()->getRepository('Usuario\Entity\Fisica')->findAll();
		
		return new ViewModel(array(
			'dados' => $dados
		));
	}

	public function saveAction()
	{
		$fisica = new Fisica;
		$form = new FisicaForm();
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
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
			$form->setInputFilter($fisica->getInputFilter());

			/**
			 * Removendo filters de inputs nao recebidos pelo o formulario
			 */			
			$fisica->removeInputFilter('origemGravacao');
			$fisica->removeInputFilter('operacao');
			$fisica->removeInputFilter('idsisCad');			

			$form->setData($request->getPost());			
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