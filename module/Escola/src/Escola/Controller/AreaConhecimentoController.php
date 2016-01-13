<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 13/01/16
 * Time: 19:13
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\AreaConhecimento;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\AreaConhecimento as AreaConhecimentoForm;

/**
 * Controlador que gerencia as areas de conhecimento
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class AreaConhecimentoController extends ActionController
{
    /**
     * Lista as areas de conhecimentos
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT a FROM Escola\Entity\AreaConhecimento a');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $area = new AreaConhecimento();
        $form = new AreaConhecimentoForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $area = $this->getEntityManager()->find('Escola\Entity\AreaConhecimento', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($area);

        if ($request->isPost()){
            $form->setInputFilter($area->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($area);
                    $this->flashMessenger()->addMessage(array('success' => 'Área de Conhecimento Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Área de Conhecimento Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/area-conhecimento');
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

        $area = $this->getEntityManager()->find('Escola\Entity\AreaConhecimento', $id);

        if (!$area)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $area
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
        $query = $this->getEntityManager()->createQuery("SELECT a FROM Escola\Entity\AreaConhecimento a WHERE a.nome
LIKE :query OR a.secao LIKE :query ");
        $query->setParameter('query', "%".$q."%");
        $dados = $query->getResult();

        $view = new ViewModel(array(
            'dados' => $dados
        ));
        $view->setTerminal(true);

        return $view;
    }


    /**
     * Excluir uma area
     * @throws \Exception If registro não encontrado
     * @return void
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        try{
            $area = $this->getEntityManager()->find('Escola\Entity\AreaConhecimento', $id);
            $this->getEntityManager()->remove($area);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/area-conhecimento');
    }
}
