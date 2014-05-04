<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Juridica;
use Usuario\Form\Juridica as JuridicaForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

/**
 * Controlador que gerencia pessoas juridicas
 * 
 * @category Usuario
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class JuridicaController extends ActionController
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
		$dados = $this->getEntityManager()->getRepository('Usuario\Entity\Juridica')->findAll();
		
		return new ViewModel(array(
			'dados' => $dados
		));
	}

	public function saveAction()
	{
		$juridica = new Juridica;
		$form = new JuridicaForm();
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$juridica = $this->getEntityManager()->find('Usuario\Entity\Juridica', $id);
			$form->get('submit')->setAttribute('value', 'Edit');			
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Usuario\Entity\Juridica'));
		$form->bind($juridica);

		if ($request->isPost()){

			/**
			 * [$pessoa->origem_gravacao origem da gravacao U = usuario]
			 * @var string
			 */
			$juridica->setOrigemGravacao("U");
			/**
			 * $id vindo do formulario se id > 0 é update/alteracao se nao é insercao
			 */
			$id  = (int) $this->params()->fromPost('id', 0);			
			$juridica->setOperacao(($id > 0) ? "A" : "I");						

			$juridica->setIdsisCad(1);
			$form->setInputFilter($juridica->getInputFilter());

			/**
			 * Removendo filters de inputs nao recebidos pelo o formulario
			 */			
			$juridica->removeInputFilter('origemGravacao');
			$juridica->removeInputFilter('operacao');
			$juridica->removeInputFilter('idsisCad');

			$form->setData($request->getPost());			
			if ($form->isValid()){												
				/**
				 * Persistindo os dados
				 */
				$this->getEntityManager()->persist($juridica);
				$this->getEntityManager()->flush();
				$this->flashMessenger()->addSuccessMessage('Pessoa Salva');
				/**
				 * Redirecionando para lista de pessoas juridica
				 */
				return $this->redirect()->toUrl('/usuario/juridica');
			}
		}

		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){
			$juridica = $this->getEntityManager()->find('Usuario\Entity\Juridica', $id);
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
			$juridica = $this->getEntityManager()->find('Usuario\Entity\Juridica', $id);
			$this->getEntityManager()->remove($juridica);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/usuario/juridica');
	}

}