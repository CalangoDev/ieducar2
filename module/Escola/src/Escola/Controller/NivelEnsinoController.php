<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/12/15
 * Time: 23:07
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\NivelEnsino;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\NivelEnsino as NivelEnsinoForm;

/**
 * Controlador que gerencia os niveis de ensino
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class NivelEnsinoController extends ActionController
{
    /**
     * Lista os niveis de ensino
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT ne FROM Escola\Entity\NivelEnsino ne');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $nivelEnsino = new NivelEnsino();
        $form = new NivelEnsinoForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $nivelEnsino = $this->getEntityManager()->find('Escola\Entity\NivelEnsino', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($nivelEnsino);

        if ($request->isPost()){
            $form->setInputFilter($nivelEnsino->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($nivelEnsino);
                    $this->flashMessenger()->addMessage(array('success' => 'Nivel de Ensino Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Nivel de Ensino Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/nivel-ensino');
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

        $data = $this->getEntityManager()->find('Escola\Entity\NivelEnsino', $id);

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
          SELECT ne FROM Escola\Entity\NivelEnsino ne WHERE ne.nome LIKE :query OR ne.descricao LIKE :query ");
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
            $data = $this->getEntityManager()->find('Escola\Entity\NivelEnsino', $id);
            $this->getEntityManager()->remove($data);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/nivel-ensino');
    }
}
