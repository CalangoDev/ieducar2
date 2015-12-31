<?php
/**
 * Created by Vim.
 * User: eduardojunior
 * Date: 30/12/15
 * Time: 22:00
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class CursoTest extends EntityTestCase
{
	public fuction setup()
	{
		parent::setup();
	}

	/**
	 * Check if filters exists
	 */
	public function testGetInputFilter()
	{
		$curso = new Curso();
		$if = $instituicao->getInputFilter();
		$this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

		return $if;
	}

	/**
	 * @depends testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		$this->assertEquals(18, $if->count());
		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('nome'));
		// TODO: continue this
	}

}
