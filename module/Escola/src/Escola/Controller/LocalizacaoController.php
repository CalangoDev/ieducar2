<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 18/01/16
 * Time: 11:59
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use Escola\Entity\Localizacao;
use Escola\Form\Localizacao as LocalizacaoForm;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

/**
 * Class LocalizacaoController
 *
 * Controlador que gerencia as localizacoes
 *
 * @category Escola
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @package Escola\Controller
 */
class LocalizacaoController extends ActionController
{
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT l FROM Escola\Entity\Localizacao l');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $localizacao = new Localizacao();
        $form = new LocalizacaoForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $localizacao = $this->getEntityManager()->find('Escola\Entity\Localizacao', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($localizacao);

        if ($request->isPost()){
            $form->setInputFilter($localizacao->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($localizacao);
                    $this->flashMessenger()->addMessage(array('success' => 'Localização Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Localização Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/localizacao');
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

        $localizacao = $this->getEntityManager()->find('Escola\Entity\Localizacao', $id);

        if (!$localizacao)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $localizacao
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
          SELECT l FROM Escola\Entity\Localizacao l WHERE l.nome LIKE :query");
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
            $localizacao = $this->getEntityManager()->find('Escola\Entity\Localizacao', $id);
            $this->getEntityManager()->remove($localizacao);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }

        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));

        return $this->redirect()->toUrl('/escola/localizacao');
    }

}