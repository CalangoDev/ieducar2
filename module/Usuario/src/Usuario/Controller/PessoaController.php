<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Pessoa;
use Usuario\Form\Pessoa as PessoaForm;

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
	 *	@var Doctrine\ORM\EntityManager	
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
	 * Mostra os usuarios cadastrados
	 * @return void
	 */
	public function indexAction()
	{
		$pessoas = $this->getEntityManager()->getRepository('Usuario\Entity\Pessoa')->findAll();
		//var_dump($pessoas);
		return new ViewModel(array(
			'pessoas' => $pessoas
		));
	}

	public function saveAction()
	{
		$form = new PessoaForm();
		$request = $this->getRequest();
		if ($request->isPost()) {			
			$pessoa = new Pessoa;
			/**
			 * [$pessoa->origem_gravacao origem da gravacao U = usuario]
			 * @var string
			 */
			$pessoa->origem_gravacao = "U";
			/**
			 * $id vindo do formulario se id > 0 é update/alteracao se nao é insercao
			 */
			$id  = (int) $this->params()->fromPost('id', 0);			
			$pessoa->operacao = ($id > 0) ? "A" : "I";						
			/**
			 * @todo PEGAR O ID DA PESSOA LOGADO NA SESSION 
			 */
			$pessoa->idpes_rev = 1;
			$pessoa->idpes_cad = 1;			
			$pessoa->idsis_cad = 1;						
			$form->setInputFilter($pessoa->getInputFilter());
			/**
			 * Removento filters de inputs nao recebidos pelo o formulario
			 */
			$pessoa->removeInputFilter('idpes_cad');			
			$pessoa->removeInputFilter('idpes_rev');
			$pessoa->removeInputFilter('operacao');
			$pessoa->removeInputFilter('origem_gravacao');
			$pessoa->removeInputFilter('idsis_rev');
			$pessoa->removeInputFilter('idsis_cad');			
			$pessoa->removeInputFilter('data_rev');			
			$form->setData($request->getPost());			
			if ($form->isValid()){				
				$data = $form->getData();
				unset($data['submit']);
				/**
				 * Remove input email se ele vinher do formulario vazio
				 */
				if (empty($data['email']))
					unset($data['email']);

				if (empty($data['url']))
					unset($data['url']);
				$pessoa->setData($data);
				/**
				 * Persistindo os dados
				 */
				$this->getEntityManager()->persist($pessoa);
				$this->getEntityManager()->flush();
				/**
				 * Redirecionando pra lista de pessoas
				 */
				return $this->redirect()->toUrl('/usuario/pessoa');
			} 
		}
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id > 0){
			$pessoa = $this->getEntityManager()->find('Usuario\Entity\Pessoa', $id);			
			$form->bind($pessoa);
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