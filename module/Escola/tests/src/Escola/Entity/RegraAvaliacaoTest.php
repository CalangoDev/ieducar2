<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 10/01/16
 * Time: 23:06
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class RegraAvaliacaoTest extends EntityTestCase
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
        $regra = new RegraAvaliacao();
        $if = $regra->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(12, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('nome'));
        $this->assertTrue($if->has('tipoNota'));
        $this->assertTrue($if->has('tipoProgressao'));
        $this->assertTrue($if->has('media'));
        $this->assertTrue($if->has('porcentagemPresenca'));
        $this->assertTrue($if->has('parecerDescritivo'));
        $this->assertTrue($if->has('tipoPresenca'));
        $this->assertTrue($if->has('mediaRecuperacao'));
        $this->assertTrue($if->has('formulaMedia'));
        $this->assertTrue($if->has('formulaRecuperacao'));
        $this->assertTrue($if->has('tabelaArredondamento'));
    }

    /**
     * Test insert data
     */
    public function testInsert()
    {
        $regra = $this->buildRegraAvaliacao();
        $this->em->persist($regra);
        $this->em->flush();

        $this->assertNotNull($regra->getId());
        $this->assertEquals(1, $regra->getId());

        /**
         * get row from database
         */
        $savedRegra = $this->em->find(get_class($regra), $regra->getId());
        $this->assertEquals(1, $savedRegra->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $regra = $this->buildRegraAvaliacao();
        $regra->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($regra);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $regra = $this->buildRegraAvaliacao();
        $this->em->persist($regra);
        $this->em->flush();

        $savedRegra = $this->em->find(get_class($regra), $regra->getId());
        $this->assertEquals('Nome da Regra', $savedRegra->getNome());
        $savedRegra->setNome('Novo nome');
        $this->em->flush();

        $savedRegra = $this->em->find(get_class($regra), $savedRegra->getId());
        $this->assertEquals('Novo nome', $savedRegra->getNome());
    }

    public function testDelete()
    {
        $regra = $this->buildRegraAvaliacao();
        $this->em->persist($regra);
        $this->em->flush();

        $id = $regra->getId();
        $savedRegra = $this->em->find(get_class($regra), $id);
        $this->em->remove($savedRegra);
        $this->em->flush();

        $savedRegra = $this->em->find(get_class($regra), $id);
        $this->assertNull($savedRegra);
    }


    /**
     * @return RegraAvaliacao
     */
    private function buildRegraAvaliacao()
    {
        $regra = new RegraAvaliacao();
        $regra->setNome('Nome da Regra');
        $regra->setTipoNota(1);
        $regra->setTipoProgressao(1);
        $regra->setMedia('10');
        $regra->setPorcentagemPresenca(45);
        $regra->setParecerDescritivo(1);
        $regra->setTipoPresenca(1);
        $regra->setMediaRecuperacao(50);
        $formulaMedia = $this->buildFormulaMedia();
        $regra->setFormulaMedia($formulaMedia);
        $regra->setFormulaRecuperacao($formulaMedia);
        $tabela = $this->buildTabelaArredondamento();
        $regra->setTabelaArredondamento($tabela);

        return $regra;

    }

    private function buildFormulaMedia()
    {
        $formula = new \Escola\Entity\FormulaMedia();
        $formula->setNome('Nome da formula');
        $formula->setTipoFormula(1);
        $formula->setFormulaMedia('Se');

        return $formula;
    }

    private function buildTabelaArredondamento()
    {
        $tabela = new \Escola\Entity\TabelaArredondamento();

        $tabela->setNome('Tabela de arredondamento');
        $tabela->setTipoNota(1);

        return $tabela;
    }
}