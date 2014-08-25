<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Ocupacao;
use Usuario\Form\Ocupacao as OcupacaoForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

/**
 * Controlador que gerencia a ocupacao
 * 
 * @category Usuario
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class OcupacaoController extends ActionController
{

	/**
	 * Mostra as ocupacoes
	 * @return void 
	 */
	public function indexAction()
	{
		$dados = $this->getEntityManager()->getRepository('Usuario\Entity\Ocupacao')->findAll();
				
		return new ViewModel(array(
			'dados' => $dados
		));
	}

	public function saveAction()
	{
		$ocupacao = new Ocupacao;
		$form = new OcupacaoForm();
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$ocupacao = $this->getEntityManager()->find('Usuario\Entity\Ocupacao', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Usuario\Entity\Ocupacao'));
		$form->bind($ocupacao);

		if ($request->isPost()){

			$form->setInputFilter($ocupacao->getInputFilter());	
			$form->setData($request->getPost());			
			if ($form->isValid()){								
				/**
				 * Persistindo os dados
				 */
				$this->getEntityManager()->persist($ocupacao);
				$this->getEntityManager()->flush();
				$this->flashMessenger()->addSuccessMessage('Nova Ocupação Salva');
				/**
				 * Redirecionando para lista de ocupacoes
				 */
				return $this->redirect()->toUrl('/usuario/ocupacao');
			}
		}
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){
			$ocupacao = $this->getEntityManager()->find('Usuario\Entity\Ocupacao', $id);
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
			$ocupacao = $this->getEntityManager()->find('Usuario\Entity\Ocupacao', $id);
			$this->getEntityManager()->remove($ocupacao);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/usuario/ocupacao');
	}

}