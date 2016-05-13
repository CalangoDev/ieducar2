<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 13/05/16
 * Time: 08:54
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class ComodoFuncaoTest extends EntityTestCase
{
    public function setup()
    {
        parent::setup();
    }

    /**
     * check if filters exists
     */
    public function testGetInputFilter()
    {
        $entity = new ComodoFuncao();
        $if = $entity->getInputFilter();
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
     * test insert data
     */
    public function testInsert()
    {
        $entity = $this->buildComodoFuncao();
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
        $entity = $this->buildComodoFuncao();
        $entity->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis');
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $entity = $this->buildComodoFuncao();
        $this->em->persist($entity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('Nome', $savedEntity->getNome());
        $savedEntity->setNome('Outro Nome');
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $savedEntity->getId());
        $this->assertEquals('Outro Nome', $savedEntity->getNome());
    }

    public function testDelete()
    {
        $entity = $this->buildComodoFuncao();
        $this->em->persist($entity);
        $this->em->flush();

        $id = $entity->getId();
        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->em->remove($savedEntity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->assertNull($savedEntity);
    }

    /**
     * @return ComodoFuncao
     */
    private function buildComodoFuncao()
    {
        $entity = new ComodoFuncao();
        $entity->setNome('Nome');
        $entity->setDescricao('Descrição');
        $entity->setAtivo(true);

        return $entity;
    }
}