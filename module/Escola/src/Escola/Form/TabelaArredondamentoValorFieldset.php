<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/01/16
 * Time: 23:31
 */
namespace Escola\Form;

use Escola\Entity\TabelaArredondamentoValor;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class TabelaArredondamentoValorFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('tabela-arredondamento-valor');

        $this->setHydrator(new DoctrineHydrator($objectManager))
            ->setObject(new TabelaArredondamentoValor());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'nome',
            'options' => array(
                'label' => 'Rótulo da nota:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control nome',
            ),

        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'descricao',
            'options' => array(
                'label' => 'Descrição:',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control descricao',
            ),
        ));

        $this->add(array(
            'name' => 'valorMinimo',
            'options' => array(
                'label' => 'Valor Mínimo: ',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control valorMinimo',
            )
        ));

        $this->add(array(
            'name' => 'valorMaximo',
            'options' => array(
                'label' => 'Valor Máximo: ',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control valorMaximo',
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required' => false
            ),

            'nome' => array(
                'required' => true,
                'continue_if_empty' => true,
            ),

            'descricao' => array(
                'required' => false
            ),

            'valorMinimo' => array(
                'required' => true
            ),

            'valorMaximo' => array(
                'required' => true
            )
        );
    }
}