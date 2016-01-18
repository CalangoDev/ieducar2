<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/01/16
 * Time: 20:23
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\RedeEnsino;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\RedeEnsino as RedeEnsinoForm;

/**
 * Controlador que gerencia as redes de ensino
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class RedeEnsinoController extends ActionController
{
    /**
     * Lista as redes de ensino
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT re FROM Escola\Entity\RedeEnsino re');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $redeEnsino = new RedeEnsino();
        $form = new RedeEnsinoForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $redeEnsino = $this->getEntityManager()->find('Escola\Entity\RedeEnsino', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($redeEnsino);

        if ($request->isPost()){
            $form->setInputFilter($redeEnsino->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($redeEnsino);
                    $this->flashMessenger()->addMessage(array('success' => 'Rede de Ensino Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Rede de Ensino Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/rede-ensino');
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

        $data = $this->getEntityManager()->find('Escola\Entity\RedeEnsino', $id);

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
          SELECT re FROM Escola\Entity\RedeEnsino re WHERE re.nome LIKE :query ");
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
            $data = $this->getEntityManager()->find('Escola\Entity\RedeEnsino', $id);
            $this->getEntityManager()->remove($data);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));

        return $this->redirect()->toUrl('/escola/rede-ensino');

    }
}
