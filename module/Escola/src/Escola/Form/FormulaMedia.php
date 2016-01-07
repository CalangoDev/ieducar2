<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/01/16
 * Time: 15:12
 */
namespace Escola\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class FormulaMedia extends Form
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('formula-media');
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '/escola/formula-media/save');
        $this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new \Escola\Entity\FormulaMedia());
        $this->setUseInputFilterDefaults(false);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'nome',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Nome: '
            )
        ));

        $this->add(array(
            'name' => 'formulaMedia',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Fórmula de média final: ',
                'help' => 'A fórmula de cálculo.<br>
                Variáveis disponíveis:<br>
                · En - Etapa n (de 1 a 10)<br>
                · Et - Total de etapas<br>
                · Se - Soma das notas das etapas<br>
                · Rc - Nota da recuperação<br>
                Símbolos disponíveis:<br>
                · (), +, /, *, x<br>
                A variável "Rc" está disponível apenas quando Tipo de fórmula for "Recuperação".'
            )
        ));

        $this->add(array(
            'name' => 'tipoFormula',
            'options' => array(
                'label' => 'Tipo de Fórmula: ',
                'value_options' => array(
                    '1'	=> 'Média Final',
                    '2' => 'Média para Recuperação',
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