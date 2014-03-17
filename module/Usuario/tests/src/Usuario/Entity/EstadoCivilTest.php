<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\EstadoCivil;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class EstadoCivilTest extends EntityTestCase
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
		$ec = $this->buildEstadoCivil();
		$this->em->persist($ec);
		$this->em->flush();

		$this->assertNotNull($ec->getId());
		$this->assertEquals(1, $ec->getId());

		/**
		 * Buscando no banco de dados o estado civil cadastrado
		 */
		$savedEc = $this->em->find(get_class($ec), $ec->getId());

		$this->assertInstanceOf(get_class($ec), $savedEc);
		$this->assertEquals($ec->getId(), $savedEc->getId());
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidDescricao()
	{		
		$ec = $this->buildEstadoCivil();		
		$ec->setDescricao('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i 
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
		$this->em->persist($ec);
		$this->em->flush();		
	}

	public function testUpdate()
	{
		$ec = $this->buildEstadoCivil();
		$this->em->persist($ec);		

		$savedEc = $this->em->find('Usuario\Entity\EstadoCivil', $ec->getId());
		

		$this->assertEquals('Solteiro(a)', $ec->getDescricao());

		$savedEc->setDescricao('Casado(a)');

		$this->em->persist($savedEc);
		$this->em->flush();

		$savedEc = $this->em->find('Usuario\Entity\EstadoCivil', $savedEc->getId());

		$this->assertEquals('Casado(a)', $savedEc->getDescricao());
	}

	public function testDelete()
	{
		$ec = $this->buildEstadoCivil();
		$this->em->persist($ec);
		$this->em->flush();

		$id = $ec->getId();
		$savedEc = $this->em->find('Usuario\Entity\EstadoCivil', $id);

		$this->em->remove($ec);
		$this->em->flush();

		$savedEc = $this->em->find('Usuario\Entity\EstadoCivil', $id);
		$this->assertNull($savedEc);

	}

	private function buildEstadoCivil()
	{
		$ec = new EstadoCivil;
		$ec->setDescricao('Solteiro(a)');

		return $ec;
	}
	
}