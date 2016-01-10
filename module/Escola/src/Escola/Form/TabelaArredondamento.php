<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/01/16
 * Time: 23:19
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class TabelaArredondamento extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('tabela-arredondamento');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/tabela-arredondamento/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\TabelaArredondamento());
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
            'name' => 'tipoNota',
            'options' => array(
                'label' => 'Tipo de Nota: ',
                'value_options' => array(
                    1 => 'Nota Numérica',
                    2 => 'Nota Conceitual',
                ),
            ),
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                'value' => 1,
                'class' => 'form-control'
            ),
        ));

        $notasFieldset = new \Escola\Form\TabelaArredondamentoValorFieldset($objectManager);
        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name' => 'notas',
            'options' => array(
                'label' => 'Notas para arredondamento: ',
                'count'           => 5,
                'target_element' => $notasFieldset,
                //'allow_add' => true,
                //'should_create_template' => true,
                //'template_placeholder' => '__placeholder__',
            ),
            'attributes' => array(
                'type'    => 'Zend\Form\Element\Collection',
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