<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 29/04/16
 * Time: 08:33
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class SequenciaSerie extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('sequencia-serie');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/sequencia-serie/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\SequenciaSerie());

        $this->setUseInputFilterDefaults(false);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'serieOrigem',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'serieOrigem'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Série Origem: ',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Serie',
                'property' => 'nome',
                'label_generator' => function($targetEntity) {
                    //return $targetEntity->getJuridica()->getNome() . ' (' . $targetEntity->getSigla() . ')';
                    return $targetEntity->getNome() . ' (' . $targetEntity->getCurso()->getNome() . ')';
                },
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
            'name' => 'serieDestino',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'serieDestino'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Série Destino: ',
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
                'label_generator' => function($targetEntity) {
                    //return $targetEntity->getJuridica()->getNome() . ' (' . $targetEntity->getSigla() . ')';
                    return $targetEntity->getNome() . ' (' . $targetEntity->getCurso()->getNome() . ')';
                },

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