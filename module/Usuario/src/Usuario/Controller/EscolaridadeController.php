<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Escolaridade;
use Usuario\Form\Escolaridade as EscolaridadeForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

/**
 * Controlador que gerencia a Escolaridade
 * 
 * @category Usuario
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class EscolaridadeController extends ActionController
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
		$dados = $this->getEntityManager()->getRepository('Usuario\Entity\Escolaridade')->findAll();

		return new ViewModel(array(
			'dados' => $dados
		));
	}

	public function saveAction()
	{
		$escolaridade = new Escolaridade;
		$form = new EscolaridadeForm();
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$escolaridade = $this->getEntityManager()->find('Usuario\Entity\Escolaridade', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Usuario\Entity\Escolaridade'));
		$form->bind($escolaridade);

		if ($request->isPost()){

			$form->setInputFilter($escolaridade->getInputFilter());			
			$form->setData($request->getPost());			
			if ($form->isValid()){								
				/**
				 * Persistindo os dados
				 */
				$this->getEntityManager()->persist($escolaridade);
				$this->getEntityManager()->flush();
				$this->flashMessenger()->addSuccessMessage('Nova Escolaridade Salva');
				/**
				 * Redirecionando para lista de escolaridade
				 */
				return $this->redirect()->toUrl('/usuario/escolaridade');
			}
		}
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){
			$escolaridade = $this->getEntityManager()->find('Usuario\Entity\Escolaridade', $id);			
			$form->get('submit')->setAttribute('value', 'Edit');
		}

		return new ViewModel(array(
			'form' => $form
		));
	}

	/**
	 * Excluir uma escolaridade
	 * @return void
	 */
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);	
		if ($id == 0)
			throw new \Exception("Código Obrigatório");

		try{		
			$escolaridade = $this->getEntityManager()->find('Usuario\Entity\Escolaridade', $id);
			$this->getEntityManager()->remove($escolaridade);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/usuario/escolaridade');
	}

}