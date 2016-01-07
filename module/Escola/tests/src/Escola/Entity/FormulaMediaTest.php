<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/01/16
 * Time: 09:17
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class FormulaMediaTest extends EntityTestCase
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
        $formulaMedia = new FormulaMedia();
        $if = $formulaMedia->getInputFilter();
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
        $this->assertTrue($if->has('formulaMedia'));
        $this->assertTrue($if->has('tipoFormula'));
    }

    /**
     * teste Insert data
     */
    public function testInsert()
    {
        $formulaMedia = $this->buildFormulaMedia();
        $this->em->persist($formulaMedia);
        $this->em->flush();

        $this->assertNotNull($formulaMedia->getId());
        $this->assertEquals(1, $formulaMedia->getId());

        /**
         * get row from database
         */
        $savedFormulaMedia = $this->em->find(get_class($formulaMedia), $formulaMedia->getId());

        $this->assertEquals(1, $savedFormulaMedia->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $formulaMedia = $this->buildFormulaMedia();
        $formulaMedia->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($formulaMedia);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $formulaMedia = $this->buildFormulaMedia();
        $this->em->persist($formulaMedia);
        $this->em->flush($formulaMedia);

        $savedFormulaMedia = $this->em->find(get_class($formulaMedia), $formulaMedia->getId());
        $this->assertEquals('nome da formula', $savedFormulaMedia->getNome());
        $savedFormulaMedia->setNome('Outra Formula');
        $this->em->flush();

        $savedFormulaMedia = $this->em->find(get_class($formulaMedia), $formulaMedia->getId());
        $this->assertEquals('Outra Formula', $savedFormulaMedia->getNome());
    }

    public function testDelete()
    {
        $formulaMedia = $this->buildFormulaMedia();
        $this->em->persist($formulaMedia);
        $this->em->flush();

        $id = $formulaMedia->getId();
        $savedFormulaMedia = $this->em->find(get_class($formulaMedia), $id);
        $this->em->remove($savedFormulaMedia);
        $this->em->flush();

        $savedFormulaMedia = $this->em->find(get_class($formulaMedia), $id);
        $this->assertNull($savedFormulaMedia);
    }

    private function buildFormulaMedia()
    {
        $formulaMedia = new FormulaMedia();
        $formulaMedia->setNome('nome da formula');
        //$formulaMedia->setFormulaMedia('Se / Et');
        //$formulaMedia->setFormulaMedia('(Rc * 0.4) + ((Se / Et) * 0.6)');
        $formulaMedia->setFormulaMedia('(Rc * 4) + ((Se / Et) * 6)');
        $formulaMedia->setTipoFormula(1);

        return $formulaMedia;
    }
}