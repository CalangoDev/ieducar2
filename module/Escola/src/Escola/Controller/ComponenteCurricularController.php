<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 20:24
 */
namespace Escola\Controller;

use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Escola\Entity\ComponenteCurricular;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;
use Escola\Form\ComponenteCurricular as ComponenteCurricularForm;
use Escola\Entity\Serie;

/**
 * Controlador que gerencia os componentes curriculares
 *
 * @category Escola
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class ComponenteCurricularController extends ActionController
{
    /**
     * Lista os dados
     * @return void
     */
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT c FROM Escola\Entity\ComponenteCurricular c');

        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );

        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);

        return new ViewModel(array('dados' => $dados));
    }

    public function saveAction()
    {
        $componente = new ComponenteCurricular();
        $form = new ComponenteCurricularForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        if ($id > 0){
            $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricular', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }

        $form->bind($componente);

        if ($request->isPost()){
            $form->setInputFilter($componente->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()){
                /*
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($componente);
                    $this->flashMessenger()->addMessage(array('success' => 'Componente Curricular Salvo!'));
                } else {
                    $this->flashMessenger()->addMessage(array('success' => 'Componente Curricular Alterado!'));
                }

                $this->getEntityManager()->flush();
                /**
                 * Redirecionando
                 */
                return $this->redirect()->toUrl('/escola/componente-curricular');
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

        $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricular', $id);

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
        $q = (string) $this->params()->fromPost('q');
        $query = $this->getEntityManager()->createQuery("
          SELECT c FROM Escola\Entity\ComponenteCurricular c WHERE c.nome LIKE :query OR c.abreviatura LIKE :query ");
        $query->setParameter('query', "%".$q."%");
        $dados = $query->getResult();

        $view = new ViewModel(array(
            'dados' => $dados
        ));
        $view->setTerminal(true);

        return $view;
    }

    /**
     * configurar anos escolares
     */
    public function anosEscolaresAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $query = $this->getEntityManager()->createQuery("SELECT s, c FROM Escola\Entity\Serie s JOIN s.curso c ORDER BY c.nome ASC ");
        $series = $query->getResult();
        $request = $this->getRequest();

        $query_componente_ano_escolar = $this->getEntityManager()->createQuery(
            "SELECT cae, c FROM Escola\Entity\ComponenteCurricularAnoEscolar cae JOIN cae.componenteCurricular c WHERE c.id = :id"
        );
        $query_componente_ano_escolar->setParameter('id', $id);
        $dados_save = $query_componente_ano_escolar->getResult();
//        foreach ($dados as $dado):
//            var_dump($dado->getId());
//            var_dump($dado->getSerie()->getNome());
//        endforeach;

        if ($request->isPost()){
            //var_dump($request->getPost());
            $dados = $request->getPost()->toArray();
            unset($dados['submit']);
            // fazer a varredura no array procurando alguma serie "marcada"
            //var_dump($dados);


            // se tem dados salvados e estou em um post é um update, se nao é um novo registro

            if (count($dados_save) > 0){

                // se é uma edicao e nao veio nenhum $dados['serie'] devemos excluir todos os registros salvos
                if (!isset($dados['serie'])){

                    //nao veio dados novos,,, apagar os que ja existem no banco
                    foreach ($dados_save as $data){
                        $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricularAnoEscolar',
                            $data->getId());
                        $this->getEntityManager()->remove($componente);
                    }
                    $this->getEntityManager()->flush();
                    return $this->redirect()->toUrl('/escola/componente-curricular');

                } else {

                    //é uma edicao e veio algo para fazer update ou excluir
                    //var_export($dados['serie']);

                    array (
                        'componenteCurricular' => '2',
                        'serie' => array (
                            1 => 'on',
                        ),
                        'cargaHoraria' => array (
                            1 => '300', 2 => '', 3 => '90',
                        ),
                    );

                    foreach ($dados_save as $data){
                        //var_dump($data->getSerie()->getId());
                        $id_serie = $data->getSerie()->getId();
                        if (array_key_exists($id_serie, $dados['serie'])){
                            //echo 'achou ' . $id_serie . ' = ' . $dados['serie'][$id_serie] . ' => ' . $dados['cargaHoraria'][$id_serie];
                            // tenho que fazer um update no componente curricular.. preciso do id dele
                            $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricularAnoEscolar',
                                $data->getId());
                            $componente->setCargaHoraria($dados['cargaHoraria'][$id_serie]);
                            $this->getEntityManager()->flush();
                            //unset($dados[$id_serie]);
                            unset($dados['serie'][$id_serie]);
                            unset($dados['cargaHoraria'][$id_serie]);
                            //unset($dados['componenteCurricular'][$id_serie]);
                        } else {
                            //nao achou nada remove
                            $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricularAnoEscolar', $data->getId());
                            $this->getEntityManager()->remove($componente);
                            $this->getEntityManager()->flush();
                            //var_dump($dados['serie'][$id_serie]);
                            //var_dump($dados['cargaHoraria'][$id_serie]);
                            //unset($dados[$id_serie]);
                        }
                    }

                    //var_export($dados['serie']);


                    //var_dump($dados);



                    foreach ($dados['serie'] as $key => $value){
                        //var_dump($value->getId());
                        $componenteAnoEscolar = new \Escola\Entity\ComponenteCurricularAnoEscolar();
                        try {

                            $serie = $this->getEntityManager()->find('Escola\Entity\Serie', $key);
                            $componenteAnoEscolar->setSerie($serie);
                            $componenteCurricular = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricular',
                                $dados['componenteCurricular']);
                            $componenteAnoEscolar->setComponenteCurricular($componenteCurricular);
                            $componenteAnoEscolar->setCargaHoraria($dados['cargaHoraria'][$key]);

                            $this->getEntityManager()->persist($componenteAnoEscolar);
                            $this->getEntityManager()->flush();
                        } catch (\Exception $e){
                            throw new \Exception("Não foi possível inserir");
                        }
                    }

                    $this->flashMessenger()->addMessage(array('success' => 'Componente Atualizado!'));
                    return $this->redirect()->toUrl('/escola/componente-curricular');
                }

            } else {

                if (isset($dados['serie'])){

                    if (count($dados['serie']) > 0){

                        foreach ($dados['serie'] as $key => $value){

                            $componenteAnoEscolar = new \Escola\Entity\ComponenteCurricularAnoEscolar();
                            try {

                                $serie = $this->getEntityManager()->find('Escola\Entity\Serie', $key);
                                $componenteAnoEscolar->setSerie($serie);
                                $componenteCurricular = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricular',
                                    $dados['componenteCurricular']);
                                $componenteAnoEscolar->setComponenteCurricular($componenteCurricular);
                                $componenteAnoEscolar->setCargaHoraria($dados['cargaHoraria'][$key]);

                                $this->getEntityManager()->persist($componenteAnoEscolar);
                                $this->getEntityManager()->flush();
                            } catch (\Exception $e){
                                throw new \Exception("Não foi possível inserir");
                            }

                        }

                        return $this->redirect()->toUrl('/escola/componente-curricular');
                    }
                }
            }
        }

        return new ViewModel(array(
            'series' => $series,
            'id' => $id,
            'dados' => $dados_save
        ));
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
            $componente = $this->getEntityManager()->find('Escola\Entity\ComponenteCurricular', $id);
            $this->getEntityManager()->remove($componente);
            $this->getEntityManager()->flush();
        } catch(\Exception $e){
            throw new \Exception("Registro não encontrado");
        }
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
        return $this->redirect()->toUrl('/escola/componente-curricular');
    }
}
