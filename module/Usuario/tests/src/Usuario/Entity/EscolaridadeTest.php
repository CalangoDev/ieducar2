<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Escolaridade;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class EscolaridadeTest extends EntityTestCase
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
		$escolaridade = $this->buildEscolaridade();
		$this->em->persist($escolaridade);
		$this->em->flush();

		$this->assertNotNull($escolaridade->getId());
		$this->assertEquals(1, $escolaridade->getId());

		/**
		 * Buscando no banco de dados a escolaridade que foi cadastrada
		 */
		$savedEscolaridade = $this->em->find(get_class($escolaridade), $escolaridade->getId());

		$this->assertInstanceOf(get_class($escolaridade), $savedEscolaridade);
		$this->assertEquals($escolaridade->getId(), $savedEscolaridade->getId());
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidDescricao()
	{		
		$escolaridade = $this->buildEscolaridade();		
		$escolaridade->setDescricao('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois 
		paga. Sapien in monti palavris qui num significa nadis i 
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
		
		$this->em->persist($escolaridade);
		$this->em->flush();		
	}

	public function testUpdate()
	{
		$escolaridade = $this->buildEscolaridade();
		$this->em->persist($escolaridade);

		$savedEscolaridade = $this->em->find('Usuario\Entity\Escolaridade', $escolaridade->getId());

		$this->assertEquals('Fundamental Incompleto', $escolaridade->getDescricao());

		$savedEscolaridade->setDescricao('NÍVEL V');

		$this->em->persist($savedEscolaridade);
		$this->em->flush();

		$savedEscolaridade = $this->em->find('Usuario\Entity\Escolaridade', $savedEscolaridade->getId());

		$this->assertEquals('NÍVEL V', $savedEscolaridade->getDescricao());
	}

	public function testDelete()
	{
		$escolaridade = $this->buildEscolaridade();
		$this->em->persist($escolaridade);
		$this->em->flush();

		$id = $escolaridade->getId();
		$savedEscolaridade = $this->em->find('Usuario\Entity\Escolaridade', $id);

		$this->em->remove($escolaridade);
		$this->em->flush();

		$savedEscolaridade = $this->em->find('Usuario\Entity\Escolaridade', $id);
		$this->assertNull($savedEscolaridade);

	}

	private function buildEscolaridade()
	{
		$escolaridade = new Escolaridade;
		$escolaridade->setDescricao('Fundamental Incompleto');

		return $escolaridade;
	}
	
}