<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 28/11/15
 * Time: 10:59
 */
namespace Usuario\Form;

use Usuario\Entity\Telefone;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class TelefoneFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('telefone');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new Telefone());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'ddd',
            'options' => array(
                'label' => 'DDD:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-2 ddd',
                'placeholder' => 'DDD'
            ),

        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'numero',
            'options' => array(
                'label' => 'Numero:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control numero',
                'placeholder' => 'Número'
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false
            ),

            'ddd' => array(
                'required' => true
            ),

            'numero' => array(
                'required' => true
            )
        );
    }
}