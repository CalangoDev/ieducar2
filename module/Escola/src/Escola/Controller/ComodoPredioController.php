<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/05/16
 * Time: 10:26
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Entity\ComodoPredio;
use Escola\Form\ComodoPredio as ComodoPredioForm;
use Zend\View\Model\JsonModel;

/**
 * Controlador que gerencia os comodos dos predios
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class ComodoPredioController extends ActionController
{
    /**
     * lista os comodos dos predios
     * @return ViewModel
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT cp FROM Escola\Entity\ComodoPredio cp');
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
        $entity = new ComodoPredio();
        $form = new ComodoPredioForm($this->getEntityManager());
        $request = $this->getRequest();
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $entity = $this->getEntityManager()->find('Escola\Entity\ComodoPredio', $id);
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
                    $this->flashMessenger()->addMessage(array('success' => 'Cômodo Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Cômodo Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/comodo-predio');
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

        $data = $this->getEntityManager()->find('Escola\Entity\ComodoPredio', $id);

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
        $query = $this->getEntityManager()->createQuery("SELECT cp FROM Escola\Entity\ComodoPredio cp  "
            . "WHERE cp.nome LIKE :query OR cp.descricao LIKE :query");
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
            $data = $this->getEntityManager()->find('Escola\Entity\ComodoPredio', $id);
            $this->getEntityManager()->remove($data);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/comodo-predio');
    }

    /**
     * @return JsonModel
     * @throws \Exception
     */
    public function salasAction()
    {
        $escolaSerieId = (int) $this->params()->fromRoute('escola', 0); // id da escola serie
        if ($escolaSerieId == 0)
            throw new \Exception("Código Obrigatório");
        // preciso verificar se tem escola serie
        $escolaSerie = $this->getEntityManager()->find('Escola\Entity\EscolaSerie', $escolaSerieId);
        $query = $this->getEntityManager()->createQuery("SELECT cp, p, e FROM Escola\Entity\ComodoPredio cp "
            . "JOIN cp.predio p JOIN p.escola e WHERE e.id = :idEscola");
        $query->setParameter('idEscola', $escolaSerie->getEscola()->getId());
        $dados = $query->getResult();

        $data = [];
        foreach ($dados as $sala){
            $data[] = [
                'id' => $sala->getId(),
                'nome' => $sala->getNome()
            ];
        }

        return new JsonModel($data);

    }

}