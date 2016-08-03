<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 20/06/16
 * Time: 15:56
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AnoLetivo extends Form
{
    public function __construct(ObjectManager $objectManager, $id = null, $ano)
    {
        parent::__construct('ano-letivo');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/ano-letivo/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\AnoLetivo());

        $this->setUseAsBaseFieldset(false);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        if ($id > 0 ){
            var_dump($id);
        } else {
            $ano;
        }

        /**
         * Select quando for um ano letivo novo, quando ja existir o ano letivo,
         * aparecera um input com o ano escolhido porem o input estara desativado
         */
        $this->add(array(
            'name' => 'ano',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                'class' => 'form-control ano'
            ),
            'options' => array(
                'empty_option' => 'Selecione',
                'label' => 'Ano:',
                'value_options' => $ano,
            ),
        ));

        $this->add(array(
            'name' => 'escola',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $anoLetivoModulosFieldset = new \Escola\Form\AnoLetivoModulosFieldset($objectManager);
        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name' => 'anoLetivoModulos',
            'options' => array(
                'label' => 'Módulo:',
                'target_element' => $anoLetivoModulosFieldset,
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