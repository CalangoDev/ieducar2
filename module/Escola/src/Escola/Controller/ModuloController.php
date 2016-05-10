<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/05/16
 * Time: 15:15
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\Modulo;
use Escola\Form\Modulo as ModuloForm;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Controlador que gerencia os modulos
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class ModuloController extends ActionController
{
    /**
     * Lista os niveis de ensino
     * @return void
     */
    public function indexAction()
    {

        $query = $this->getEntityManager()->createQuery('SELECT m  FROM Escola\Entity\Modulo m');
        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        //$this->layout()->setTemplate('layout/index');

        return new ViewModel(array('dados' => $dados));
    }


    public function saveAction()
    {
        $modulo = new Modulo();
        $form = new ModuloForm($this->getEntityManager());
        $request = $this->getRequest();
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $modulo = $this->getEntityManager()->find('Escola\Entity\Modulo', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($modulo);

        if ($request->isPost()){
            $form->setInputFilter($modulo->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /**
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($modulo);
                    $this->flashMessenger()->addMessage(array('success' => 'Módulo Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Módulo Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/modulo');
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

        $data = $this->getEntityManager()->find('Escola\Entity\Modulo', $id);

        if (!$data)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $data
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
          SELECT m FROM Escola\Entity\Modulo m WHERE m.nome LIKE :query OR m.descricao LIKE :query ");
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
            $data = $this->getEntityManager()->find('Escola\Entity\Modulo', $id);
            $this->getEntityManager()->remove($data);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/modulo');
    }

}