<?php
namespace Escola\Form;

use Escola\Entity\EscolaSerieDisciplina;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class EscolaSerieDisciplinaFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct(ObjectManager $objectManager)
	{
		parent::__construct('escola-serie-disciplina');

		$this->setHydrator(new DoctrineHydrator($objectManager))->setObject(new EscolaSerieDisciplina());

		$this->add([
			'name' => 'id',
			'attributes' => [
				'type' => 'hidden',
				'value' => 0,
			],
		]);		

		//componentCurricular
		//escolaSerie
		//cargaHoraria
		$this->add([
			'name' => 'componenteCurricular',
			'attributes' => [
				'type' => 'Zend\Form\Element\Checkbox',
			],
			'type' => 'Zend\Form\Element\Checkbox',
			'options' => [
				'label' => 'Nome Disciplina',
			],
		]);


		$this->add([
			'name' => 'escolaSerie',
			'attributes' => [
				'type' => 'hidden',
			],
		]);


		$this->add([
			'name' => 'cargaHoraria',
			'attributes' => [
				'type' => 'text',
			],
			'options' => [
				'label' => 'Carga Horária',
			]
		]);


	}

	public function getInputFilterSpecification()
	{
		return [
			'id' => [
				'required' => false
			]
		];
	}
}
