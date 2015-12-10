<?php
namespace Drh\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Drh\Entity\Funcionario;
use Drh\Form\Funcionario as FuncionarioForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\EntityManager;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
// use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Controlador que gerencia os funcionarios
 * 
 * @category Portal
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class FuncionarioController extends ActionController
{
	/**
	 * Mostra os funcionarios cadastrados
	 * @return void 
	 */
	public function indexAction()
	{
		// $dados = $this->getEntityManager()->getRepository('Portal\Entity\Funcionario')->findAll();
		$query = $this->getEntityManager()->createQuery('SELECT f FROM Drh\Entity\Funcionario f');

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

				Drh\Entity\Funcionario f

			LEFT JOIN

				f.fisica fisica

			WHERE

				f.matricula LIKE :query 

			OR 

				fisica.nome LIKE :query
		");
		$query->setParameter('query', "%".$q."%");		
		$dados = $query->getResult();		
		
		$view = new ViewModel(array(
			'dados' => $dados
		));
		$view->setTerminal(true);

		return $view;
	}

	/**
	 * Inseri um novo funcionario no banco, depende que exista uma pessoa fisica no sistema para fazer a inserção
	 * parametro deve ser passado pela url
	 */
	public function saveAction()
	{
		$funcionario = new Funcionario;
		$form = new FuncionarioForm($this->getEntityManager());
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');

		if ($id > 0){			
			$funcionario = $this->getEntityManager()->find('Drh\Entity\Funcionario', $id);
			$form->get('submit')->setAttribute('value', 'Atualizar');
		}
		$form->bind($funcionario);

		if ($request->isPost()){

            // add filter unique to input matricula
			$matriculaInput = $funcionario->getInputFilter()->get('matricula');
			$noObjectExistsValidator = new NoObjectExistsValidator(array(
                'object_repository' => $this->getEntityManager()->getRepository('Drh\Entity\Funcionario'),
                'fields' => 'matricula',
                'messages' => array(
                    'objectFound' => 'Nos desculpe, mas essa matrícula já existe no sistema !'
                )
			));

			$matriculaInput->getValidatorChain()->attach($noObjectExistsValidator);

			$form->setInputFilter($funcionario->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()){

                /**
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($funcionario);
                    $this->flashMessenger()->addMessage(array("success" => "Funcionário Salvo!"));
                } else {
                    $this->flashMessenger()->addMessage(array("success" => 'Funcionário Alterado!'));
                }

                $this->getEntityManager()->flush();

                /**
                 * Redirecionando para a lista de funcionarios
                 */
				return $this->redirect()->toUrl('/drh/funcionario');
			}

		}

		return new ViewModel(array(
			'form' => $form
		));
	}


    /**
     * Detalhes de uma pessoa
     */
    public function detalhesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $funcionario = $this->getEntityManager()->find('Drh\Entity\Funcionario', $id);

        if (!$funcionario)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $funcionario
        ));

        $view->setTerminal(true);

        return $view;
    }

	/**
	 * Excluir um funcionario
	 * @return void
     * @throws \Exception If Registro não encontrado
	 */
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);	
		if ($id == 0)
			throw new \Exception("Código Obrigatório");

		try{		
			$funcionario = $this->getEntityManager()->find('Drh\Entity\Funcionario', $id);
			$this->getEntityManager()->remove($funcionario);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
		
		return $this->redirect()->toUrl('/drh/funcionario');
	}

}