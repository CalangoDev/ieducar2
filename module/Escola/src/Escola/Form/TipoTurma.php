<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 10/09/16
 * Time: 11:55
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class TipoTurma extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('tipo-turma');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/tipo-turma/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\TipoTurma());

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
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Nome:'
            ),
        ));


        $this->add(array(
            'name' => 'sigla',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Sigla:'
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
