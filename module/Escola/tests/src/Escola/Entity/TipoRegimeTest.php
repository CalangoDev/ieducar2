<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/12/15
 * Time: 08:32
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class TipoRegimeTest extends EntityTestCase
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
        $tipoRegime = new TipoRegime();
        $if = $instituicao->getInputFilter();
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
        $this->assertTrue($if->has('responsavel'));
        $this->assertTrue($if->has('enderecoExterno'));
        $this->assertTrue($if->has('telefones'));
        $this->assertTrue($if->has('ativo'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {
        $telefones = $this->buildTelefones();
        $instituicao = $this->buildInstituicao();
        $instituicao->addTelefones($telefones);

        $this->em->persist($instituicao);
        $this->em->flush();

        $this->assertNotNull($instituicao->getId());
        $this->assertEquals(1, $instituicao->getId());

        /**
         * get row from database
         */
        $savedInstituicao = $this->em->find(get_class($instituicao), $instituicao->getId());

        $this->assertEquals(1, $savedInstituicao->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $instituicao = $this->buildInstituicao();
        $instituicao->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($instituicao);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $instituicao = $this->buildInstituicao();
        $this->em->persist($instituicao);
        $this->em->flush();

        $savedInstituicao = $this->em->find(get_class($instituicao), $instituicao->getId());
        $this->assertEquals('Prefeitura Municipal Modelo', $savedInstituicao->getNome());
        $savedInstituicao->setNome('Prefeitura Municipal Modelo X');

        $this->em->flush();

        $savedInstituicao = $this->em->find(get_class($instituicao), $savedInstituicao->getId());
        $this->assertEquals('Prefeitura Municipal Modelo X', $savedInstituicao->getNome());

    }

    public function testDelete()
    {
        $instituicao = $this->buildInstituicao();
        $this->em->persist($instituicao);
        $this->em->flush();

        $id = $instituicao->getId();
        $savedInstituicao = $this->em->find(get_class($instituicao), $id);
        $this->em->remove($savedInstituicao);
        $this->em->flush();

        $savedInstituicao = $this->em->find(get_class($instituicao), $id);
        $this->assertNull($savedInstituicao);

    }

    private function buildTelefones()
    {
        $telefones = new ArrayCollection();

        $telefone = new \Usuario\Entity\Telefone();
        $telefone->setDdd('74');
        $telefone->setNumero('12345678');

        $telefones->add($telefone);

        $telefone2 = new \Usuario\Entity\Telefone();
        $telefone2->setDdd('74');
        $telefone2->setNumero('87654321');
        $telefones->add($telefone2);

        return $telefones;

    }

    private function buildInstituicao()
    {
        $instituicao = new Instituicao();
        $instituicao->setNome('Prefeitura Municipal Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
    }
}
