<?php
/**
 * Created by Vim.
 * User: eduardojunior
 * Date: 23/12/15
 * Time: 00:23
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class Instituicao extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('instituicao');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/instituicao/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\Instituicao());

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
                'label' => 'Nome:'
            ),
        ));

        $this->add(array(
            'name' => 'responsavel',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Responsável Instituição:'
            ),
        ));

        $enderecoExternoFieldset = new \Usuario\Form\EnderecoExternoFieldset($objectManager);
        $enderecoExternoFieldset->setLabel('Endereço');
        $enderecoExternoFieldset->setName('enderecoExterno');
        $enderecoExternoFieldset->setUseAsBaseFieldset(false);
        $this->add($enderecoExternoFieldset);

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
            'name' => 'ativo',
            'options' => array(
                'label' => 'Ativo',
                'value_options' => array(
                    true	=> 'Ativo',
                    false => 'Inativo',
                ),
            ),
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'type' => 'Zend\Form\Element\Select',
                'value' => true,
                'class' => 'form-control'
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
