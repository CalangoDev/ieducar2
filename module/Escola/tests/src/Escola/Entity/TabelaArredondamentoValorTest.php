<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/01/16
 * Time: 20:53
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class TabelaArredondamentoValorTest extends EntityTestCase
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
        $tabela = new TabelaArredondamentoValor();
        $if = $tabela->getInputFilter();
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
        $this->assertTrue($if->has('descricao'));
        $this->assertTrue($if->has('valorMinimo'));
        $this->assertTrue($if->has('valorMaximo'));
        $this->assertTrue($if->has('tabelaArredondamento'));
    }

    /**
     * Teste de insersao de dados
     */
    public function testInsert()
    {
        $tabela = $this->buildTabelaArredondamentoValor();
        $this->em->persist($tabela);
        $this->em->flush();

        $this->assertNotNull($tabela->getId());
        $this->assertEquals(1, $tabela->getId());

        /**
         * buscando no banco de dados o telefone que foi cadastrado
         */
        $savedTabela = $this->em->find(get_class($tabela), $tabela->getId());

        $this->assertInstanceOf(get_class($tabela), $savedTabela);
        $this->assertEquals($tabela->getId(), $savedTabela->getId());
    }


    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $tabela = $this->buildTabelaArredondamentoValor();
        $tabela->setNome('ABCDEFGHIJ');
        $this->em->persist($tabela);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $tabela = $this->buildTabelaArredondamentoValor();
        $this->em->persist($tabela);
        $this->em->flush();

        $savedTabela = $this->em->find('Escola\Entity\TabelaArredondamentoValor', $tabela->getId());
        $savedTabela->setNome('B');
        $this->em->flush();

        $savedTabela = $this->em->find('Escola\Entity\TabelaArredondamentoValor', $tabela->getId());
        $this->assertEquals('B', $savedTabela->getNome());
    }

    public function testDelete()
    {
        $tabela = $this->buildTabelaArredondamentoValor();
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
     * @return TabelaArredondamentoValor
     */
    private function buildTabelaArredondamentoValor()
    {
        $tabela = new TabelaArredondamentoValor();
        $tabela->setNome('A');
        $tabela->setDescricao('Descricao');
        $tabela->setValorMaximo('10');
        $tabela->setValorMinimo('8');
        $tabelaArredondamento = $this->buildTabelaArredondamento();
        $tabela->setTabelaArredondamento($tabelaArredondamento);

        return $tabela;
    }

    private function buildTabelaArredondamento()
    {
        $tabelaArredondamento = new TabelaArredondamento();
        $tabelaArredondamento->setNome('Tabela X');
        $tabelaArredondamento->setTipoNota(1);

        return $tabelaArredondamento;
    }
}