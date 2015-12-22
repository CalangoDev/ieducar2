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
        $this->assertTrue($if->has('instituicao'));
        $this->assertTrue($if->has('ativo'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {
        $instituicao = $this->buildInstituicao();
        $this->em->persist($instituicao);
		$tipoRegime = $this->buildTipoRegime();
		$tipoRegime->setInstituicao($instituicao);
		$this->em->persist($tipoRegime);
        $this->em->flush();

        $this->assertNotNull($tipoRegime->getId());
        $this->assertEquals(1, $tipoRegime->getId());

        /**
         * get row from database
         */
        $savedTipoRegime = $this->em->find(get_class($tipoRegime), $tipoRegime->getId());

        $this->assertEquals(1, $savedTipoRegime->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $instituicao = $this->buildInstituicao();
		$tipoRegime = $this->buildTipoRegime();
        $tipoRegime->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
		$tipoRegime->setInstituicao($instituicao);
        $this->em->persist($tipoRegime);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $instituicao = $this->buildInstituicao();
		$tipoRegime = $this->buildTipoRegime();
		$tipoRegime->setInstituicao($instituicao);
        $this->em->persist($tipoRegime);
        $this->em->flush();

        $savedTipoRegime = $this->em->find(get_class($tipoRegime), $tipoRegime->getId());
        $this->assertEquals('Integral', $savedTipoRegime->getNome());
        $savedInstituicao->setNome('Integral X');

        $this->em->flush();

        $savedTipoRegime = $this->em->find(get_class($tipoRegime), $savedTipoRegime->getId());
        $this->assertEquals('Integral X', $savedTipoRegime->getNome());

    }

    public function testDelete()
    {
        $instituicao = $this->buildInstituicao();
		$tipoRegime = $this->buildTipoRegime();
		$tipoRegime->setInstituicao($instituicao);
        $this->em->persist($tipoRegime);
        $this->em->flush();

        $id = $tipoRegime->getId();
        $savedTipoRegime = $this->em->find(get_class($tipoRegime), $id);
        $this->em->remove($savedTipoRegime);
        $this->em->flush();

        $savedTipoRegime = $this->em->find(get_class($tipoRegime), $id);
        $this->assertNull($savedTipoRegime);

    }

    private function buildTipoRegime() 
	{
		$tipoRegime = new TipoRegime();
		$tipoRegime->setNome('Integral');
		
		return $tipoRegime;
	}

    private function buildInstituicao()
    {
        $instituicao = new Instituicao();
        $instituicao->setNome('Prefeitura Municipal Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
    }
}
