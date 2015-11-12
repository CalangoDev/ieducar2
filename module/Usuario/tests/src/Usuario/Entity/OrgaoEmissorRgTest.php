<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 11/11/15
 * Time: 21:42
 */
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\OrgaoEmissorRg;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class OrgaoEmissorRgTest extends EntityTestCase
{
    public function setup()
    {
        parent::setup();
    }

    /**
     * Verificando se existem filtros
     */
    public function testGetInputFilter()
    {
        $orgaoEmissor = new OrgaoEmissorRg();
        $if = $orgaoEmissor->getInputFilter();
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
        $this->assertTrue($if->has('sigla'));
        $this->assertTrue($if->has('descricao'));
    }

    /**
     * Teste de inserção de um Orgão Emissor de Rg
     */
    public function testInsert()
    {
        $orgaoEmissor = $this->buildOrgaoEmissorRg();
        $this->em->persist($orgaoEmissor);
        $this->em->flush();

        $savedOrgaoEmissor = $this->em->find('Usuario\Entity\OrgaoEmissorRg', $orgaoEmissor->getId());
        $this->assertEquals(1, $savedOrgaoEmissor->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidSigla()
    {
        $orgaoEmissor = $this->buildOrgaoEmissorRg();
        $orgaoEmissor->setSigla('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($orgaoEmissor);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidDescricao()
    {
        $orgaoEmissor = $this->buildOrgaoEmissorRg();
        $orgaoEmissor->setDescricao('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($orgaoEmissor);
        $this->em->flush();
    }

    /**
     * Teste de Update
     */
    public function testUpdate()
    {
        $orgaoEmissorRg = $this->buildOrgaoEmissorRg();
        $this->em->persist($orgaoEmissorRg);
        $this->em->flush();

        $savedOrgaoEmissorRg = $this->em->find('Usuario\Entity\OrgaoEmissorRg', $orgaoEmissorRg->getId());

        $this->assertEquals('SSP', $savedOrgaoEmissorRg->getSigla());

        $savedOrgaoEmissorRg->setSigla('PF');
        $savedOrgaoEmissorRg->setDescricao('Polícia Federal');
        $this->em->flush();

        $savedOrgaoEmissorRg = $this->em->find('Usuario\Entity\OrgaoEmissorRg', $orgaoEmissorRg->getId());

        $this->assertEquals('PF', $savedOrgaoEmissorRg->getSigla());
    }

    public function testDelete()
    {
        $orgaoEmissorRg = $this->buildOrgaoEmissorRg();
        $this->em->persist($orgaoEmissorRg);
        $this->em->flush();

        $id = $orgaoEmissorRg->getId();

        $savedOrgaoEmissorRg = $this->em->find('Usuario\Entity\OrgaoEmissorRg', $id);
        $this->em->remove($savedOrgaoEmissorRg);
        $this->em->flush();

        $savedOrgaoEmissorRg = $this->em->find('Usuario\Entity\OrgaoEmissorRg', $id);
        $this->assertNull($savedOrgaoEmissorRg);
    }

    private function buildOrgaoEmissorRg()
    {
        $orgaoEmissor = new OrgaoEmissorRg();
        $orgaoEmissor->setSigla('SSP');
        $orgaoEmissor->setDescricao('SSP');

        return $orgaoEmissor;
    }
}