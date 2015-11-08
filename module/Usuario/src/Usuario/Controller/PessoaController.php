<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Pessoa;
use Usuario\Form\Pessoa as PessoaForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

use Doctrine\ORM\EntityManager;

/**
 * Controlador que gerencia pessoas
 * 
 * @category Usuario
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class PessoaController extends ActionController
{

	/**
	 * Mostra os usuarios cadastrados
	 * @return void
	 */
	public function indexAction()
	{
		$dados = $this->getEntityManager()->getRepository('Usuario\Entity\Pessoa')->findAll();
		
		return new ViewModel(array('dados' => $dados));
	}

	public function saveAction()
	{
		$pessoa = new Pessoa;
		$form = new PessoaForm();
		$request = $this->getRequest();	
		
		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			// $album = $this->getEntityManager()->find('Album\Entity\Album', $id);
			// $form = new AlbumForm();
	  		//       $form->setBindOnValidate(false);
			//       $form->bind($album);
			//       $form->get('submit')->setAttribute('label', 'Edit');
	        $pessoa = $this->getEntityManager()->find('Usuario\Entity\Pessoa', $id);	        
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(),'Usuario\Entity\Pessoa'));
		$form->bind($pessoa);

		if ($request->isPost()) {
			/**
			 * $id vindo do formulario se id > 0 é update/alteracao se nao é insercao
			 */
			$id  = (int) $this->params()->fromPost('id', 0);
			/**
			 * @todo PEGAR O ID DA PESSOA LOGADO NA SESSION 
			 */
			$form->setInputFilter($pessoa->getInputFilter());
			/**
			 * Removento filters de inputs nao recebidos pelo o formulario
			 */
			//$pessoa->removeInputFilter('dataRev');
			
			if ($this->params()->fromPost('email') == null)				
				$pessoa->removeInputFilter('email');			

			$form->setData($request->getPost());
			if ($form->isValid()){						
				//$data = $form->getData();				
				//unset($data['submit']);
				// /**
				//  * Remove input email se ele vinher do formulario vazio
				//  */
				// if (empty($data['email']))
				// 	unset($data['email']);

				// if (empty($data['url']))
				// 	unset($data['url']);
				//$pessoa->setData($data);
				/**
				 * Persistindo os dados
				 */
				//if (!$id)				
				$this->getEntityManager()->persist($pessoa);
				$this->getEntityManager()->flush();
				$this->flashMessenger()->addSuccessMessage('Pessoa Salva');
				/**
				 * Redirecionando pra lista de pessoas
				 */
				return $this->redirect()->toUrl('/usuario/pessoa');
			} 
		}				
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id > 0){		
			$pessoa = $this->getEntityManager()->find('Usuario\Entity\Pessoa', $id);				
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
			$pessoa = $this->getEntityManager()->find('Usuario\Entity\Pessoa', $id);		
			$this->getEntityManager()->remove($pessoa);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/usuario/pessoa');
	}
	
}