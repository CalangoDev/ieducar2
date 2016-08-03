<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 18/01/16
 * Time: 17:42
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class Escola extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('escola');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/escola/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\Escola());

        $this->setUseInputFilterDefaults(false);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'codigoInep',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Código INEP:'
            )
        ));

        $this->add(array(
            'name' => 'sigla',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Sigla:'
            )
        ));

        $this->add(array(
            'name' => 'redeEnsino',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'stype' => 'height:100px',
                'id' => 'redeEnsino'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Rede de Ensino:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\RedeEnsino',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('nome' => 'ASC')
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'localizacao',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px',
                'id' => 'localizacao'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Escola Localização:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Localizacao',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy' => array('nome' => 'ASC')
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'bloquearLancamento',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Checkbox',
                'class' => 'form-control'
            ),
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Bloquear lançamento no diário para anos letivos encerrados: ',
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
        ));

        $juridicaFieldset = new \Escola\Form\JuridicaFieldset($objectManager);
        $juridicaFieldset->setLabel('Jurídica');
        $juridicaFieldset->setName('juridica');
        $juridicaFieldset->setUseAsBaseFieldset(false);
        $this->add($juridicaFieldset);

        $telefoneFieldset = new \Usuario\Form\TelefoneFieldset($objectManager);
        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name' => 'telefones',
            'options' => array(
                'label' => 'Telefones:',
                'count'           => 2,
                'target_element' => $telefoneFieldset
            ),
            'attributes' => array(
                'type'    => 'Zend\Form\Element\Collection',
            )
        ));

        $this->add(array(
            'name' => 'cursos',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'multiple' => true,
                'class' => 'cursos chosen-select',
                'style' => 'height:100px; width:100%',
                'id' => 'cursos',
                'data-placeholder' => 'data-placeholder',
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Curso: ',
                'empty_option' => 'Selecione',
                'allow_empty' => true,
                'continue_if_empty' => true,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Curso',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('ativo' => true),
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