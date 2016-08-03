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

        $anos = array(
            date('Y') => date('Y'),
            date('Y', strtotime('+1 year')) => date('Y', strtotime('+1 year')),
            date('Y', strtotime('+2 year')) => date('Y', strtotime('+2 year')),
            date('Y', strtotime('+3 year')) => date('Y', strtotime('+3 year')),
            date('Y', strtotime('+4 year')) => date('Y', strtotime('+4 year')),
        );

        $escola = $this->getEntityManager()->find('Escola\Entity\Escola', $escola);
        foreach ($escola->getAnosLetivos() as $ano){
            unset($anos[$ano->getAno()]);
        }


        return $anos;
    }

    public function saveAction()
    {
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
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($entity);
        $form->get('escola')->setValue($escola);

        if ($request->isPost()){

            //$date = new \DateTime($this->params()->fromPost('dataNasc'), new \DateTimeZone('America/Sao_Paulo'));
            //$entity->setDataNasc($date->format('Y-m-d'));
            //$request->getPost()->set('dataNasc', $fisica->getDataNasc());

            $form->setInputFilter($entity->getInputFilter());
            $form->setData($request->getPost());
            //var_dump($request->getPost());
            if ($form->isValid()){
                /**
                 * persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($entity);
                    $this->flashMessenger()->addMessage(array('success' => 'Ano Letivo Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Ano Letivo Alterado!'));
                }

                $this->getEntityManager()->flush();

                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/ano-letivo');
            } else {
                //var_dump($form->getInputFilter());
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