<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 28/01/16
 * Time: 22:51
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\EscolaSerie;
use Escola\Form\EscolaSerie as EscolaSerieForm;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Controlador que gerencia as escolas series
 *
 * @category EscolaSerie
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class EscolaSerieController extends ActionController
{
    /**
     * Lista os dados
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT es FROM Escola\Entity\EscolaSerie es');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $escolaSerie = new EscolaSerie();
        $form = new EscolaSerieForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $escolaSerie = $this->getEntityManager()->find('Escola\Entity\EscolaSerie', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }
        $form->bind($escolaSerie);

        if ($request->isPost()){
            $form->setInputFilter($escolaSerie->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /**
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($escolaSerie);
                    $this->flashMessenger()->addMessage(array('success' => 'Escola Série Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Escola Série Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/escola-serie');
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

        $componente = $this->getEntityManager()->find('Escola\Entity\EscolaSerie', $id);

        if (!$componente)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $componente
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
        //SELECT es, e, s FROM Escola\Entity\EscolaSerie es JOIN es.escola e JOIN es.serie s WHERE e.nome LIKE :query OR s.nome LIKE :query"
        $query = $this->getEntityManager()->createQuery("
            SELECT es, e, s FROM Escola\Entity\EscolaSerie es JOIN es.escola e JOIN es.serie s WHERE s.nome LIKE :query 
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
            $escolaSerie = $this->getEntityManager()->find('Escola\Entity\EscolaSerie', $id);
            $this->getEntityManager()->remove($escolaSerie);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/escola-serie');
    }



}