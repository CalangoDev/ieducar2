<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 10/09/16
 * Time: 09:38
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class TipoTurmaTest extends EntityTestCase
{
    public function setup()
    {
        parent::setup();
    }

    /**
     * Check if filters exists
     *
     * @return Zend\InputFilter\InputFilter
     */
    public function testGetInputFilter()
    {
        $entity = new TipoTurma();
        $if = $entity->getInputFilter();
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
        $this->assertTrue($if->has('sigla'));
    }

    public function testInsert()
    {
        $entity = $this->buildTipoTurma();
        $this->em->persist($entity);
        $this->em->flush();

        $this->assertNotNull($entity->getId());
        $this->assertEquals(1, $entity->getId());

        /**
         * get row from database
         */
        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals(1, $savedEntity->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $entity = $this->buildTipoTurma();
        $entity->setNome("Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.");
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $entity = $this->buildTipoTurma();
        $this->em->persist($entity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('Tipo de Turma', $savedEntity->getNome());
        $savedEntity->setNome('Novo Tipo de Turma');
        $this->em->flush();
        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('Novo Tipo de Turma', $savedEntity->getNome());
    }

    public function testDelete()
    {
        $entity = $this->buildTipoTurma();
        $this->em->persist($entity);
        $this->em->flush();

        $id = $entity->getId();
        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->em->remove($savedEntity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->assertNull($savedEntity);
    }

    private function buildTipoTurma()
    {
        $entity = new TipoTurma();
        $entity->setNome('Tipo de Turma');
        $entity->setSigla('TT');

        return $entity;
    }

}