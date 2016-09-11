<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 10/09/16
 * Time: 11:47
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Escola\Entity\TipoTurma;
use Escola\Form\TipoTurma as TipoTurmaForm;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\View\Model\ViewModel;


/**
 * Controlador que gerencia os tipos de turma
 * @category Escola
 * @package Escola\Controller
 * @autor Eduardo Junior <ej@calangodev.com.br>
 */
class TipoTurmaController extends ActionController
{
    /**
     * Lista os tipos de turma
     * @return ViewModel
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery("SELECT tt FROM Escola\Entity\TipoTurma tt");
        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel([
            'dados' => $dados
            ]
        );
    }

    public function saveAction()
    {
        $entity = new TipoTurma();
        $form = new TipoTurmaForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $entity = $this->getEntityManager()->find('Escola\Entity\TipoTurma', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($entity);

        if ($request->isPost()){
            $form->setInputFilter($entity->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($entity);
                    $this->flashMessenger()->addMessage(array('success' => 'Tipo de Turma Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Tipo de Turma Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/tipo-turma');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function buscaAction()
    {
        $q = (string) $this->params()->fromPost('q');
        $query = $this->getEntityManager()->createQuery("
          SELECT tt FROM Escola\Entity\TipoTurma tt WHERE tt.nome LIKE :query
        ");
        $query->setParameter('query', "%".$q."%");
        $dados = $query->getResult();

        $view = new ViewModel(array(
            'dados' => $dados
        ));
        $view->setTerminal(true);

        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        try {
            $entity = $this->getEntityManager()->find('Escola\Entity\TipoTurma', $id);
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        } catch (\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage([
            "success" => "Registro Removido com sucesso!"
        ]);
        return $this->redirect()->toUrl('/escola/tipo-turma');
    }

    public function detalhesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $entity = $this->getEntityManager()->find('Escola\Entity\TipoTurma', $id);

        if (!$entity)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel([
           'data' => $entity
        ]);

        $view->setTerminal(true);

        return $view;
    }
}