<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 20/06/16
 * Time: 15:53
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\AnoLetivo;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\AnoLetivo as AnoLetivoForm;

/**
 * Class AnoLetivoController
 *
 * @package Escola\Controller
 * @category Escola
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class AnoLetivoController extends ActionController
{
    /**
     * Lista os anos letivos
     * @return ViewModel
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT al FROM Escola\Entity\AnoLetivo al');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    /**
     * Retornar os anos para exibição no formulario
     *
     * @param $escola
     * @return array
     */
    private function getAnos($escola){

        $quantidadeMinimaAnos = 5;

        // objetivo sempre 5 anos a partir do ano atual, tirando os anos que ja existem no banco de dados

        $anos = array();

        $escola = $this->getEntityManager()->find('Escola\Entity\Escola', $escola);

        if ($escola->getAnosLetivos()->count() > 0){

            // ja existe anos inseridos, esses anos inseridos nao podem fazer parte dos 5 proximos exibidos

            for ($i = 0; $i < $quantidadeMinimaAnos; $i++){

                $ano = date("Y", strtotime("+" . $i . " year"));

                $checkAno = false;

                foreach ($escola->getAnosLetivos() as $a){

                    if ($a->getAno() == $ano){
                        $checkAno = true;
                        break;
                    }
                }

                if ($checkAno){
                    $quantidadeMinimaAnos++;
                    $ano = null;
                }

                $anos += array(
                    $ano => $ano
                );
            }

            return $anos;
        }

        for ($i = 0; $i < $quantidadeMinimaAnos; $i++){
            $anos += array(
                date('Y', strtotime('+' . $i . ' year')) => date('Y', strtotime('+' . $i . ' year'))
            );
        }

        return $anos;
    }

    public function saveAction()
    {
        /*
         * TODO fazendo os tratamentos ainda de validacao e inserção de dados, depois ajustar os testes unitarios
         *
         */
        $escola = (int) $this->params()->fromRoute('escola', 0);
        if ($escola == 0)
            throw new \Exception("Código da Escola Obrigatório");

        $entity = new AnoLetivo();
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');

        $form = new AnoLetivoForm($this->getEntityManager(), $id, $this->getAnos($escola));
        $form->setAttribute('action', '/escola/ano-letivo/save/escola/' . $escola);

        if ($id > 0){
            $entity = $this->getEntityManager()->find('Escola\Entity\AnoLetivo', $id);

            // modificar as datas inicio e fim para poder ser exibidos com qualidade no formulario
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($entity);
        $form->get('escola')->setValue($escola);

        if ($request->isPost()){


            $form->setInputFilter($entity->getInputFilter());
            $form->setData($request->getPost());


            try {
                if ($form->isValid()){
                    /**
                     * persistindo os dados
                     */
                    $id = (int) $this->params()->fromPost('id', 0);
                    $msg = 'Ano Letivo Alterado!';

                    if ($id == 0){

                        $this->getEntityManager()->persist($entity);
                        $msg = 'Ano Letivo Salvo!';

                    }

                    $this->flashMessenger()->addMessage(array('success' => $msg));
                    $this->getEntityManager()->flush();



                    /**
                     * Redirecionando
                     */
                    return $this->redirect()->toUrl('/escola/escola');
                }
            } catch (\Exception $e){
                $this->flashMessenger()->addMessage(array('error' => '<i class="glyphicon glyphicon-alert"></i> ' . $e->getMessage()));
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

        $entity = $this->getEntityManager()->find('Escola\Entity\AnoLetivo', $id);

        if (!$entity)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $entity
        ));

        $view->setTerminal(true);

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function buscaAction()
    {
        $q = (string) $this->params()->fromPost('q');
        $query = $this->getEntityManager()->createQuery("
            SELECT al 
            FROM Escola\Entity\AnoLetivo al 
            JOIN al.escola e
            JOIN e.juridica j 
            WHERE al.ano LIKE :query 
            OR j.nome LIKE :query");
        $query->setParameter('query', '%' . $q . '%');
        $dados = $query->getResult();

        $view = new ViewModel(array(
            'dados' => $dados
        ));

        $view->setTerminal(true);

        return $view;
    }

    /**
     * @return \Zend\Http\Response
     * @throws \Exception
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        try {
            $entity = $this->getEntityManager()->find('Escola\Entity\AnoLetivo', $id);
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        } catch (\Exception $e){
            throw new \Exception("Registro não encontrado");
        }

        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));

        return $this->redirect()->toUrl('/escola/ano-letivo');
    }
}