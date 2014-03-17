<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Ocupacao;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class OcupacaoTest extends EntityTestCase
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
		$escolaridade = new Escolaridade();
		$if = $escolaridade->getInputFilter();
		$this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

		return $if;
	}

	/**
	 * @depends	testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		$this->assertEquals(2, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('descricao'));
	}

	/**
	 * Teste de insercao de escolaridade
	 */
	public function testInsert()
	{
		$ocupacao = $this->buildOcupacao();
		$this->em->persist($ocupacao);
		$this->em->flush();

		$this->assertNotNull($ocupacao->getId());
		$this->assertEquals(1, $ocupacao->getId());

		/**
		 * Buscando no banco de dados a ocupacao
		 */
		$savedOcupacao = $this->em->find(get_class($ocupacao), $ocupacao->getId());

		$this->assertInstanceOf(get_class($ocupacao), $savedOcupacao);
		$this->assertEquals($ocupacao->getId(), $savedOcupacao->getId());
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidDescricao()
	{		
		$ocupacao = $this->buildOcupacao();
		$ocupacao->setDescricao('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i 
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
		$this->em->persist($ocupacao);
		$this->em->flush();
	}

	public function testUpdate()
	{
		$ocupacao = $this->buildOcupacao();
		$this->em->persist($ocupacao);

		$savedOcupacao = $this->em->find('Usuario\Entity\Ocupacao', $ocupacao->getId());
		

		$this->assertEquals('Ocupacao 1', $ocupacao->getDescricao());

		$savedOcupacao->setDescricao('Ocupacao 2');

		$this->em->persist($savedOcupacao);
		$this->em->flush();

		$savedOcupacao = $this->em->find('Usuario\Entity\Ocupacao', $savedOcupacao->getId());

		$this->assertEquals('Ocupacao 2', $savedOcupacao->getDescricao());
	}

	public function testDelete()
	{
		$ocupacao = $this->buildOcupacao();
		$this->em->persist($ocupacao);
		$this->em->flush();

		$id = $ocupacao->getId();
		$savedOcupacao = $this->em->find('Usuario\Entity\Ocupacao', $id);

		$this->em->remove($ocupacao);
		$this->em->flush();

		$savedOcupacao = $this->em->find('Usuario\Entity\Ocupacao', $id);
		$this->assertNull($savedOcupacao);

	}

	private function buildOcupacao()
	{
		$ec = new Ocupacao;
		$ec->setDescricao('Ocupacao 1');

		return $ec;
	}
	
}