<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 03/05/16
 * Time: 11:29
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\SequenciaSerie;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\SequenciaSerie as SequenciaSerieForm;

/**
 * Controlador que gerencia as series
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class SequenciaSerieController extends ActionController
{
    /**
     * lista as sequencias series (enturmação)
     * @return ViewModel
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT ss FROM Escola\Entity\SequenciaSerie ss');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function saveAction()
    {
        $sequencia = new SequenciaSerie();
        $form = new SequenciaSerieForm($this->getEntityManager());
        $request = $this->getRequest();
        
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $sequencia = $this->getEntityManager()->find('Escola\Entity\SequenciaSerie', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($sequencia);

        if ($request->isPost()){
            $form->setInputFilter($sequencia->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                // persistindo os dados
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($sequencia);
                    $this->flashMessenger()->addMessage(array('success' => 'Sequencia de Enturmação Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Sequencia de Enturmação Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/sequencia-serie');

            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    /**
     * Detalhes Action
     * @return ViewModel
     * @throws \Exception Código Obrigatório
     */
    public function detalhesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $sequencia = $this->getEntityManager()->find('Escola\Entity\SequenciaSerie', $id);

        if (!$sequencia)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $sequencia
        ));

        $view->setTerminal(true);

        return $view;
    }

    /**
     * Busca Action
     * @return ViewModel
     */
    public function buscaAction()
    {
        $q = (string) $this->params()->fromPost('q');
        $query = $this->getEntityManager()->createQuery("SELECT ss, so, sd FROM Escola\Entity\SequenciaSerie ss 
JOIN ss.serieOrigem so JOIN ss.serieDestino sd WHERE so.nome LIKE :query OR sd.nome LIKE :query");
        $query->setParameter('query', '%' . $q . '%');
        $dados = $query->getResult();

        $view = new ViewModel(array(
            'dados' => $dados
        ));

        $view->setTerminal(true);

        return $view;
    }

    /**
     * delete action
     * @return \Zend\Http\Response
     * @throws \Exception Código Obrigatório
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        try{
            $sequencia = $this->getEntityManager()->find('Escola\Entity\SequenciaSerie', $id);
            $this->getEntityManager()->remove($sequencia);
            $this->getEntityManager()->flush();
        } catch (\Exception $e){
            throw new \Exception("Registro não encontrado");
        }

        $this->flashMessenger()->addMessage(array("success" => 'Registro Removido com sucesso!'));

        return $this->redirect()->toUrl('/escola/sequencia-serie');
    }

}