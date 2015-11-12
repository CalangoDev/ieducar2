<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 12/11/15
 * Time: 08:12
 */
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
/**
 * @group Entity
 */
class DocumentoTest extends EntityTestCase
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
        $documento = new Documento();
        $if = $documento->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(15, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('dataExpedicaoRg'));
        $this->assertTrue($if->has('tipoCertidaoCivil'));
        $this->assertTrue($if->has('termo'));
        $this->assertTrue($if->has('livro'));
        $this->assertTrue($if->has('folha'));
        $this->assertTrue($if->has('dataEmissaoCertidaoCivil'));
        $this->assertTrue($if->has('cartorioCertidaoCivil'));
        $this->assertTrue($if->has('numeroCarteiraTrabalho'));
        $this->assertTrue($if->has('serieCarteiraTrabalho'));
        $this->assertTrue($if->has('dataEmissaoCarteiraTrabalho'));
        $this->assertTrue($if->has('numeroTituloEleitor'));
        $this->assertTrue($if->has('zonaTituloEleitor'));
        $this->assertTrue($if->has('secaoTituloEleitor'));
        $this->assertTrue($if->has('dataCad'));
    }

    /**
     * teste de inserção de um documento
     */
    public function testInsert()
    {
        $documento = $this->buildDocumento();
        $this->em->persist($documento);
        $this->em->flush();

        $savedDocumento = $this->em->find('Usuario\Entity\Documento', $documento->getId());
        $this->assertEquals(1, $savedDocumento->getId());
    }

    public function testUpdate()
    {
        $documento = $this->buildDocumento();
        $this->em->persist($documento);
        $this->em->flush();

        $savedDocumento = $this->em->find('Usuario\Entity\Documento', $documento->getId());

        $this->assertEquals('1111111111', $savedDocumento->getRg());
        $savedDocumento->setRg('22222222');
        $this->em->flush();

        $savedDocumento = $this->em->find('Usuario\Entity\Documento', $documento->getId());
        $this->assertEquals('22222222', $savedDocumento->getRg());
    }

    public function testDelete()
    {
        $documento = $this->buildDocumento();
        $this->em->persist($documento);
        $this->em->flush();

        $id = $documento->getId();

        $savedDocumento = $this->em->find('Usuario\Entity\Documento', $id);
        $this->em->remove($savedDocumento);
        $this->em->flush();

        $savedDocumento = $this->em->find('Usuario\Entity\Documento', $id);
        $this->assertNull($savedDocumento);
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidDataExpedicaoRg()
    {
        $documento = $this->buildDocumento();
        $documento->setDataExpedicaoRg('10-10-2015');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidTipoCertidaoCivil()
    {
        $documento = $this->buildDocumento();
        $documento->setTipoCertidaoCivil('100');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidTermo()
    {
        $documento = $this->buildDocumento();
        $documento->setTermo('123456789');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidLivro()
    {
        $documento = $this->buildDocumento();
        $documento->setLivro('123456789');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidFolha()
    {
        $documento = $this->buildDocumento();
        $documento->setFolha('12345');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidDataEmissaoCertidaoCivil()
    {
        $documento = $this->buildDocumento();
        $documento->setDataEmissaoCertidaoCivil('10-10-2015');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidCartorioCertidaoCivil()
    {
        $documento = $this->buildDocumento();
        $documento->setCartorioCertidaoCivil('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNumeroCarteiraTrabalho()
    {
        $documento = $this->buildDocumento();
        $documento->setNumeroCarteiraTrabalho('123456789');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidSerieCarteiraTrabalho()
    {
        $documento = $this->buildDocumento();
        $documento->setSerieCarteiraTrabalho('999991');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidDataEmissaoCarteiraTrabalho()
    {
        $documento = $this->buildDocumento();
        $documento->setDataEmissaoCarteiraTrabalho('10-10-2015');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNumeroTituloEleitor()
    {
        $documento = $this->buildDocumento();
        $documento->setNumeroTituloEleitor('12345678901234');
        $this->em->persist($documento);
        $this->em->flush();
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidZonaTituloEleitor()
    {
        $documento = $this->buildDocumento();
        $documento->setZonaTituloEleitor('10000');
        $this->em->persist($documento);
        $this->em->flush();
    }

    private function buildDocumento()
    {
        //dependencias - orgao emissor e estado
        $orgaoEmissor = $this->buildOrgaoEmissorRg();
        $this->em->persist($orgaoEmissor);

        $uf = $this->buildUf();
        $this->em->persist($uf);

        $this->em->flush();

        $documento = new Documento();
        $documento->setRg('1111111111');
        $documento->setDataExpedicaoRg(new \DateTime("10-10-2015", new \DateTimeZone('America/Sao_Paulo')));
        $documento->setSiglaUfExpedicaoRg($uf);
        $documento->setTipoCertidaoCivil('1');
        $documento->setTermo('12345678');
        $documento->setLivro('LIVRO');
        $documento->setFolha('1234');
        $documento->setDataEmissaoCertidaoCivil(new \DateTime("10-10-2015", new \DateTimeZone('America/Sao_Paulo')));
        $documento->setSiglaUfCertidaoCivil($uf);
        $documento->setCartorioCertidaoCivil('CARTORIO CIVIL');
        $documento->setNumeroCarteiraTrabalho('123456789');
        $documento->setDataEmissaoCarteiraTrabalho(new \DateTime("10-10-2015", new \DateTimeZone('America/Sao_Paulo')));
        $documento->setSiglaUfCarteiraTrabalho($uf);
        $documento->setNumeroTituloEleitor('1234567890123');
        $documento->setZonaTituloEleitor('1234');
        $documento->setSecaoTituloEleitor('1234');
        $documento->setOrgaoEmissorRg($orgaoEmissor);

        return $documento;
    }

    private function buildUf()
    {
        $uf = new \Core\Entity\Uf();
        $uf->setNome('Bahia');
        $uf->setUf('BA');
        $uf->setCep1('44000');
        $uf->setCep2('48900');

        return $uf;
    }

    private function buildOrgaoEmissorRg()
    {
        $orgaoEmissor = new \Usuario\Entity\OrgaoEmissorRg();
        $orgaoEmissor->setSigla('SSP');
        $orgaoEmissor->setDescricao('SSP');

        return $orgaoEmissor;
    }
}