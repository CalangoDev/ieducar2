<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 20:24
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\ComponenteCurricular;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\ComponenteCurricular as ComponenteCurricularForm;

/**
 * Controlador que gerencia os componentes curriculares
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class ComponenteCurricularController extends ActionController
{
    /**
     * Lista os dados
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT c FROM Escola\Entity\ComponenteCurricular c');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $componente = new ComponenteCurricular();
        $form = new ComponenteCurricularForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricular', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($componente);

        if ($request->isPost()){
            $form->setInputFilter($componente->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($componente);
                    $this->flashMessenger()->addMessage(array('success' => 'Componente Curricular Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Componente Curricular Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/componente-curricular');
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

        $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricular', $id);

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
        $query = $this->getEntityManager()->createQuery("
          SELECT c FROM Escola\Entity\ComponenteCurricular c WHERE c.nome LIKE :query OR c.abreviatura LIKE :query ");
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
            $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricular', $id);
            $this->getEntityManager()->remove($componente);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/componente-curricular');
    }
}
