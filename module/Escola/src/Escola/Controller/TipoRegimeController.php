<?php
/**
 * Created by Vim.
 * User: eduardojunior
 * Date: 22/12/15
 * Time: 17:30
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\TipoRegime;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\TipoRegime as TipoRegimeForm;

/**
 * Controlador que gerencia os tipos de regimes
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class TipoRegimeController extends ActionController
{
    /**
     * Lista os tipos de regimes cadastrados
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT tp FROM Escola\Entity\TipoRegime tp');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array(
            'dados' => $dados
        ));
    }

    public function saveAction()
    {
        $tipoRegime = new TipoRegime();
        $form = new TipoRegimeForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $tipoRegime = $this->getEntityManager()->find('Escola\Entity\TipoRegime', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($tipoRegime);

        if ($request->isPost()){
            $form->setInputFilter($tipoRegime->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($tipoRegime);
                    $this->flashMessenger()->addMessage(array('success' => 'Tipo de Regime Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Tipo de Regime Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/tiporegime');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));

    }

    /**
     * Detalhes do tipo de regime
     */
    public function detalhesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $tipoRegime = $this->getEntityManager()->find('Escola\Entity\TipoRegime', $id);

        if (!$tipoRegime)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $tipoRegime
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
          SELECT tp FROM Escola\Entity\TipoRegime tp WHERE tp.nome LIKE :query
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
     * Excluir um tipo de regime
     * @throws \Exception If registro não encontrado
     * @return void
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        try{
            $tipoRegime = $this->getEntityManager()->find('Escola\Entity\TipoRegime', $id);
            $this->getEntityManager()->remove($tipoRegime);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/tiporegime');
    }
}
