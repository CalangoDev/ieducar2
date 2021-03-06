<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 11/05/16
 * Time: 10:23
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\Predio;
use Escola\Form\Predio as PredioForm;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Controlador que gerencia os predios
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class PredioController extends ActionController
{
    /**
     * lista os predios da infraestrutura
     * @return ViewModel
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT p FROM Escola\Entity\Predio p');
        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    /**
     * save or edit record
     * @return \Zend\Http\Response|ViewModel
     */
    public function saveAction()
    {
        $entity = new Predio();
        $form = new PredioForm($this->getEntityManager());
        $request = $this->getRequest();
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $entity = $this->getEntityManager()->find('Escola\Entity\Predio', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($entity);

        if ($request->isPost()){
            $form->setInputFilter($entity->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /**
                 * persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($entity);
                    $this->flashMessenger()->addMessage(array('success' => 'Prédio Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Prédio Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/predio');
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

        $data = $this->getEntityManager()->find('Escola\Entity\Predio', $id);

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
          SELECT p FROM Escola\Entity\Predio p JOIN p.escola e JOIN e.juridica ej WHERE p.nome LIKE :query 
          OR p.descricao LIKE :query OR p.endereco LIKE :query OR ej.nome LIKE :query");
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
            $data = $this->getEntityManager()->find('Escola\Entity\Predio', $id);
            $this->getEntityManager()->remove($data);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/predio');
    }
}