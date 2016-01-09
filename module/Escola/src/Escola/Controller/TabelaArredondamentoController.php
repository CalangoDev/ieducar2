<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/01/16
 * Time: 23:10
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\TabelaArredondamento;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\TabelaArredondamento as TabelaArredondamentoForm;

/**
 * Controlador que gerencia as tabelas de arredondamento
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class TabelaArredondamentoController extends ActionController
{
    /**
     * Lista as tabelas
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT t FROM Escola\Entity\TabelaArredondamento t');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $tabela = new TabelaArredondamento();
        $form = new TabelaArredondamentoForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $tabela = $this->getEntityManager()->find('Escola\Entity\TabelaArredondamento', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($tabela);

        if ($request->isPost()){
            $form->setInputFilter($tabela->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($tabela);
                    $this->flashMessenger()->addMessage(array('success' => 'Tabela de arredondamento Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Tabela de arredondamento Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/tabela-arredondamento');
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

        $formulaMedia = $this->getEntityManager()->find('Escola\Entity\TabelaArredondamento', $id);

        if (!$formulaMedia)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $formulaMedia
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
          SELECT t FROM Escola\Entity\TabelaArredondamento t WHERE t.nome LIKE :query");
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
            $tabela = $this->getEntityManager()->find('Escola\Entity\TabelaArredondamento', $id);
            $this->getEntityManager()->remove($tabela);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/tabela-arredondamento');
    }
}