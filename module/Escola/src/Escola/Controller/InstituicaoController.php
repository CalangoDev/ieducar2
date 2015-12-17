<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/12/15
 * Time: 09:58
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\Instituicao;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\Instituicao as InstituicaoForm;

/**
 * Controlador que gerencia as instituicoes
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class InstituicaoController extends ActionController
{
    /**
     * Lista as instituicoes cadastradas
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT i FROM Escola\Entity\Instituicao i');

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
        $instituicao = new Instituicao();
        $form = new InstituicaoForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $instituicao = $this->getEntityManager()->find('Escola\Entity\Instituicao', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($instituicao);

        if ($request->isPost()){
            $form->setInputFilter($instituicao->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($instituicao);
                    $this->flashMessenger()->addMessage(array('success' => 'Instituição Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Instituição Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/instituicao');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));

    }

    /**
     * Detalhes de uma instituicao
     */
    public function detalhesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $instituicao = $this->getEntityManager()->find('Escola\Entity\Instituicao', $id);

        if (!$instituicao)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $instituicao
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
        $query = $this->getEntityManager()->createQuery("SELECT i FROM Escola\Entity\Instituicao i WHERE i.nome LIKE :query	OR i.responsavel LIKE :query");
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
            $instituicao = $this->getEntityManager()->find('Escola\Entity\Instituicao', $id);
            $this->getEntityManager()->remove($instituicao);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/instituicao');
    }
}