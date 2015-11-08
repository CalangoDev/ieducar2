<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/11/15
 * Time: 16:39
 */
namespace Sandbox\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class EditNameForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('edit-name-form');

        $this->setHydrator(new DoctrineHydrator($objectManager));

        $userFieldset = new UserFieldset($objectManager);
        $userFieldset->setName('user');
        $userFieldset->setUseAsBaseFieldset(true);
        $this->add($userFieldset);


        $this->setValidationGroup(array(

            'user' => array(
                'name'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Salvar',
                'id' => 'submitbutton',
                'class' => 'btn btn-lg btn-primary',
            ),
        ));
    }
}