<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 05/04/16
 * Time: 09:51
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ComponenteCurricularAnoEscolar extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('componente-curricular-ano-escolar');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/componente-curricular-ano-escolar/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\ComponenteCurricularAnoEscolar());

        $this->setUseInputFilterDefaults(false);
        $this->add(array(
            'name' => 'id',
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

        $this->add(array(
            'name' => 'componenteCurricular',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'componenteCurricular'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Componente Curricular: ',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\ComponenteCurricular',
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