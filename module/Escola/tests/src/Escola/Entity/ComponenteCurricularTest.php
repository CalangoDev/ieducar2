<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 20:33
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class ComponenteCurricularTest extends EntityTestCase
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
        $componente = new ComponenteCurricular();
        $if = $componente->getInputFilter();
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
        $this->assertTrue($if->has('abreviatura'));
        $this->assertTrue($if->has('tipoBase'));
        $this->assertTrue($if->has('areaConhecimento'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {

        $componente = $this->buildComponenteCurricular();
        $this->em->persist($componente);
        $this->em->flush();

        $this->assertNotNull($componente->getId());
        $this->assertEquals(1, $componente->getId());

        /**
         * get row from database
         */
        $savedComponente = $this->em->find(get_class($componente), $componente->getId());

        $this->assertEquals(1, $savedComponente->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $componente = $this->buildComponenteCurricular();
        $componente->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($componente);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $componente = $this->buildComponenteCurricular();
        $this->em->persist($componente);
        $this->em->flush();

        $savedComponente = $this->em->find(get_class($componente), $componente->getId());
        $this->assertEquals('Integral', $savedComponente->getNome());
        $savedComponente->setNome('Integral X');

        $this->em->flush();

        $savedComponente = $this->em->find(get_class($componente), $savedComponente->getId());
        $this->assertEquals('Integral X', $savedComponente->getNome());

    }

    public function testDelete()
    {

        $componente = $this->buildComponenteCurricular();
        $this->em->persist($componente);
        $this->em->flush();

        $id = $componente->getId();
        $savedComponente = $this->em->find(get_class($componente), $id);
        $this->em->remove($savedComponente);
        $this->em->flush();

        $savedComponente = $this->em->find(get_class($componente), $id);
        $this->assertNull($savedComponente);

    }

    private function buildAreaConhecimento()
    {
        $area = new \Escola\Entity\AreaConhecimento();
        $area->setNome('Nome da Area');
        $area->setSecao('Seção da area');

        return $area;
    }

    private function buildComponenteCurricular()
    {
        $componente = new ComponenteCurricular();
        $componente->setNome('Integral');
        $componente->setAbreviatura('Int');
        $componente->setTipoBase(1);
        $area = $this->buildAreaConhecimento();
        $componente->setAreaConhecimento($area);

        return $componente;
    }

}