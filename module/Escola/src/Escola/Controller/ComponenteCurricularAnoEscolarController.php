<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 05/04/16
 * Time: 23:19
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\ComponenteCurricularAnoEscolar;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\ComponenteCurricularAnoEscolar as ComponenteCurricularAnoEscolarForm;

/**
 * Controlador que gerencia os componentes curriculares ano escolar
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class ComponenteCurricularAnoEscolarController extends ActionController
{
    /**
     * Lista os dados
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT c FROM Escola\Entity\ComponenteCurricularAnoEscolar c');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $componente = new ComponenteCurricularAnoEscolar();
        $form = new ComponenteCurricularAnoEscolarForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricularAnoEscolar', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($componente);

        if ($request->isPost()){
            $form->setInputFilter($componente->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /**
                 * persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($componente);
                    $this->flashMessenger()->addMessage(
                        array('success' => 'Componente Curricular Ano Escolar Salvo!')
                    );
                } else {
                    $this->flashMessenger()->addMessage(
                        array('success' => 'Componente Curricular Ano Escolar Alterado!')
                    );
                }

                $this->getEntityManager()->flush();
                /**
                 * redirecionando
                 */
                return $this->redirect()->toUrl('/escola/componente-curricular-ano-escolar');
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

        $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricularAnoEscolar', $id);

        if (!$componente)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $componente
        ));

        $view->setTerminal(true);

        return $view;

    }

    /**
     * Busca
     */
    public function buscaAction()
    {
        // busca por nome do componente, carga horaria e nome da serie
        $q = (string) $this->params()->fromPost('q');
        $query = $this->getEntityManager()->createQuery("
          SELECT c, cc, s  
          FROM Escola\Entity\ComponenteCurricularAnoEscolar c
          JOIN c.componenteCurricular cc
          JOIN c.serie s
          WHERE c.cargaHoraria LIKE :query OR cc.nome LIKE :query OR s.nome LIKE :query");
        $query->setParameter('query', "%" . $q . "%");
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
            $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricularAnoEscolar', $id);
            $this->getEntityManager()->remove($componente);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/componente-curricular-ano-escolar');
    }
}