<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 21/06/16
 * Time: 09:19
 */
namespace Escola\Form;

use Escola\Entity\AnoLetivoModulo;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class AnoLetivoModulosFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('ano-letivo-modulos');

        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new AnoLetivoModulo());

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id'
        ));

//        $this->add(array(
//            'type' => 'hidden',
//            'name' => 'anoLetivo'
//        ));

        $this->add(array(
            'name' => 'modulo',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control modulo',
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Módulo: ',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Modulo',
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
            'type' => 'text',
            'name' => 'dataInicio',
            'options' => array(
                'label' => 'Data Início: ',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control dataInicio',
            )
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'dataFim',
            'options' => array(
                'label' => 'Data Fim:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control dataFim',
            )
        ));

    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false
            ),
            'dataInicio' => array(
                'required' => true
            ),
            'dataFim' => array(
                'required' => true
            ),
            'modulo' => array(
                'reduired' => true
            ),
            'anoLetivo' => array(
                'required' => false
            )
        );
    }
}