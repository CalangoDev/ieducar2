<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 10/01/16
 * Time: 23:42
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\RegraAvaliacao;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\RegraAvaliacao as RegraAvaliacaoForm;

/**
 * Controlador que gerencia as regras de avaliações
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class RegraAvaliacaoController extends ActionController
{
    /**
     * Lista as regras
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT r FROM Escola\Entity\RegraAvaliacao r');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $regra = new RegraAvaliacao();
        $form = new RegraAvaliacaoForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $regra = $this->getEntityManager()->find('Escola\Entity\RegraAvaliacao', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($regra);
        if ($request->isPost()){

            $form->setInputFilter($regra->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($regra);
                    $this->flashMessenger()->addMessage(array('success' => 'Regra de Avaliação Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Regra de Avaliação Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/regra-avaliacao');
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

        $regra = $this->getEntityManager()->find('Escola\Entity\RegraAvaliacao', $id);

        if (!$regra)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $regra
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
        $query = $this->getEntityManager()->createQuery(
            "SELECT r FROM Escola\Entity\RegraAvaliacao r WHERE r.nome LIKE :query"
        );
        $query->setParameter('query', "%".$q."%");
        $dados = $query->getResult();

        $view = new ViewModel(array(
            'dados' => $dados
        ));

        $view->setTerminal(true);

        return $view;
    }


    /**
     * Excluir um registro
     * @throws \Exception If registro não encontrado
     * @return void
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        try{
            $regra = $this->getEntityManager()->find('Escola\Entity\RegraAvaliacao', $id);
            $this->getEntityManager()->remove($regra);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/regra-avaliacao');
    }
}