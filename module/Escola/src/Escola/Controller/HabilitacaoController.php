<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/12/15
 * Time: 14:16
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\Habilitacao;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\Habilitacao as HabilitacaoForm;

/**
 * Controlador que gerencia as habilitações
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class HabilitacaoController extends ActionController
{
    /**
     * Lista as habilitacoes
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT h FROM Escola\Entity\Habilitacao h');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $habilitacao = new Habilitacao();
        $form = new HabilitacaoForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $habilitacao = $this->getEntityManager()->find('Escola\Entity\Habilitacao', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($habilitacao);

        if ($request->isPost()){
            $form->setInputFilter($habilitacao->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($habilitacao);
                    $this->flashMessenger()->addMessage(array('success' => 'Habilitação Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Habilitação Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/habilitacao');
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

        $habilitacao = $this->getEntityManager()->find('Escola\Entity\Habilitacao', $id);

        if (!$habilitacao)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $habilitacao
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
          SELECT h FROM Escola\Entity\Habilitacao h WHERE h.nome LIKE :query OR h.descricao LIKE :query ");
        $query->setParameter('query', "%".$q."%");
        $dados = $query->getResult();

        $view = new ViewModel(array(
            'dados' => $dados
        ));
        $view->setTerminal(true);

        return $view;
    }


    /**
     * Excluir uma habilitacao
     * @throws \Exception If registro não encontrado
     * @return void
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        try{
            $habilitacao = $this->getEntityManager()->find('Escola\Entity\Habilitacao', $id);
            $this->getEntityManager()->remove($habilitacao);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/habilitacao');
    }
}
