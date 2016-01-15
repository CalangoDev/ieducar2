<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 20:12
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ComponenteCurricular extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('componente-curricular');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/componente-curricular/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\ComponenteCurricular());

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
                'label' => 'Nome:',
                'help' => 'Nome por extenso do componente.'
            )
        ));

        $this->add(array(
            'name' => 'abreviatura',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Abreviatura:',
                'help' => 'Nome abreviado do componente.'
            )
        ));

        $this->add(array(
            'name' => 'tipoBase',
            'options' => array(
                'label' => 'Base Curricular:',
                'value_options' => array(
                    '1' => 'Base nacional comum',
                    '2' => 'Base diversificada',
                    '3' => 'Base profissional',
                ),
            ),
            'type' => 'Zend\Form\Element\Radio',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Radio',
            )
        ));

        $this->add(array(
            'name' => 'areaConhecimento',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'areaConhecimento'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Área conhecimento: ',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\AreaConhecimento',
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