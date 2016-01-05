<?php
/**
 * Created by Vim.
 * User: eduardojunior
 * Date: 23/12/15
 * Time: 14:08
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class TipoEnsinoTest extends EntityTestCase
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
        $tipoEnsino = new TipoEnsino();
        $if = $tipoEnsino->getInputFilter();
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

        $tipoEnsino = $this->buildTipoEnsino();
        $this->em->persist($tipoEnsino);
        $this->em->flush();

        $this->assertNotNull($tipoEnsino->getId());
        $this->assertEquals(1, $tipoEnsino->getId());

        /**
         * get row from database
         */
        $savedTipoEnsino = $this->em->find(get_class($tipoEnsino), $tipoEnsino->getId());

        $this->assertEquals(1, $savedTipoEnsino->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $tipoEnsino = $this->buildTipoEnsino();
        $tipoEnsino->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($tipoEnsino);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $tipoEnsino = $this->buildTipoEnsino();
        $this->em->persist($tipoEnsino);
        $this->em->flush();

        $savedTipoEnsino = $this->em->find(get_class($tipoEnsino), $tipoEnsino->getId());
        $this->assertEquals('Integral', $savedTipoEnsino->getNome());
        $savedTipoEnsino->setNome('Integral X');

        $this->em->flush();

        $savedTipoEnsino = $this->em->find(get_class($tipoEnsino), $savedTipoEnsino->getId());
        $this->assertEquals('Integral X', $savedTipoEnsino->getNome());

    }

    public function testDelete()
    {

        $tipoEnsino = $this->buildTipoEnsino();
        $this->em->persist($tipoEnsino);
        $this->em->flush();

        $id = $tipoEnsino->getId();
        $savedTipoEnsino = $this->em->find(get_class($tipoEnsino), $id);
        $this->em->remove($savedTipoEnsino);
        $this->em->flush();

        $savedTipoEnsino = $this->em->find(get_class($tipoEnsino), $id);
        $this->assertNull($savedTipoEnsino);

    }

    private function buildTipoEnsino()
    {
        $tipoEnsino = new TipoEnsino();
        $tipoEnsino->setNome('Integral');

        return $tipoEnsino;
    }

}