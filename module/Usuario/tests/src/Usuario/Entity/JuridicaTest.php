<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Juridica;
use Usuario\Entity\Pessoa;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class JuridicaTest extends EntityTestCase
{
	public function setup()
	{
		parent::setup();
	}

	/**
	 * Verificando se existem filtros
	 */
	public function testGetInputFilter()
	{
		$juridica = new Juridica();
		$if = $juridica->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);
		return $if;
	}

	/**
	 * @depends testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		//testa os filtros
		
		$this->assertEquals(13, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('cnpj'));
		$this->assertTrue($if->has('insc_estadual'));
		$this->assertTrue($if->has('idpes_rev'));
		$this->assertTrue($if->has('data_rev'));
		$this->assertTrue($if->has('origem_gravacao'));
		$this->assertTrue($if->has('idpes_cad'));
		$this->assertTrue($if->has('data_cad'));
		$this->assertTrue($if->has('operacao'));
		$this->assertTrue($if->has('idsis_rev'));
		$this->assertTrue($if->has('idsis_cad'));
		$this->assertTrue($if->has('fantasia'));
		$this->assertTrue($if->has('capital_social'));
	}
}