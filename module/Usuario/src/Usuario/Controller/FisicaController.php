<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Fisica;
use Usuario\Form\Fisica as FisicaForm;
use Usuario\Entity\EnderecoExterno;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Doctrine\ORM\EntityManager;


use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;


/**
 * Controlador que gerencia pessoas fisicas
 * 
 * @category Usuario
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class FisicaController extends ActionController
{	
	/**
	 * Mostra as pessoas fisicas cadastradas
	 * @return  void
	 */
	public function indexAction()
	{
		// $dados = $this->getEntityManager()->getRepository('Usuario\Entity\Fisica')->findAll();
		$query = $this->getEntityManager()->createQuery('SELECT f FROM Usuario\Entity\Fisica f');

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

				f

			FROM

				Usuario\Entity\Fisica f
			
			WHERE

				f.nome LIKE :query 

			OR 

				f.cpf LIKE :query
		");
		$query->setParameter('query', "%".$q."%");		
		$dados = $query->getResult();		
		
		$view = new ViewModel(array(
			'dados' => $dados
		));
		$view->setTerminal(true);

		return $view;
	}

	public function saveAction()
	{
		$fisica = new Fisica;
		$enderecoExterno = new EnderecoExterno();
		// $fisica->setEnderecoExterno(new EnderecoExterno());
		$form = new FisicaForm($this->getEntityManager());
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);			
			if ($fisica->getDataNasc() == null){
				$date = new \DateTime($fisica->getDataNasc(), new \DateTimeZone('America/Sao_Paulo'));	
				$fisica->setDataNasc($date->format('d-m-Y'));
			} else {
				$fisica->setDataNasc($fisica->getDataNasc()->format('d-m-Y'));
			}			
			$form->get('submit')->setAttribute('value', 'Editar');
		}
		$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Usuario\Entity\Fisica'));

		// $hydrator = new DoctrineObject($this->getEntityManager());
		//$blogPost = new BlogPost();
		// $data = array(
		//     'title' => 'The best blog post in the world !',
		//     'user'  => array(
		//         'id' => 2 // Written by user 2
		//     )
		// );
		// $data = array(
		// 	'id' => 1,
		// 	'nome' => 'Gold'
		// );

		// $fisica = $hydrator->hydrate($data, $fisica);
		$form->bind($fisica);		

		if ($request->isPost()){
			
			/**
			 * [$pessoa->origem_gravacao origem da gravacao U = usuario]
			 * @var string
			 */
			$fisica->setOrigemGravacao("U");
			/**
			 * $id vindo do formulario se id > 0 é update/alteracao se nao é insercao
			 */
			$id  = (int) $this->params()->fromPost('id', 0);		

			// $enderecoExterno->setTipoLogradouro($this->params()->fromPost('tipoLogradouro'));
			$enderecoExterno->setSiglaUf($this->params()->fromPost('siglaUf'));
			$enderecoExterno->setOperacao(($id > 0) ? "A" : "I");
			$enderecoExterno->setOrigemGravacao("U");
			$enderecoExterno->setLogradouro($this->params()->fromPost('logradouro'));
			$enderecoExterno->setCidade($this->params()->fromPost('cidade'));
			$enderecoExterno->setIdsisCad(1);


			// $fisica->setEnderecoExterno($enderecoExterno);			
			
			// var_dump($fisica->getEnderecoExterno()->getInputFilter());
			// $fisica->getEnderecoExterno()->removeInputFilter('tipoLogradouro');
			
			$fisica->setOperacao(($id > 0) ? "A" : "I");
			$fisica->setIdsisCad(1);						
			$date = new \DateTime($this->params()->fromPost('dataNasc'), new \DateTimeZone('America/Sao_Paulo'));			

			$fisica->setDataNasc($date->format('Y-m-d'));			
			$request->getPost()->set('dataNasc', $fisica->getDataNasc());						
			
			$form->setInputFilter($fisica->getInputFilter());			

			/**
			 * Removendo filters de inputs nao recebidos pelo o formulario
			 */			
			$fisica->removeInputFilter('origemGravacao');
			$fisica->removeInputFilter('operacao');
			$fisica->removeInputFilter('idsisCad');
			$cpf  = $this->params()->fromPost('cpf', 0);	

			if ($cpf == '' || $cpf == 0){
				$fisica->removeInputFilter('cpf');				
				$form->remove('cpf');
				// unset($data['cpf']);				
			}	

			$dataNasc  = $this->params()->fromPost('dataNasc', 0);
			if ($dataNasc == '')
				$fisica->removeInputFilter('dataNasc');
			
			// $form->setData($data);	
						
			$form->setData($request->getPost());
			// var_dump($request->getPost());
						
			if ($form->isValid()){				
				// $data = $form->getData();				
				// unset($data['submit']);
				// $fisica->setData($data);
				/**
				 * Persistindo os dados
				 */				
				$id = (int) $this->params()->fromPost('id', 0);
				// var_dump($id);
				if ($id == 0){
					// $enderecoExterno = new EnderecoExterno();
					// $enderecoExterno->setPessoa($fisica);
					// $enderecoExterno->setLogradouro($this->params()->fromPost('logradouro'));
					// $enderecoExterno->setCidade($this->params()->fromPost('cidade'));
					// $enderecoExterno->setSiglaUf($this->params()->fromPost('uf'));
					// $enderecoExterno->setSiglaUf($this->params()->fromPost('uf'));
					// $enderecoExterno->setOrigemGravacao("U");
					// $enderecoExterno->setOperacao(($id > 0) ? "A" : "I");
					// $enderecoExterno->setIdsisCad(1);							
					// $enderecoExterno->setPessoa($fisica);						
					$this->getEntityManager()->persist($fisica);					
					$this->getEntityManager()->persist($enderecoExterno);	
					$fisica->setEnderecoExterno($enderecoExterno);
					// $pessoa = $this->getEntityManager()->find('Usuario\Entity\Pessoa', $fisica->getId());									
					$this->flashMessenger()->addSuccessMessage('Pessoa Salva');
				} else {
					$this->flashMessenger()->addSuccessMessage('Pessoa foi alterada!');
				}				
				$this->getEntityManager()->flush();					
				/**
				 * Redirecionando para lista de pessoas fisicas
				 */
				return $this->redirect()->toUrl('/usuario/fisica');
			} else {	
				// var_dump("NAO VALIDO");
				if ($this->params()->fromPost('dataNasc')){
					$date = new \DateTime($this->params()->fromPost('dataNasc'), new \DateTimeZone('America/Sao_Paulo'));
					$date = $date->format('d-m-Y');					
					$form->get('dataNasc')->setAttribute('value', $date);
				}

                /**
                 * Checar isso e ver o teste
                 * form invalido, onde nao tenha cpf
                 */
				if ($cpf == ''){
					$form->add(array(
					    'name' => 'cpf',
					    'attributes' => array(
                            'type' => 'text',
                            'class' => 'form-control cpf',
                            'pattern' => "\d{3}\.\d{3}\.\d{3}-\d{2}",
                            'title' => "Digite o CPF no formato nnn.nnn.nnn-nn"
					    ),
					    'options' => array(
						    'label' => 'CPF <small>nnn.nnn.nnn-nn</small>'
					    ),
				    ));
				}
			}
		}
		$id = (int) $this->params()->fromRoute('id', 0);
		if ($id >0){
			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);			
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
			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);		
			$this->getEntityManager()->remove($fisica);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/usuario/fisica');
	}
	
}