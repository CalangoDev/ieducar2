<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/01/16
 * Time: 11:03
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\Serie;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\Serie as SerieForm;

/**
 * Controlador que gerencia as series
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class SerieController extends ActionController
{
    /**
     * Lista os cursos
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT s FROM Escola\Entity\Serie s');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $serie = new Serie();
        $form = new SerieForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $serie = $this->getEntityManager()->find('Escola\Entity\Serie', $id);
            //populate etapaCurso de acordo com o curso salvo.
            $opcoesEtapas = array();
            for ($i = 1; $i <= $serie->getCurso()->getQuantidadeEtapa(); $i++){
                $opcoesEtapas[$i] = 'Etapa ' . $i;
            }

            $form->get('etapaCurso')->setAttribute('options', $opcoesEtapas);
            $form->get('etapaCurso')->setAttribute('disabled', false);
            //$form->get('usernames')->setValueOptions($usernames
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($serie);

        if ($request->isPost()){
            $form->get('etapaCurso')->setAttribute('disabled', false);
            $form->setInputFilter($serie->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($serie);
                    $this->flashMessenger()->addMessage(array('success' => 'Série Salva!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Série Alterada!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/serie');
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

        $serie = $this->getEntityManager()->find('Escola\Entity\Serie', $id);

        if (!$serie)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $serie
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
          SELECT s FROM Escola\Entity\Serie s WHERE s.nome LIKE :query");
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
            $serie = $this->getEntityManager()->find('Escola\Entity\Serie', $id);
            $this->getEntityManager()->remove($serie);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/serie');
    }
}
