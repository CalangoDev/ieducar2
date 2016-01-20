<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 18/01/16
 * Time: 17:54
 */
namespace Escola\Form;

use Usuario\Entity\Juridica;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class JuridicaFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('juridica');

        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new Juridica());

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
                'value' => 0
            )
        ));

        $this->add(array(
            'name' => 'situacao',
            'attributes' => array(
                'type' => 'hidden',
                'value' => 'A'
            )
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Nome:'
            )
        ));

        $this->add(array(
            'name' => 'cnpj',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'CNPJ:'
            )
        ));


        $this->add(array(
            'name' => 'fantasia',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Nome de Fantasia:'
            )
        ));

        $enderecoExternoFieldset = new \Usuario\Form\EnderecoExternoFieldset($objectManager);
        $enderecoExternoFieldset->setLabel('Endereço');
        $enderecoExternoFieldset->setName('enderecoExterno');
        $enderecoExternoFieldset->setUseAsBaseFieldset(false);
        $this->add($enderecoExternoFieldset);

        $this->add(array(
            'name' => 'url',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Site',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false
            ),

           'fantasia' => array(
               'required' => false
           )
        );
    }
}