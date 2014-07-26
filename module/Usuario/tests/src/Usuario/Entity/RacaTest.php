<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Raca;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class RacaTest extends EntityTestCase
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
		$raca = new Raca();
		$if = $raca->getInputFilter();
		$this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

		return $if;
	}

	/**
	 * @depends	testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		$this->assertEquals(4, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('nome'));		
		$this->assertTrue($if->has('dataExclusao'));
		$this->assertTrue($if->has('ativo'));
	}

	/**
	 * Teste de insercao de raca
	 */
	public function testInsert()
	{
		$raca = $this->buildRaca();
		$this->em->persist($raca);
		$this->em->flush();

		$this->assertNotNull($raca->getId());
		$this->assertEquals(1, $raca->getId());

		/**
		 * Buscando no banco de dados a raca
		 */
		$savedRaca = $this->em->find(get_class($raca), $raca->getId());

		$this->assertInstanceOf(get_class($raca), $savedRaca);
		$this->assertEquals($raca->getId(), $savedRaca->getId());
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidNome()
	{		
		$raca = $this->buildRaca();
		$raca->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois 
		paga. Sapien in monti palavris qui num significa nadis i 
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
		$this->em->persist($raca);
		$this->em->flush();
	}

	public function testUpdate()
	{
		$raca = $this->buildRaca();
		$this->em->persist($raca);
		$this->em->flush();

		$savedRaca = $this->em->find('Usuario\Entity\Raca', $raca->getId());

		$this->assertEquals('Branca', $raca->getNome());

		$savedRaca->setNome('Preta');
		$this->em->flush();

		$savedRaca = $this->em->find('Usuario\Entity\Raca', $savedRaca->getId());

		$this->assertEquals('Preta', $savedRaca->getNome());
	}

	public function testDelete()
	{
		$raca = $this->buildRaca();
		$this->em->persist($raca);
		$this->em->flush();

		$id = $raca->getId();
		$savedRaca = $this->em->find('Usuario\Entity\Raca', $id);

		$this->em->remove($raca);
		$this->em->flush();

		$savedRaca = $this->em->find('Usuario\Entity\Raca', $id);
		$this->assertNull($savedRaca);

	}

	private function buildRaca()
	{
		$raca = new Raca;
		$raca->setNome('Branca');
		// $raca->setAtivo(true);

		return $raca;
	}
	
}