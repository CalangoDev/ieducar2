<?php
/**
 * Created by Vim.
 * User: eduardojunior
 * Date: 02/01/16
 * Time: 18:12
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\Curso;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\Curso as CursoForm;

/**
 * Controlador que gerencia os cursos
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class CursoController extends ActionController
{
    /**
     * Lista os cursos
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT c FROM Escola\Entity\Curso c');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $curso = new Curso();
        $form = new CursoForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $curso = $this->getEntityManager()->find('Escola\Entity\Curso', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($curso);

        if ($request->isPost()){
            $form->setInputFilter($curso->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($curso);
                    $this->flashMessenger()->addMessage(array('success' => 'Curso Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Curso Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/curso');
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

        $curso = $this->getEntityManager()->find('Escola\Entity\Curso', $id);

        if (!$curso)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $curso
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
          SELECT c FROM Escola\Entity\Curso c WHERE c.nome LIKE :query");
        $query->setParameter('query', "%".$q."%");
        $dados = $query->getResult();

        $view = new ViewModel(array(
            'dados' => $dados
        ));
        $view->setTerminal(true);

        return $view;
    }


    /**
     * Excluir um curso
     * @throws \Exception If registro não encontrado
     * @return void
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        try{
            $curso = $this->getEntityManager()->find('Escola\Entity\Curso', $id);
            $this->getEntityManager()->remove($curso);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/curso');
    }
}
