<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 28/01/16
 * Time: 22:38
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class EscolaSerie extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('escola-serie');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/escola-serie/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\EscolaSerie());

        $this->setUseInputFilterDefaults(false);
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'escola',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'stype' => 'height:100px',
                'id' => 'escola'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Escola:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Escola',
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
            'name' => 'serie',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'stype' => 'height:100px',
                'id' => 'serie'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Série:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Serie',
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
            'name' => 'horaInicial',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Hora Inicial:',
                'help' => 'hh:mm'
            )
        ));

        $this->add(array(
            'name' => 'horaFinal',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Hora Final:',
                'help' => 'hh:mm'
            )
        ));

        $this->add(array(
            'name' => 'inicioIntervalo',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Hora Início Intervalo:',
                'help' => 'hh:mm'
            )
        ));

        $this->add(array(
            'name' => 'fimIntervalo',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Hora Fim Intervalo:',
                'help' => 'hh:mm'
            )
        ));

        $this->add(array(
            'name' => 'bloquearEnturmacao',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Checkbox',
                'class' => 'form-control'
            ),
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Bloquear enturmação após atingir limite de vagas: ',
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
        ));

        $this->add(array(
            'name' => 'bloquearCadastroTurma',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Checkbox',
                'class' => 'form-control'
            ),
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Bloquear cadastro de novas turmas antes de atingir limite de vagas (no mesmo turno): ',
                'checked_value' => '1',
                'unchecked_value' => '0'
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