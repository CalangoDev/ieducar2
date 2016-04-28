<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/04/16
 * Time: 12:17
 */
namespace Escola\Form;

use Escola\Entity\ComponenteCurricularAnoEscolar;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class ComponenteCurricularAnoEscolarFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('componente-curricular-ano-escolar');

        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new ComponenteCurricularAnoEscolar());

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
                'value' => 0
            )
        ));


        $this->add(array(
            'name' => 'componenteCurricular',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'cargaHoraria',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Carga Horária:',
                'help' => 'Carga Horária.'
            )
        ));

        $this->add(array(
            'name' => 'serie',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'serie'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Série: ',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Serie',
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

    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false
            )
        );
    }
}