<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 16:07
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\TipoDispensa;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\TipoDispensa as TipoDispensaForm;

/**
 * Controlador que gerencia os tipos de dispensas
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class TipoDispensaController extends ActionController
{
    /**
     * Lista os dados
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT t FROM Escola\Entity\TipoDispensa t');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $tipo = new TipoDispensa();
        $form = new TipoDispensaForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $tipo = $this->getEntityManager()->find('Escola\Entity\TipoDispensa', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($tipo);

        if ($request->isPost()){
            $form->setInputFilter($tipo->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($tipo);
                    $this->flashMessenger()->addMessage(array('success' => 'Tipo de Dispensa Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Tipo de Dispensa Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/tipo-dispensa');
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

        $tipo = $this->getEntityManager()->find('Escola\Entity\TipoDispensa', $id);

        if (!$tipo)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $tipo
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
          SELECT t FROM Escola\Entity\TipoDispensa t WHERE t.nome LIKE :query OR t.descricao LIKE :query ");
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
            $tipo = $this->getEntityManager()->find('Escola\Entity\TipoDispensa', $id);
            $this->getEntityManager()->remove($tipo);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/tipo-dispensa');
    }
}
