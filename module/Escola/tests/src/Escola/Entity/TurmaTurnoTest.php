<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 13/09/16
 * Time: 09:39
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class TurmaTurnoTest extends EntityTestCase
{
    public function setup()
    {
        parent::setup();
    }

    /**
     * @return Zend\InputFilter\InputFilter
     */
    public function testGetInputFilter()
    {
        $entity = new TurmaTurno();
        $if = $entity->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFiltervalid($if)
    {
        $this->assertEquals(3, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('nome'));
        $this->assertTrue($if->has('ativo'));
    }

    public function testInsert()
    {
        $entity = $this->buildTurmaTurno();
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
        $entity = $this->buildTurmaTurno();
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
        $entity = $this->buildTurmaTurno();
        $this->em->persist($entity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('Matutino', $savedEntity->getNome());
        $savedEntity->setNome('Noturno');
        $this->em->flush();
        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('Noturno', $savedEntity->getNome());
    }

    public function testDelete()
    {
        $entity = $this->buildTurmaTurno();
        $this->em->persist($entity);
        $this->em->flush();

        $id = $entity->getId();
        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->em->remove($savedEntity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->assertNull($savedEntity);
    }

    private function buildTurmaTurno()
    {
        $entity = new TurmaTurno();
        $entity->setNome('Matutino');
        $entity->setAtivo(true);

        return $entity;
    }
}