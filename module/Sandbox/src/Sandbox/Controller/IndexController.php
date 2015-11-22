<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sandbox\Controller;

use Sandbox\Entity\User;
use Sandbox\Entity\Usersandbox;
use Sandbox\Form\EditNameForm;
use Sandbox\Form\UserSandboxForm;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Core\Controller\ActionController;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class IndexController extends ActionController
{
	
    public function indexAction()
    {
        $query = $this->getEntityManager()->createQuery('SELECT u FROM Sandbox\Entity\Usersandbox u');
        $dados = new Paginator(
            new DoctrinePaginator(new ORMPaginator($query))
        );
        $dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);
        return new ViewModel(
            array(
                'dados' => $dados
            )
        );
    }

    public function saveAction()
    {

        $user = new Usersandbox();
        $form = new UserSandboxForm($this->getEntityManager());
        $request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');

        if ($id > 0){
            $user = $this->getEntityManager()->find('Sandbox\Entity\Usersandbox', $id);
            $form->get('submit')->setAttribute('value', 'Atualizar');
        }
        //$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Sandbox\Entity\Usersandbox'));

        $form->bind($user);

        if ($request->isPost()){

            $data = $request->getPost()->toArray();

//            if ($data['usersandbox']['documento']['id'] == "")
//                $data['usersandbox']['documento']['id'] = 0;

//            if ($data['usersandbox']['id'] == "")
//                $data['usersandbox']['id'] = 0;

            $form->setData($data);

            if ($form->isValid()){


                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($user);
                    $this->flashMessenger()->addSuccessMessage('User Salvo');
                } else {
                    $this->flashMessenger()->addSuccessMessage('User Alterado');
                }

                $this->getEntityManager()->flush();



                return $this->redirect()->toUrl('/sandbox/index');
            }
        }


        return new ViewModel(array(
            'form' => $form
        ));

    }

    public function createAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = new EditNameForm($objectManager);

        //$loggedUser = $this->userIdentity();
        $user = new User();

        $form->bind($user);

        $request = $this->request;

        if ($request->isPost()){
            $form->setData($request->getPost());

            if ($form->isValid()){

                $objectManager->persist($user);
                $objectManager->flush();

            }
        }

        return array('form' => $form);

    }


}
