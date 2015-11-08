<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/11/15
 * Time: 15:34
 */
namespace Sandbox\Form;

use Sandbox\Entity\City;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class CityFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('city');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new City());

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'options' => array(
                'label' => 'Name of your city'
            ),
            'attributes' => array(
                'required' => 'required'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'postCode',
            'options' => array(
                'label' => 'Postcode of your city'
            ),
            'attributes' => array(
                'required' => 'required'
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true
            ),

            'postCode' => array(
                'required' => true
            )
        );
    }
}