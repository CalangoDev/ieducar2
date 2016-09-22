<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/09/16
 * Time: 15:29
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\View\Model\ViewModel;
use Escola\Entity\Turma;
use Escola\Form\Turma as TurmaForm;


/**
 * Class TurmaController
 * @category Escola
 * @package Escola\Controller
 * @autor Eduardo Junior <ej@calangodev.com.br>
 */
class TurmaController extends ActionController
{
    /**
     * Lista os tipos de turma
     * @return ViewModel
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery("SELECT t FROM Escola\Entity\Turma t");
        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel([
            'dados' => $dados
        ]);
    }

    public function saveAction()
    {
        $entity = new Turma();
        $request = $this->getRequest();
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        $form = new TurmaForm($this->getEntityManager(), $id);
        if ($id > 0){
            $entity = $this->getEntityManager()->find('Escola\Entity\Turma', $id);
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
                    $this->flashMessenger()->addMessage([
                        'success' => 'Turma Salva!'
                    ]);
                } else {
                    $this->flashMessenger()->addMessage([
                        'success' => 'Turma Alterada!'
                    ]);
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionado
                 */
                return $this->redirect()->toUrl('/escola/turma');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function buscaAction()
    {
        $q = (string) $this->params()->fromPost('q');
        $query = $this->getEntityManager()->createQuery("
            SELECT t FROM Escola\Entity\Turma t WHERE t.nome LIKE :query
        ");
        $query->setParameter('query', '%' . $q . '%');
        $dados = $query->getResult();

        $view = new ViewModel([
            'dados' => $dados
        ]);

        $view->setTerminal(true);

        return $view;
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        try {
            $entity = $this->getEntityManager()->find('Escola\Entity\Turma', $id);
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        } catch (\Exception $e){
            throw new \Exception("Registro não encontrado");
        }

        $this->flashMessenger()->addMessage([
            'success' => 'Registro Removido com sucesso!'
        ]);

        return $this->redirect()->toUrl('/escola/turma');
    }


    public function detalhesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $entity = $this->getEntityManager()->find('Escola\Entity\Turma', $id);

        if (!$entity)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel([
            'data' => $entity
        ]);

        $view->setTerminal(true);

        return $view;
    }

}