<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 12/05/16
 * Time: 20:59
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Entity\ComodoFuncao;
use Escola\Form\ComodoFuncao as ComodoFuncaoForm;

/**
 * Controlador que gerencia as funcoes do comodo
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class ComodoFuncaoController extends ActionController
{
    /**
     * lista as funcoes dos comodos
     * @return ViewModel
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT cf FROM Escola\Entity\ComodoFuncao cf');
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
        $entity = new ComodoFuncao();
        $form = new ComodoFuncaoForm($this->getEntityManager());
        $request = $this->getRequest();
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $entity = $this->getEntityManager()->find('Escola\Entity\ComodoFuncao', $id);
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
                    $this->flashMessenger()->addMessage(array('success' => 'Função Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Função Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/comodo-funcao');
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

        $data = $this->getEntityManager()->find('Escola\Entity\ComodoFuncao', $id);

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
          SELECT cf FROM Escola\Entity\ComodoFuncao cf  WHERE cf.nome LIKE :query OR cf.descricao LIKE :query");
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
            $data = $this->getEntityManager()->find('Escola\Entity\ComodoFuncao', $id);
            $this->getEntityManager()->remove($data);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/comodo-funcao');
    }

}