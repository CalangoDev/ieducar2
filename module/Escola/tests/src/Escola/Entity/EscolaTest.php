<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 18/01/16
 * Time: 19:51
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class EscolaTest extends EntityTestCase
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
        $escola = new Escola();
        $if = $escola->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(10, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('sigla'));
        $this->assertTrue($if->has('ativo'));
        $this->assertTrue($if->has('juridica'));
        $this->assertTrue($if->has('redeEnsino'));
        $this->assertTrue($if->has('localizacao'));
        $this->assertTrue($if->has('cursos'));
        $this->assertTrue($if->has('bloquearLancamento'));
        $this->assertTrue($if->has('codigoInep'));
        $this->assertTrue($if->has('telefones'));
    }

    /**
     * teste insert data
     */
    public function testInsert()
    {
        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush();

        $this->assertNotNull($escola->getId());
        $this->assertEquals(1, $escola->getId());

        /**
         * get row from database
         */
        $savedEscola = $this->em->find(get_class($escola), $escola->getId());

        $this->assertEquals(1, $savedEscola->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidCodigoInep()
    {
        $escola = $this->buildEscola();
        $escola->setCodigoInep('123456789');
        $this->em->persist($escola);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush($escola);

        $savedEscola = $this->em->find(get_class($escola), $escola->getId());
        $this->assertEquals('Escola Modelo', $savedEscola->getJuridica()->getNome());
        $savedEscola->getJuridica()->setNome('Escola X');
        $this->em->flush();

        $savedEscola = $this->em->find(get_class($escola), $savedEscola->getId());
        $this->assertEquals('Escola X', $savedEscola->getJuridica()->getNome());
    }

    public function testDelete()
    {
        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush();

        $id = $escola->getId();
        $savedEscola = $this->em->find(get_class($escola), $id);
        $this->em->remove($savedEscola);
        $this->em->flush();

        $savedEscola = $this->em->find(get_class($escola), $id);
        $this->assertNull($savedEscola);
    }

    private function buildInstituicao()
    {
        $instituicao = new \Escola\Entity\Instituicao();
        $instituicao->setNome('Prefeitura Municipal de Irecê');
        $instituicao->setResponsavel('Secretaria de Educação');

        return $instituicao;
    }

    private function buildRedeEnsino()
    {
        $rede = new \Escola\Entity\RedeEnsino();
        $rede->setNome('Muincipal');
        $instituicao = $this->buildInstituicao();
        $rede->setInstituicao($instituicao);

        return $rede;
    }

    private function buildLocalizacao()
    {
        $localizacao = new \Escola\Entity\Localizacao();
        $localizacao->setNome('Urbana');

        return $localizacao;
    }

    private function buildJuridica()
    {
        $juridica = new \Usuario\Entity\Juridica();
        $juridica->setNome('Escola Modelo');
        $juridica->setFantasia('Escola Modelo');
        $juridica->setSituacao('A');

        return $juridica;
    }

    private function buildEscola()
    {
        $escola = new Escola();
        $escola->setAtivo(true);
        $escola->setBloquearLancamento(false);
        $escola->setCodigoInep('12345678');
        $escola->setSigla('EM');
        $juridica = $this->buildJuridica();
        $escola->setJuridica($juridica);
        $localizacao = $this->buildLocalizacao();
        $escola->setLocalizacao($localizacao);
        $rede = $this->buildRedeEnsino();
        $escola->setRedeEnsino($rede);

        return $escola;
    }
}