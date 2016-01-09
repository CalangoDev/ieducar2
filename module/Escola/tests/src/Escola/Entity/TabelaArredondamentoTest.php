<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/01/16
 * Time: 21:47
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class TabelaArredondamentoTest extends EntityTestCase
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
        $tabela = new TabelaArredondamento();
        $if = $tabela->getInputFilter();
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
        $this->assertTrue($if->has('tipoNota'));
        $this->assertTrue($if->has('notas'));
    }

    /**
     * test insert data
     */
    public function testInsert()
    {
        $notas = $this->buildNotas();
        $tabela = $this->buildTabelaArredondamento();
        $tabela->addNotas($notas);

        $this->em->persist($tabela);
        $this->em->flush();

        $this->assertNotNull($tabela->getId());
        $this->assertEquals(1, $tabela->getId());

        /**
         * get row from database
         */
        $savedTabela = $this->em->find(get_class($tabela), $tabela->getId());

        $this->assertEquals(1, $savedTabela->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $tabela = $this->buildTabelaArredondamento();
        $tabela->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($tabela);
        $this->em->flush();
    }

    /**
     * Test Update
     */
    public function testUpdate()
    {
        $tabela = $this->buildTabelaArredondamento();
        $this->em->persist($tabela);
        $this->em->flush();

        $savedTabela = $this->em->find(get_class($tabela), $tabela->getId());
        $this->assertEquals('Tabela X', $savedTabela->getNome());
        $savedTabela->setNome('B');
        $this->em->flush();

        $savedTabela = $this->em->find(get_class($tabela), $tabela->getId());
        $this->assertEquals('B', $savedTabela->getNome());
    }

    public function testDelete()
    {
        $tabela = $this->buildTabelaArredondamento();
        $this->em->persist($tabela);
        $this->em->flush();

        $id = $tabela->getId();
        $savedTabela = $this->em->find(get_class($tabela), $id);
        $this->em->remove($savedTabela);
        $this->em->flush();

        $savedTabela = $this->em->find(get_class($tabela), $id);
        $this->assertNull($savedTabela);
    }

    /**
     * @return ArrayCollection
     */
    private function buildNotas()
    {
        $notas = new ArrayCollection();
        $nota = new \Escola\Entity\TabelaArredondamentoValor();
        $nota->setNome('A');
        $nota->setValorMinimo(7);
        $nota->setValorMaximo(10);
        $nota->setDescricao('Desc');
        $notas->add($nota);

        $nota = new \Escola\Entity\TabelaArredondamentoValor();
        $nota->setNome('B');
        $nota->setValorMinimo(5);
        $nota->setValorMaximo(6.999);
        $nota->setDescricao('Desc');
        $notas->add($nota);

        return $notas;
    }

    /**
     * @return TabelaArredondamento
     */
    private function buildTabelaArredondamento()
    {
        $tabelaArredondamento = new TabelaArredondamento();
        $tabelaArredondamento->setNome('Tabela X');
        $tabelaArredondamento->setTipoNota(1);

        return $tabelaArredondamento;
    }
}