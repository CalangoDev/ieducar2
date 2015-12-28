<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/12/15
 * Time: 23:21
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class NivelEnsinoTest extends EntityTestCase
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
        $nivelEnsino = new NivelEnsino();
        $if = $nivelEnsino->getInputFilter();
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
        $this->assertTrue($if->has('instituicao'));
        $this->assertTrue($if->has('ativo'));
        $this->assertTrue($if->has('descricao'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {
        $nivelEnsino = $this->buildNivelEnsino();
        $this->em->persist($nivelEnsino);
        $this->em->flush();

        $this->assertNotNull($nivelEnsino->getId());
        $this->assertEquals(1, $nivelEnsino->getId());

        /**
         * get row from database
         */
        $savedNivelEnsino = $this->em->find(get_class($nivelEnsino), $nivelEnsino->getId());

        $this->assertEquals(1, $savedNivelEnsino->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $nivelEnsino = $this->buildNivelEnsino();
        $nivelEnsino->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($nivelEnsino);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $nivelEnsino = $this->buildNivelEnsino();
        $this->em->persist($nivelEnsino);
        $this->em->flush();

        $savedNivelEnsino = $this->em->find(get_class($nivelEnsino), $nivelEnsino->getId());
        $this->assertEquals('Integral', $savedNivelEnsino->getNome());
        $savedNivelEnsino->setNome('Integral X');

        $this->em->flush();

        $savedNivelEnsino = $this->em->find(get_class($nivelEnsino), $savedNivelEnsino->getId());
        $this->assertEquals('Integral X', $savedNivelEnsino->getNome());

    }

    public function testDelete()
    {
        $nivelEnsino = $this->buildNivelEnsino();
        $this->em->persist($nivelEnsino);
        $this->em->flush();

        $id = $nivelEnsino->getId();
        $savedNivelEnsino = $this->em->find(get_class($nivelEnsino), $id);
        $this->em->remove($savedNivelEnsino);
        $this->em->flush();

        $savedNivelEnsino = $this->em->find(get_class($nivelEnsino), $id);
        $this->assertNull($savedNivelEnsino);

    }

    private function buildNivelEnsino()
    {
        $nivelEnsino = new NivelEnsino();
        $nivelEnsino->setNome('Integral');
        $nivelEnsino->setDescricao('Descrição');
        $instituicao = $this->buildInstituicao();
        $nivelEnsino->setInstituicao($instituicao);

        return $nivelEnsino;
    }

    private function buildInstituicao()
    {
        $instituicao = new Instituicao();
        $instituicao->setNome('Prefeitura Municipal Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
    }

}