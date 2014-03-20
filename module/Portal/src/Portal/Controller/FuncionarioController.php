<?php
namespace Portal\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Portal\Entity\Funcionario;
use Portal\Form\Funcionario as FuncionarioForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

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
		$dados = $this->getEntityManager()->getRepository('Portal\Entity\Funcionario')->findAll();

		return new ViewModel(array(
			'dados' => $dados
		));
	}

	/**
	 * Inseri um novo funcionario no banco, depende que exista uma pessoa fisica no sistema para fazer a inserção
	 * parametro deve ser passado pela url
	 */
	public function saveAction()
	{
		$funcionario = new Funcionario;
		$form = new FuncionarioForm($this->getEntityManager());
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$funcionario = $this->getEntityManager()->find('Portal\Entity\Funcionario', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Portal\Entity\Funcionario'));
		$form->bind($funcionario);

		if ($request->isPost()){

			$form->setInputFilter($funcionario->getInputFilter());			
			$form->setData($request->getPost());			
			if ($form->isValid()){								
				/**
				 * Persistindo os dados
				 */
				$this->getEntityManager()->persist($funcionario);
				$this->getEntityManager()->flush();
				$this->flashMessenger()->addSuccessMessage('Novo Funcionario Inserido');
				 
				return $this->redirect()->toUrl('/portal/funcionario');
			}
		}
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){
			$funcionario = $this->getEntityManager()->find('Portal\Entity\Funcionario', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}

		return new ViewModel(array(
			'form' => $form
		));
	}

	// /**
	//  * Excluir uma escolaridade
	//  * @return void
	//  */
	// public function deleteAction()
	// {
	// 	$id = (int) $this->params()->fromRoute('id', 0);	
	// 	if ($id == 0)
	// 		throw new \Exception("Código Obrigatório");

	// 	try{		
	// 		$escolaridade = $this->getEntityManager()->find('Usuario\Entity\Escolaridade', $id);
	// 		$this->getEntityManager()->remove($escolaridade);
	// 		$this->getEntityManager()->flush();			
	// 	} catch(\Exception $e){
	// 		throw new \Exception("Registro não encontrado");			
	// 	}
		
	// 	return $this->redirect()->toUrl('/usuario/escolaridade');
	// }

}