<?php
/**
 * Created by Vim.
 * User: eduardojunior
 * Date: 23/12/15
 * Time: 18:48
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\TipoEnsino;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\TipoEnsino as TipoEnsinoForm;

/**
 * Controlador que gerencia os tipos de ensino
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class TipoEnsinoController extends ActionController
{
    /**
     * Lista os tipos de ensino
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT te FROM Escola\Entity\TipoEnsino te');

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
        $tipoEnsino = new TipoEnsino();
        $form = new TipoEnsinoForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $tipoEnsino = $this->getEntityManager()->find('Escola\Entity\TipoEnsino', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($tipoEnsino);

        if ($request->isPost()){
            $form->setInputFilter($tipoEnsino->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($tipoEnsino);
                    $this->flashMessenger()->addMessage(array('success' => 'Tipo de Ensino Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Tipo de Ensino Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/tipo-ensino');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));

    }

    /**
     * Detalhes
     */
    public function detalhesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $tipoEnsino = $this->getEntityManager()->find('Escola\Entity\TipoEnsino', $id);

        if (!$tipoEnsino)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $tipoEnsino
        ));

        $view->setTerminal(true);

        return $view;
    }

    /**
     * Busca
     */
    public function buscaAction()
    {
        $q = (string) $this->params()->fromPost('q');
        $query = $this->getEntityManager()->createQuery("
          SELECT i FROM Escola\Entity\TipoEnsino te WHERE te.nome LIKE :query");
        $query->setParameter('query', "%".$q."%");
        $dados = $query->getResult();

        $view = new ViewModel(array(
            'dados' => $dados
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
            $tipoEnsino = $this->getEntityManager()->find('Escola\Entity\TipoEnsino', $id);
            $this->getEntityManager()->remove($tipoEnsino);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/tipo-ensino');
    }
}
