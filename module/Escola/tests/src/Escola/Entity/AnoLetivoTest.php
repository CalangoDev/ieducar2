<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 17/06/16
 * Time: 17:14
 */
namespace Escola\Entity;
use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class AnoLetivoTest extends EntityTestCase
{
    public function setup()
    {
        parent::setup();
    }

    /**
     * check if filters exists
     */
    public function testGetInputFilter()
    {
        $entity = new AnoLetivo();
        $if = $entity->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(7, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('ano'));
        $this->assertTrue($if->has('andamento'));
        $this->assertTrue($if->has('ativo'));
        $this->assertTrue($if->has('turmasPorAno'));
        $this->assertTrue($if->has('escola'));
        $this->assertTrue($if->has('anosLetivosModulos'));
    }

    /**
     * test insert data
     */
    public function testInsert()
    {
        $entity = $this->buildAnoLetivo();
        $this->em->persist($entity);
        $this->em->flush();

        $this->assertNotNull($entity->getId());
        $this->assertEquals(1, $entity->getId());


        /**
         * get row from database
         */
        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals(1, $savedEntity->getId());
    }

    public function testUpdate()
    {
        $entity = $this->buildAnoLetivo();
        $this->em->persist($entity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals(2016, $savedEntity->getAno());
        $savedEntity->setAno(2017);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals(2017, $savedEntity->getAno());
    }

    public function testDelete()
    {
        $entity = $this->buildAnoLetivo();
        $this->em->persist($entity);
        $this->em->flush();

        $id = $entity->getId();
        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->em->remove($savedEntity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->assertNull($savedEntity);
    }


    private function buildAnoLetivo()
    {
        $entity = new AnoLetivo();
        $entity->setAndamento(1);
        $entity->setAno(2016);
        $entity->setTurmasPorAno(1);
        $escola = $this->buildEscola();
        $entity->setEscola($escola);

        return $entity;

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
        $entity = new Escola();
        $entity->setAtivo(true);
        $entity->setBloquearLancamento(false);
        $entity->setCodigoInep('12345678');
        $entity->setSigla('EM');
        $juridica = $this->buildJuridica();
        $entity->setJuridica($juridica);
        $localizacao = $this->buildLocalizacao();
        $entity->setLocalizacao($localizacao);
        $rede = $this->buildRedeEnsino();
        $entity->setRedeEnsino($rede);

        return $entity;
    }



}