<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 04/05/16
 * Time: 10:00
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class ModuloTest extends EntityTestCase
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
        $modulo = new Modulo();
        $if = $modulo->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(6, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('nome'));
        $this->assertTrue($if->has('descricao'));
        $this->assertTrue($if->has('numeroMeses'));
        $this->assertTrue($if->has('numeroSemanas'));
        $this->assertTrue($if->has('ativo'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {
        $modulo = $this->buildModulo();
        $this->em->persist($modulo);
        $this->em->flush();

        $this->assertNotNull($modulo->getId());
        $this->assertEquals(1, $modulo->getId());

        /**
         * get row from database
         */
        $savedModulo = $this->em->find(get_class($modulo), $modulo->getId());

        $this->assertEquals(1, $savedModulo->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $modulo = $this->buildModulo();
        $modulo->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis');
        $this->em->persist($modulo);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $modulo = $this->buildModulo();
        $this->em->persist($modulo);
        $this->em->flush();

        $savedModulo = $this->em->find(get_class($modulo), $modulo->getId());
        $this->assertEquals('Modulo X', $savedModulo->getNome());
        $savedModulo->setNome('Modulo Y');
        $this->em->flush();

        $savedModulo = $this->em->find(get_class($modulo), $savedModulo->getId());
        $this->assertEquals('Modulo Y', $savedModulo->getNome());
    }

    public function testDelete()
    {
        $modulo = $this->buildModulo();
        $this->em->persist($modulo);
        $this->em->flush();

        $id = $modulo->getId();
        $savedModulo = $this->em->find(get_class($modulo), $id);
        $this->em->remove($savedModulo);
        $this->em->flush();

        $savedModulo = $this->em->find(get_class($modulo), $id);
        $this->assertNull($savedModulo);

    }

    private function buildModulo()
    {
        $modulo = new Modulo();
        $modulo->setNome('Modulo X');
        $modulo->setDescricao('Descrição');
        $modulo->setNumeroMeses(10);
        $modulo->setNumeroSemanas(30);
        $modulo->setAtivo(true);

        return $modulo;
    }
}