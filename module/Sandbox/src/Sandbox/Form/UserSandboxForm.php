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


class UserSandboxForm extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('user-sandbox-form');

        $this->setHydrator(new DoctrineHydrator($objectManager));

        $userFieldset = new UsersandboxFieldset($objectManager);
        $userFieldset->setName('usersandbox');
        $userFieldset->setUseAsBaseFieldset(true);
        $this->add($userFieldset);


        $this->setValidationGroup(array(

            'usersandbox' => array(
                'nome',
                'documentosandbox' => array(
                    'id',
                    'nome',
                    'tipoLogradouro'
                ),
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