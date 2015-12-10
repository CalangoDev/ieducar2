<?php
namespace Drh\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Drh\Entity\Setor;
use Drh\Form\Setor as SetorForm;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\Paginator\Paginator;


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
	 * Mostra as escolaridades cadastradas
	 * @return void 
	 */
	public function indexAction()
	{
		$query = $this->getEntityManager()->createQuery('SELECT s FROM Drh\Entity\Setor s');

		$dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
		);

		$dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

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
			$form->get('submit')->setAttribute('value', 'Atualizar');
		}
		$form->bind($setor);

		if ($request->isPost()){			

            $form->setInputFilter($setor->getInputFilter());
			// $setor->removeInputFilter('sigla_setor');
			$form->setData($request->getPost());				
			if ($form->isValid()){				
				/**
				 * Persistindo os dados
				 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($setor);
                    $this->flashMessenger()->addSuccessMessage(array("success" => "Novo Setor salvo"));
                } else {
                    $this->flashMessenger()->addSuccessMessage(array("success" => "Setor alterado"));
                }
				$this->getEntityManager()->flush();
				/**
				 * Redirecionando para lista de setores
				 */
				return $this->redirect()->toUrl('/drh/setor');
			} 
		} 

		return new ViewModel(array(
			'form' => $form
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

				s

			FROM

				Drh\Entity\Setor s

			WHERE

				s.nome LIKE :query

			OR

				s.sigla LIKE :query
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
     * Detalhes de um setor
     */
    public function detalhesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $setor = $this->getEntityManager()->find('Drh\Entity\Setor', $id);

        if (!$setor)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $setor
        ));

        $view->setTerminal(true);

        return $view;
    }

	/**
	 * Excluir um setor
	 * @return void
     * @throws \Exception If Registro não encontrado
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