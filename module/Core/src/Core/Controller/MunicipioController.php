<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 22/11/15
 * Time: 11:07
 */
namespace Core\Controller;

use Zend\View\Model\JsonModel;
use Core\Entity\CepUnico;

/**
 * Controlador resposavel pela busca por municipio do sistema
 *
 * @category Core
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class MunicipioController extends ActionController
{
    public function indexAction()
    {
        $termo = $this->params()->fromQuery('term');
        //$termo = $this->params()->fromRoute('term');
        $id = $this->params()->fromRoute('id');

        $lista = array();

        if ($id){
            $cidade = $this->getEntityManager()->find('Core\Entity\CepUnico', $id);
            $lista = array(
                $cidade->getNome() . ' - ' . $cidade->getUf()
            );

        } else {

            $dados = array();
            if ($termo != ""){

                $query = $this->getEntityManager()->createQuery("SELECT cep FROM Core\Entity\CepUnico cep WHERE cep.nome LIKE :termo");
                $query->setParameter('termo', '%' . $termo . '%');
                $dados = $query->getResult();
    //            foreach ($dados as $municipio):
    //                $dados = array(
    //                    'value' => $municipio->getNome(),
    //                    'label' => $municipio->getNome()
    //                );
    //            endforeach;
                $lista = array();
                foreach ($dados as $cidade) {
                    $lista[] = array(
                        'value' => $cidade->getId(),
                        'label' => $cidade->getNome() . ' - ' . $cidade->getUf()
                    );
                }

            }


        }

        //{"value":"Some Name","id":1},{"value":"Some Othername","id":2}
        //[ "c++", "java", "php", "coldfusion", "javascript", "asp", "ruby" ]
        //{"value":"Irec\u00ea"}
        //[{"value":"Some Name","id":1},{"value":"Some Othername","id":2}],

        //return new JsonModel([$dados]);

        return new JsonModel($lista);

    }
}