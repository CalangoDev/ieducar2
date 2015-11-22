<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/11/15
 * Time: 15:25
 */
namespace Sandbox\Form;

use Sandbox\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Sandbox\Entity\Usersandbox;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class UsersandboxFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('usersandbox');
        $this->setHydrator(new DoctrineHydrator($objectManager))
            ->setObject(new Usersandbox());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'nome',
            'options' => array(
                'label' => 'Seu nome'
            ),
            'attributes' => array(
                'required' => 'required'
            )
        ));

        $documentoFieldset = new DocumentosandboxFieldset($objectManager);
        $documentoFieldset->setLabel('Documento');
        $documentoFieldset->setName('documentosandbox');
        $this->add($documentoFieldset);



    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false
            ),

            'nome' => array(
                'required' => true
            ),

            'documentosandbox' => array(
                'required' => false,
                'continue_if_empty' => true,
                'filters' => array(
                    array('name' => 'Int'),
                    array('name' => 'Null')
                ),
//                'usersandbox' => array(
//                    'required' => false
//                )
            )
        );
    }
}