<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/01/16
 * Time: 20:15
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class RedeEnsino extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('rede-ensino');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/rede-ensino/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\RedeEnsino());

        $this->setUseInputFilterDefaults(false);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Rede Ensino:'
            )
        ));

        $this->add(array(
            'name' => 'instituicao',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'instituicao'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Instituição: ',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Instituicao',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('nome' => 'ASC')
                    ),
                ),

            ),
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