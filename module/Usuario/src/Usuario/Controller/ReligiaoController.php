<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Religiao;
use Usuario\Form\Religiao as ReligiaoForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

/**
 * Controlador que gerencia as religioes
 * 
 * @category Usuario
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class ReligiaoController extends ActionController
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
	 * Mostra as religioes cadastradas
	 * @return void 
	 */
	public function indexAction()
	{
		$dados = $this->getEntityManager()->getRepository('Usuario\Entity\Religiao')->findAll();

		return new ViewModel(array(
			'dados' => $dados
		));
	}

	public function saveAction()
	{
		$religiao = new Religiao;
		$form = new ReligiaoForm();
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){			
			$religiao = $this->getEntityManager()->find('Usuario\Entity\Religiao', $id);			
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Usuario\Entity\Religiao'));
		$form->bind($religiao);

		if ($request->isPost()){			
			$form->setInputFilter($religiao->getInputFilter());
			/**
			 * Removendo filters de inputs nao recebidos pelo o formulario
			 */			
			$religiao->removeInputFilter('data_cadastro');
			$form->setData($request->getPost());			
			if ($form->isValid()){
				/**
				 * Persistindo os dados
				 */
				$this->getEntityManager()->persist($religiao);
				$this->getEntityManager()->flush();
				$this->flashMessenger()->addSuccessMessage('Nova Religião Salva');
				/**
				 * Redirecionando para lista de escolaridade
				 */
				return $this->redirect()->toUrl('/usuario/religiao');
			}
		}
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){
			$religiao = $this->getEntityManager()->find('Usuario\Entity\Religiao', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}

		return new ViewModel(array(
			'form' => $form
		));
	}

	/**
	 * Excluir uma religiao
	 * @return void
	 */
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);	
		if ($id == 0)
			throw new \Exception("Código Obrigatório");

		try{		
			$religiao = $this->getEntityManager()->find('Usuario\Entity\Religiao', $id);
			$this->getEntityManager()->remove($religiao);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/usuario/religiao');
	}

}