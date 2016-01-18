<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/01/16
 * Time: 20:48
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class RedeEnsinoTest extends EntityTestCase
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
        $redeEnsino = new RedeEnsino();
        $if = $redeEnsino->getInputFilter();
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
        $this->assertTrue($if->has('instituicao'));
        $this->assertTrue($if->has('ativo'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {

        $redeEnsino = $this->buildRedeEnsino();
        $this->em->persist($redeEnsino);
        $this->em->flush();

        $this->assertNotNull($redeEnsino->getId());
        $this->assertEquals(1, $redeEnsino->getId());

        /**
         * get row from database
         */
        $savedRedeEnsino = $this->em->find(get_class($redeEnsino), $redeEnsino->getId());

        $this->assertEquals(1, $savedRedeEnsino->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $redeEnsino = $this->buildRedeEnsino();
        $redeEnsino->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($redeEnsino);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $redeEnsino = $this->buildRedeEnsino();
        $this->em->persist($redeEnsino);
        $this->em->flush();

        $savedRedeEnsino = $this->em->find(get_class($redeEnsino), $redeEnsino->getId());
        $this->assertEquals('Municipal', $savedRedeEnsino->getNome());
        $savedRedeEnsino->setNome('Integral X');

        $this->em->flush();

        $savedRedeEnsino = $this->em->find(get_class($redeEnsino), $savedRedeEnsino->getId());
        $this->assertEquals('Integral X', $savedRedeEnsino->getNome());

    }

    public function testDelete()
    {

        $redeEnsino = $this->buildRedeEnsino();
        $this->em->persist($redeEnsino);
        $this->em->flush();

        $id = $redeEnsino->getId();
        $savedRedeEnsino = $this->em->find(get_class($redeEnsino), $id);
        $this->em->remove($savedRedeEnsino);
        $this->em->flush();

        $savedRedeEnsino = $this->em->find(get_class($redeEnsino), $id);
        $this->assertNull($savedRedeEnsino);

    }

    private function buildInstituicao()
    {
        $instituicao = new Instituicao();
        $instituicao->setNome('Prefeitura Municipal Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
    }

    private function buildRedeEnsino()
    {
        $redeEnsino = new RedeEnsino();
        $redeEnsino->setNome('Municipal');
        $instituicao = $this->buildInstituicao();
        $redeEnsino->setInstituicao($instituicao);
        $redeEnsino->setAtivo(true);

        return $redeEnsino;
    }

}