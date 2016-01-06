<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/12/15
 * Time: 14:21
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class Curso extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('curso');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/curso/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\Curso());

        $this->setUseInputFilterDefaults(false);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Nome: '
            ),
        ));

        $this->add(array(
            'name' => 'sigla',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Sigla: ',
            )
        ));

        $this->add(array(
            'name' => 'quantidadeEtapa',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' =>  'Quantidade de Etapas: '
            ),
        ));

        $this->add(array(
            'name' => 'horaFalta',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Hora Falta: '
            )
        ));

        $this->add(array(
            'name' => 'cargaHoraria',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Carga Horaria: '
            ),
        ));

        $this->add(array(
            'name' => 'atoPoderPublico',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Ato Poder Público: '
            )
        ));


        $this->add(array(
            'name' => 'habilitacoes',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'multiple' => true,
                'class' => 'habilitacoes chosen-select',
                'style' => 'height:100px; width:100%',
                'id' => 'habilitacoes',
                'data-placeholder' => 'data-placeholder',
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Habilitação: ',
                //'empty_option' => 'Selecione',
                'allow_empty' => true,
                'continue_if_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Habilitacao',
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
            'name' => 'objetivo',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Objetivo Curso: '
            )
        ));

        $this->add(array(
            'name' => 'publicoAlvo',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Público Alvo: '
            )
        ));


        $this->add(array(
            'name' => 'instituicao',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'instituicao'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Instituição: ',
                //'empty_option' => 'Selecione',
                'allow_empty' => true,
                'continue_if_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\Instituicao',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('ativo' => true),
                        'orderBy' => array('nome' => 'ASC')
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label'   => 'Selecione',
            ),
        ));

        $this->add(array(
            'name' => 'nivelEnsino',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'nivelEnsino'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Nivel de Ensino: ',
                'allow_empty' => true,
                'continue_if_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\NivelEnsino',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('ativo' => true),
                        'orderBy' => array('nome' => 'ASC'),
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label' => 'Selecione',
            ),
        ));

        $this->add(array(
            'name' => 'tipoEnsino',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'tipoEnsino'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Tipo de Ensino: ',
                'allow_empty' => true,
                'continue_if_empty' => false,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\TipoEnsino',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('ativo' => true),
                        'orderBy' => array('nome' => 'ASC'),
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label' => 'Selecione',
            ),
        ));

        $this->add(array(
            'name' => 'tipoRegime',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
                'id' => 'tipoRegime'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Tipo Regime: ',
                'allow_empty' => true,
                'continue_if_empty' => true,
                'object_manager' => $objectManager,
                'target_class' => 'Escola\Entity\TipoRegime',
                'property' => 'nome',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('ativo' => true),
                        'orderBy' => array('nome' => 'ASC'),
                    ),
                ),
                'display_empty_item' => true,
                'empty_item_label' => 'Selecione',
            ),
        ));

        $this->add(array(
            'name' => 'ativo',
            'options' => array(
                'label' => 'Ativo: ',
                'value_options' => array(
                    '1'	=> 'Sim',
                    '0' => 'Não',
                ),
            ),
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                'value' => '1',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'padraoAnoEscolar',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Checkbox',
                'class' => 'form-control'
            ),
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Padrão Ano Escolar: ',
                'checked_value' => '1',
                'unchecked_value' => '0'
            ),
        ));

        $this->add(array(
            'name' => 'multiSeriado',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Checkbox',
                'class' => 'form-control'
            ),
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => 'Multi Seriado: ',
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
