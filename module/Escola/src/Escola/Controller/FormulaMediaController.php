<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/01/16
 * Time: 15:38
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\FormulaMedia;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\FormulaMedia as FormulaMediaForm;

/**
 * Controlador que gerencia as formulas medias
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class FormulaMediaController extends ActionController
{
    /**
     * Lista as formulas medias
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT fm FROM Escola\Entity\FormulaMedia fm');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $formulaMedia = new FormulaMedia();
        $form = new FormulaMediaForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $formulaMedia = $this->getEntityManager()->find('Escola\Entity\FormulaMedia', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($formulaMedia);

        if ($request->isPost()){
            $form->setInputFilter($formulaMedia->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($formulaMedia);
                    $this->flashMessenger()->addMessage(array('success' => 'Fórmula Média Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Fórmula Média Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/formula-media');
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

        $formulaMedia = $this->getEntityManager()->find('Escola\Entity\FormulaMedia', $id);

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
          SELECT fm FROM Escola\Entity\FormulaMedia fm WHERE fm.nome LIKE :query OR fm.formulaMedia LIKE :query ");
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
            $formulaMedia = $this->getEntityManager()->find('Escola\Entity\FormulaMedia', $id);
            $this->getEntityManager()->remove($formulaMedia);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/formula-media');
    }
}