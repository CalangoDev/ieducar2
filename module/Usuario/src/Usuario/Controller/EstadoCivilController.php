<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\EstadoCivil;
use Usuario\Form\EstadoCivil as EstadoCivilForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

/**
 * Controlador que gerencia o estado civil
 * 
 * @category Usuario
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class EstadoCivilController extends ActionController
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
	 * Mostra os estados civis cadastrados
	 * @return void 
	 */
	public function indexAction()
	{
		$dados = $this->getEntityManager()->getRepository('Usuario\Entity\EstadoCivil')->findAll();

		return new ViewModel(array(
			'dados' => $dados
		));
	}

	public function saveAction()
	{
		$ec = new EstadoCivil;
		$form = new EstadoCivilForm();
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$ec = $this->getEntityManager()->find('Usuario\Entity\EstadoCivil', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Usuario\Entity\EstadoCivil'));
		$form->bind($ec);

		if ($request->isPost()){

			$form->setInputFilter($ec->getInputFilter());			
			$form->setData($request->getPost());			
			if ($form->isValid()){								
				/**
				 * Persistindo os dados
				 */
				$this->getEntityManager()->persist($ec);
				$this->getEntityManager()->flush();
				$this->flashMessenger()->addSuccessMessage('Novo Estado Cívil Salvo');
				/**
				 * Redirecionando para lista de estados civis
				 */
				return $this->redirect()->toUrl('/usuario/estadocivil');
			}
		}
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){
			$ec = $this->getEntityManager()->find('Usuario\Entity\EstadoCivil', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}

		return new ViewModel(array(
			'form' => $form
		));
	}

	/**
	 * Excluir um estado civil
	 * @return void
	 */
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);	
		if ($id == 0)
			throw new \Exception("Código Obrigatório");

		try{		
			$ec = $this->getEntityManager()->find('Usuario\Entity\EstadoCivil', $id);
			$this->getEntityManager()->remove($ec);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/usuario/estadocivil');
	}

}