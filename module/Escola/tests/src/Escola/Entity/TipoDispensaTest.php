<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 16:13
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class TipoDispensaTest extends EntityTestCase
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
        $tipo = new TipoDispensa();
        $if = $tipo->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(4, $if->count());

        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('nome'));
        $this->assertTrue($if->has('descricao'));
        $this->assertTrue($if->has('ativo'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {

        $tipo = $this->buildTipoDispensa();
        $this->em->persist($tipo);
        $this->em->flush();

        $this->assertNotNull($tipo->getId());
        $this->assertEquals(1, $tipo->getId());

        /**
         * get row from database
         */
        $savedTipo = $this->em->find(get_class($tipo), $tipo->getId());

        $this->assertEquals(1, $savedTipo->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $tipo = $this->buildTipoDispensa();
        $tipo->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($tipo);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $tipo = $this->buildTipoDispensa();
        $this->em->persist($tipo);
        $this->em->flush();

        $savedTipo = $this->em->find(get_class($tipo), $tipo->getId());
        $this->assertEquals('Integral', $savedTipo->getNome());
        $savedTipo->setNome('Integral X');

        $this->em->flush();

        $savedTipo = $this->em->find(get_class($tipo), $savedTipo->getId());
        $this->assertEquals('Integral X', $savedTipo->getNome());

    }

    public function testDelete()
    {

        $tipo = $this->buildTipoDispensa();
        $this->em->persist($tipo);
        $this->em->flush();

        $id = $tipo->getId();
        $savedTipo = $this->em->find(get_class($tipo), $id);
        $this->em->remove($savedTipo);
        $this->em->flush();

        $savedTipo = $this->em->find(get_class($tipo), $id);
        $this->assertNull($savedTipo);

    }

    private function buildTipoDispensa()
    {
        $tipo = new TipoDispensa();
        $tipo->setNome('Integral');
        $tipo->setDescricao('Descrição da Dispensa');
        $tipo->setAtivo(true);

        return $tipo;
    }

}