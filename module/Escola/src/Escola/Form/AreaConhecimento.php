<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 13/01/16
 * Time: 19:11
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AreaConhecimento extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('area-conhecimento');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/area-conhecimento/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\AreaConhecimento());

        $this->setUseInputFilterDefaults(false);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Nome:',
                'help' => 'O nome da área de conhecimento. Exemplo: "Ciências da natureza".'
            ),
        ));

        $this->add(array(
            'name' => 'secao',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Seção',
                'help' => 'A seção que abrange a área de conhecimento. Exemplo: "Lógico Matemático".'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Salvar',
                'id' => 'submitbutton',
                'class' => 'btn btn-lg btn-primary'
            ),
        ));

    }
}