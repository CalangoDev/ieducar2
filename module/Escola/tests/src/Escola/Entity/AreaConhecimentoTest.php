<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 13/01/16
 * Time: 19:18
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class AreaConhecimentoTest extends EntityTestCase
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
        $area = new AreaConhecimento();
        $if = $area->getInputFilter();
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
        $this->assertTrue($if->has('secao'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {
        $area = $this->buildAreaConhecimento();
        $this->em->persist($area);
        $this->em->flush();

        $this->assertNotNull($area->getId());
        $this->assertEquals(1, $area->getId());

        /**
         * get row from database
         */
        $savedArea = $this->em->find(get_class($area), $area->getId());

        $this->assertEquals(1, $savedArea->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $area = $this->buildAreaConhecimento();
        $area->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($area);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $area = $this->buildAreaConhecimento();
        $this->em->persist($area);
        $this->em->flush();

        $savedArea = $this->em->find(get_class($area), $area->getId());
        $this->assertEquals('Ciências da Natureza', $savedArea->getNome());
        $savedArea->setNome('Ciências Matemáticas');

        $this->em->flush();

        $savedArea = $this->em->find(get_class($area), $savedArea->getId());
        $this->assertEquals('Ciências Matemáticas', $savedArea->getNome());
    }

    public function testDelete()
    {
        $area = $this->buildAreaConhecimento();
        $this->em->persist($area);
        $this->em->flush();

        $id = $area->getId();
        $savedArea = $this->em->find(get_class($area), $id);
        $this->em->remove($savedArea);
        $this->em->flush();

        $savedArea = $this->em->find(get_class($area), $id);
        $this->assertNull($savedArea);

    }

    private function buildAreaConhecimento()
    {
        $area = new AreaConhecimento();
        $area->setNome('Ciências da Natureza');
        $area->setSecao('Lógico Matemático');

        return $area;
    }

}
