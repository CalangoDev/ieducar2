<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/12/15
 * Time: 08:32
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class TipoRegimeTest extends EntityTestCase
{
    public function setup()
    {
        parent::setup();
    }

    /**
     * Check if filters exists
     */
    public function testGetInputFilter()
    {
        $tipoRegime = new TipoRegime();
        $if = $tipoRegime->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(3, $if->count());

        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('nome'));
        $this->assertTrue($if->has('ativo'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {
		$tipoRegime = $this->buildTipoRegime();
		$this->em->persist($tipoRegime);
        $this->em->flush();

        $this->assertNotNull($tipoRegime->getId());
        $this->assertEquals(1, $tipoRegime->getId());

        /**
         * get row from database
         */
        $savedTipoRegime = $this->em->find(get_class($tipoRegime), $tipoRegime->getId());

        $this->assertEquals(1, $savedTipoRegime->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
		$tipoRegime = $this->buildTipoRegime();
        $tipoRegime->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($tipoRegime);
        $this->em->flush();
    }

    public function testUpdate()
    {
		$tipoRegime = $this->buildTipoRegime();
        $this->em->persist($tipoRegime);
        $this->em->flush();

        $savedTipoRegime = $this->em->find(get_class($tipoRegime), $tipoRegime->getId());
        $this->assertEquals('Integral', $savedTipoRegime->getNome());
        $savedTipoRegime->setNome('Integral X');

        $this->em->flush();

        $savedTipoRegime = $this->em->find(get_class($tipoRegime), $savedTipoRegime->getId());
        $this->assertEquals('Integral X', $savedTipoRegime->getNome());

    }

    public function testDelete()
    {
		$tipoRegime = $this->buildTipoRegime();
        $this->em->persist($tipoRegime);
        $this->em->flush();

        $id = $tipoRegime->getId();
        $savedTipoRegime = $this->em->find(get_class($tipoRegime), $id);
        $this->em->remove($savedTipoRegime);
        $this->em->flush();

        $savedTipoRegime = $this->em->find(get_class($tipoRegime), $id);
        $this->assertNull($savedTipoRegime);

    }

    private function buildTipoRegime() 
	{
		$tipoRegime = new TipoRegime();
		$tipoRegime->setNome('Integral');
		
		return $tipoRegime;
	}

}
