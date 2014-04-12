<?php
namespace Drh\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Drh\Entity\Setor;
use Drh\Form\Setor as SetorForm;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;



/**
 * Controlador que gerencia o drh
 * 
 * @category Drh
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class SetorController extends ActionController
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
	 * Mostra as escolaridades cadastradas
	 * @return void 
	 */
	public function indexAction()
	{
		$dados = $this->getEntityManager()->getRepository('Drh\Entity\Setor')->findAll();

		return new ViewModel(array(
			'dados' => $dados
		));
	}

	public function saveAction()
	{
		$setor = new Setor;
		$form = new SetorForm($this->getEntityManager());
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$setor = $this->getEntityManager()->find('Drh\Entity\Setor', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Drh\Entity\Setor'));
		$form->bind($setor);

		if ($request->isPost()){			
			$form->setInputFilter($setor->getInputFilter());			
			$form->setData($request->getPost());				
			if ($form->isValid()){				
				/**
				 * Persistindo os dados
				 */				
				$this->getEntityManager()->persist($setor);
				$this->getEntityManager()->flush();
				$this->flashMessenger()->addSuccessMessage('Novo Setor salvo');
				/**
				 * Redirecionando para lista de setores
				 */
				return $this->redirect()->toUrl('/drh/setor');
			}			
		}

		/**
		 * @todo refatorar a logica do get id, estou praticamente duplicando codigo 		 
		 */
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){
			$setor = $this->getEntityManager()->find('Drh\Entity\Setor', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}

		return new ViewModel(array(
			'form' => $form
		));

	}

	/**
	 * Excluir um setor
	 * @return void
	 */
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);	
		if ($id == 0)
			throw new \Exception("Código Obrigatório");

		try{		
			$setor = $this->getEntityManager()->find('Drh\Entity\Setor', $id);
			$this->getEntityManager()->remove($setor);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/drh/setor');
	}
}