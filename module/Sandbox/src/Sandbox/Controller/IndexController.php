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
use Sandbox\Form\EditNameForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;

class IndexController extends AbstractActionController
{
	
    public function indexAction()
    {
        return new ViewModel();
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
