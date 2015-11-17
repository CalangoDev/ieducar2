<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/11/15
 * Time: 23:08
 */
namespace Usuario\Form;

use Usuario\Entity\Documento;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;

class DocumentoFieldset extends Fieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('documento');

        $this->setHydrator(new DoctrineHydrator($objectManager))
             ->setObject(new Documento());

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'rg',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'RG'
            ),
            'options' => array(
                'label' => 'RG:'
            )
        ));

        $this->add(array(
            'name' => 'dataEmissaoRg',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control dataEmissaoRg',
                'placeholder' => 'Data de Emissão'
            ),
            'options' => array(
                'label' => 'Data de Emissão:',
            ),
        ));

        $this->add(array(
            'name' => 'siglaUfEmissaoRg',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select uf',
                'style' => 'height:100px;',
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Estado:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Core\Entity\Uf',
                'property' => 'nome'
            ),
        ));

        $this->add(array(
            'name' => 'tipoCertidaoCivil',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                //'value' => 'M',
                'class' => 'form-control tipoCertidaoCivil'
            ),
            'options' => array(
                'empty_option' => 'Selecione',
                'label' => 'Tipo Certidão Civil:',
                'value_options' => array(
                    1 => 'Nascimento (novo formato)',
                    2 => 'Nascimento (antigo formato)',
                    3 => 'Casamento',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'termo',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Termo',
                'id' => 'termo'
            ),
            'options' => array(
                'label' => 'Termo'
            )
        ));

        $this->add(array(
            'name' => 'livro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Livro',
                'id' => 'livro'
            ),
            'options' => array(
                'label' => 'Livro'
            )
        ));

        $this->add(array(
            'name' => 'folha',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Folha',
                'id' => 'folha'
            ),
            'options' => array(
                'label' => 'Folha'
            )
        ));

        $this->add(array(
            'name' => 'dataEmissaoCertidaoCivil',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control dataEmissaoCertidaoCivil',
                'id' => 'dataEmissaoCertidaoCivil'
            ),
            'options' => array(
                'label' => 'Data de Emissão:',
            ),
        ));

        $this->add(array(
            'name' => 'siglaUfCertidaoCivil',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select uf',
                'style' => 'height:100px;',
                'id' => 'siglaUfCertidaoCivil'
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Estado Certidão:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Core\Entity\Uf',
                'property' => 'nome'
            ),
        ));

        $this->add(array(
            'name' => 'cartorioCertidaoCivil',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
                'placeholder' => 'Cartório Certidão Civil',
                'style' => 'min-height: 100px;',
                'id' => 'cartorioCertidaoCivil'
            ),
            'options' => array(
                'label' => 'Certidão Civil:'
            )
        ));

        $this->add(array(
            'name' => 'numeroCarteiraTrabalho',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Número Carteira de Trabalho'
            ),
            'options' => array(
                'label' => 'Número Carteira de Trabalho'
            )
        ));

        $this->add(array(
            'name' => 'serieCarteiraTrabalho',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Série '
            ),
            'options' => array(
                'label' => 'Série'
            )
        ));

        $this->add(array(
            'name' => 'dataEmissaoCarteiraTrabalho',
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control dataEmissaoCarteiraTrabalho'
            ),
            'options' => array(
                'label' => 'Data de Emissão:',
            ),
        ));

        $this->add(array(
            'name' => 'siglaUfCarteiraTrabalho',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select uf',
                'style' => 'height:100px;',
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Estado:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Core\Entity\Uf',
                'property' => 'nome'
            ),
        ));

        $this->add(array(
            'name' => 'numeroTituloEleitor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Numero do Título Eleitor'
            ),
            'options' => array(
                'label' => 'Título de Eleitor'
            )
        ));

        $this->add(array(
            'name' => 'zonaTituloEleitor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Zona'
            ),
            'options' => array(
                'label' => 'Zona'
            )
        ));

        $this->add(array(
            'name' => 'secaoTituloEleitor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Seção'
            ),
            'options' => array(
                'label' => 'Seção'
            )
        ));

        $this->add(array(
            'name' => 'orgaoEmissorRg',
            'attributes' => array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'class' => 'form-control chosen-select',
                'style' => 'height:100px;',
            ),
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Orgão Emissor Rg:',
                'empty_option' => 'Selecione',
                'object_manager' => $objectManager,
                'target_class' => 'Usuario\Entity\OrgaoEmissorRg',
                'property' => 'nome'
            ),
        ));

        $this->add(array(
            'name' => 'certidaoNascimento',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Certidão de Nascimento',
                'id' => 'certidaoNascimento'
            ),
            'options' => array(
                'label' => 'Certidão de Nascimento'
            )
        ));

    }
}