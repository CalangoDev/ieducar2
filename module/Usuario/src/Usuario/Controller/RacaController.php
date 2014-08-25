<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Raca;
use Usuario\Form\Raca as RacaForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Zend\Authentication\AuthenticationService;


/**
 * Controlador que gerencia a raça
 * 
 * @category Usuario
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class RacaController extends ActionController
{
	/**
	 * Mostra as racas cadastradas
	 * @return void 
	 */
	public function indexAction()
	{
		$query = $this->getEntityManager()->createQuery('SELECT r FROM Usuario\Entity\Raca r WHERE r.ativo = :ativo');
		$query->setParameter('ativo', true);	

		$dados = new Paginator(
			new DoctrinePaginator(new ORMPaginator($query))
		);		
		
		$dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);
		
		return new ViewModel(array(
			'dados' => $dados
		));		
	}

	/**
	 * Busca
	 */
	public function buscaAction()
	{
		$q = (string) $this->params()->fromPost('q');		
		$query = $this->getEntityManager()->createQuery("
			SELECT

				r

			FROM

				Usuario\Entity\Raca r
			
			WHERE

				r.nome LIKE :query 

			AND 
				r.ativo = :ativo				
		");
		$query->setParameter('query', "%".$q."%");		
		$query->setParameter('ativo', true);
		$dados = $query->getResult();		
		
		$view = new ViewModel(array(
			'dados' => $dados
		));
		$view->setTerminal(true);

		return $view;
	}

	public function saveAction()
	{
		$raca = new Raca;
		$form = new RacaForm();
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$raca = $this->getEntityManager()->find('Usuario\Entity\Raca', $id);
			$form->get('submit')->setAttribute('value', 'Edit');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Usuario\Entity\Raca'));
		$form->bind($raca);

		if ($request->isPost()){

			$form->setInputFilter($raca->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()){
				/**
				 * Persistindo os dados
				 */
				$id = (int) $this->params()->fromPost('id', 0);
				if ($id === 0){
					/**
					 * Verifica se esta autenticado, pega a pessoa que esta logado e seta a pessoa logada como a responsavel
					 * pelo o cadastro					 
					 */
					$authService = new AuthenticationService();
					if ($authService->hasIdentity()) {					
						$user = $this->identity();
						$raca->setPessoaCad($user->getRefCodPessoaFj());
					}
					$this->getEntityManager()->persist($raca);
					$this->flashMessenger()->addSuccessMessage('Raça Salva!');
				} else {
					$this->flashMessenger()->addSuccessMessage('Raça foi alterada');
				}

				$this->getEntityManager()->flush();
				/**
				 * Redirecionando para lista de racas
				 */				
				return $this->redirect()->toUrl('/usuario/raca');
			}
		}

		$id = (int) $this->params()->fromRoute('id', 0);
		
		if ($id >0){

			$raca = $this->getEntityManager()->find('Usuario\Entity\Raca', $id);
			$form->get('submit')->setAttribute('value', 'Edit');

		}

		return new ViewModel(array(
			'form' => $form
		));
	}

	/**
	 * Excluir uma raca
	 * @return  void 
	 */
	public function deleteAction()
	{
		//update ativo para false e pessoa que excluiu e datade exlusao
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id == 0)
			throw new \Exception("Código Obrigatório");
		try{

			$raca = $this->getEntityManager()->find('Usuario\Entity\Raca', $id);			
			
			if ($raca){				
				
				$raca->setAtivo(false);
				$raca->setDataExclusao(new \DateTime());				
				/**
				 * verificando se esta logado, se logado pega o id do usuario e seta como a pessoa que 
				 * excluiu o registro
				 */
				$authService = new AuthenticationService();
				if ($authService->hasIdentity()) {
					$user = $this->identity();
					$raca->setPessoaExclu($user->getRefCodPessoaFj());			
				} 
					
				$this->getEntityManager()->flush();
				
			} else {

				throw new \Exception("Registro não encontrado");

			}

		} catch (\Exception $e){
			throw new \Exception("Registro não encontrado");
		}

		return $this->redirect()->toUrl('/usuario/raca');
	}
}