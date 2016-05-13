<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 12/05/16
 * Time: 20:54
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ComodoFuncao extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('comodo-funcao');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/comodo-funcao/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\ComodoFuncao());
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
                'label' => 'Nome:'
            )
        ));

        $this->add(array(
            'name' => 'descricao',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Descrição:'
            )
        ));

        $this->add(array(
            'name' => 'ativo',
            'options' => array(
                'label' => 'Ativo',
                'value_options' => array(
                    '1'	=> 'Sim',
                    '0' => 'Não',
                ),
            ),
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                'value' => '1',
                'class' => 'form-control'
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