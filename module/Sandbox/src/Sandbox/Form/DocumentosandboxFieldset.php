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
use Sandbox\Entity\Documentosandbox;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class DocumentosandboxFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('documentosandbox');

        $this->setHydrator(new DoctrineHydrator($objectManager))
            ->setObject(new Documentosandbox());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'attributes' => array(
                'value' => 0
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'nome',
            'options' => array(
                'label' => 'Name do documento'
            ),
            'attributes' => array(
                'required' => 'required'
            )
        ));

        $this->add(array(
            'name' => 'tipoLogradouro',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select tipoLogradouro',
                'style' => 'height:100px;',
                //'required' => 'required'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Tipo de Logradouro:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Core\Entity\TipoLogradouro',
                'property' => 'descricao',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('descricao' => 'ASC')
                    )
                )
            ),
        ));

    }


    public function getInputFilterSpecification()
    {

        return array(
            'id' => array(
                'required' => false
            ),

            'tipoLogradouro' => array(
                'required' => true,
                'continue_if_empty' => true,
                'filters' => array(
                    array('name' => 'Null')
                ),
            ),

            'nome' => array(
                'required' => false
            ),

        );
    }

}