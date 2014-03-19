<?php
namespace Portal\Entity;

use Core\Test\EntityTestCase;
use Portal\Entity\Funcionario;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class FuncionarioTest extends EntityTestCase
{
	public function setup()
	{
		parent::setup();
	}

	/**
	 * verificando se existem filtros
	 */
	public function testGetInputFilter()
	{
		$funcionario = new Funcionario();
		$if = $funcionario->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);

		return $if;
	}
}