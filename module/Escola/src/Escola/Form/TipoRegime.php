<?php
/**
 * Created by Vim.
 * User: eduardojunior
 * Date: 23/12/15
 * Time: 00:23
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
//use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity as DoctrineHydrator;

class TipoRegime extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('tipo-regime');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/tipo-regime/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\TipoRegime());

        //very important
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
                'label' => 'Nome:'
            ),
        ));

        $this->add(array(
            'name' => 'ativo',
            'options' => array(
                'label' => 'Ativo',
                'value_options' => array(
                    true	=> 'Sim',
                    false => 'Não',
                ),
            ),
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                'value' => true,
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
