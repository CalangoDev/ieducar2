<?php
namespace Usuario\Controller;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
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
	 * Mostra as pessoas fisicas cadastradas
	 * @return  void
	 */
	public function indexAction()
	{
		$query = $this->getEntityManager()->createQuery('SELECT j FROM Usuario\Entity\Juridica j');

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
		$juridica = new Juridica;
		$form = new JuridicaForm($this->getEntityManager());
		$request = $this->getRequest();

		$id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id > 0){
			$juridica = $this->getEntityManager()->find('Usuario\Entity\Juridica', $id);
			$form->get('submit')->setAttribute('value', 'Atualizar');
		}
		$form->bind($juridica);

		if ($request->isPost()){

			$form->setInputFilter($juridica->getInputFilter());
			$form->getInputFilter()->get('fantasia')->setRequired(true);
			$form->setData($request->getPost());			
			if ($form->isValid()){												
				/**
				 * Persistindo os dados
				 */
				$id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($juridica);
                    $this->flashMessenger()->addMessage(array('success' => 'Pessoa Jurídica Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Pessoa Jurídica alterada!'));
                }

				$this->getEntityManager()->flush();
				/**
				 * Redirecionando para lista de pessoas juridica
				 */
				return $this->redirect()->toUrl('/usuario/juridica');
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
        $query = $this->getEntityManager()->createQuery(
            "SELECT j FROM Usuario\Entity\Juridica j WHERE j.nome LIKE :query OR j.cnpj LIKE :query OR j.fantasia
        LIKE :query ");
        $query->setParameter('query', "%".$q."%");
        $dados = $query->getResult();

        $view = new ViewModel(array(
            'dados' => $dados
        ));
        $view->setTerminal(true);

        return $view;
    }


    /**
     * Detalhes de uma pessoa
     */
    public function detalhesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $juridica = $this->getEntityManager()->find('Usuario\Entity\Juridica', $id);

        if (!$juridica)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $juridica
        ));

        $view->setTerminal(true);

        return $view;
    }


	/**
	 * Excluir uma pessoa
     * @throws \Exception If registro não encontrado
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