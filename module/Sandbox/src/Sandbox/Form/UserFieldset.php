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
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class UserFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('user');
        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new User());

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'options' => array(
                'label' => 'Your name'
            ),
            'attributes' => array(
                'required' => 'required'
            )
        ));
        $cityFieldset = new CityFieldset($objectManager);
        $cityFieldset->setLabel('Your city');
        $cityFieldset->setName('city');
        $this->add($cityFieldset);
    }

    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true
            )
        );
    }
}