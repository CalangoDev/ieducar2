<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 11/05/16
 * Time: 10:17
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class PredioTest extends EntityTestCase
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
        $predio = new Predio();
        $if = $predio->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(5, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('nome'));
        $this->assertTrue($if->has('descricao'));
        $this->assertTrue($if->has('endereco'));
        $this->assertTrue($if->has('ativo'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {
        $row = $this->buildPredio();
        $this->em->persist($row);
        $this->em->flush();

        $this->assertNotNull($row->getId());
        $this->assertEquals(1, $row->getId());

        /**
         * get row from database
         */
        $savedRow = $this->em->find(get_class($row), $row->getId());

        $this->assertEquals(1, $savedRow->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $row = $this->buildPredio();
        $row->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis');
        $this->em->persist($row);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $row = $this->buildPredio();
        $this->em->persist($row);
        $this->em->flush();

        $savedRow = $this->em->find(get_class($row), $row->getId());
        $this->assertEquals('Predio Figueredo', $savedRow->getNome());
        $savedRow->setNome('Prédio Y');
        $this->em->flush();

        $savedRow = $this->em->find(get_class($row), $savedRow->getId());
        $this->assertEquals('Prédio Y', $savedRow->getNome());
    }

    public function testDelete()
    {
        $row = $this->buildPredio();
        $this->em->persist($row);
        $this->em->flush();

        $id = $row->getId();
        $savedRow = $this->em->find(get_class($row), $id);
        $this->em->remove($savedRow);
        $this->em->flush();

        $savedRow = $this->em->find(get_class($row), $id);
        $this->assertNull($savedRow);

    }

    private function buildPredio()
    {
        $predio = new Predio();
        $predio->setNome('Predio Figueredo');
        $predio->setDescricao('Descrição do prédio');
        $predio->setEndereco('Rua XYZ');
        $predio->setAtivo(true);

        return $predio;
    }
}