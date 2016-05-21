<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/05/16
 * Time: 08:38
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class ComodoPredioTest extends EntityTestCase
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
        $entity = new ComodoPredio();
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
        $this->assertTrue($if->has('nome'));
        $this->assertTrue($if->has('descricao'));
        $this->assertTrue($if->has('area'));
        $this->assertTrue($if->has('comodoFuncao'));
        $this->assertTrue($if->has('ativo'));
        $this->assertTrue($if->has('predio'));
    }

    /**
     * test insert data
     */
    public function testInsert()
    {
        $entity = $this->buildComodoPredio();
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

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $entity = $this->buildComodoPredio();
        $entity->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois
		paga. Sapien in monti palavris qui num significa nadis i
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis');
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $entity = $this->buildComodoPredio();
        $this->em->persist($entity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('Nome', $savedEntity->getNome());
        $savedEntity->setNome('Outro Nome');
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $savedEntity->getId());
        $this->assertEquals('Outro Nome', $savedEntity->getNome());
    }

    public function testDelete()
    {
        $entity = $this->buildComodoPredio();
        $this->em->persist($entity);
        $this->em->flush();

        $id = $entity->getId();
        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->em->remove($savedEntity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->assertNull($savedEntity);
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

    private function buildPredio()
    {
        $entity = new Predio();
        $entity->setNome('Nome Predio');
        $entity->setDescricao('Descrição');
        $entity->setEndereco('End');
        $entity->setAtivo(true);
        $escola = $this->buildEscola();
        $entity->setEscola($escola);

        return $entity;
    }

    /**
     * @return ComodoFuncao
     */
    private function buildComodoFuncao()
    {
        $entity = new ComodoFuncao();
        $entity->setNome('Funcao');
        $entity->setDescricao('Descrição');
        $entity->setAtivo(true);

        return $entity;
    }

    /**
     * @return ComodoPredio
     */
    private function buildComodoPredio()
    {
        $entity = new ComodoPredio();
        $entity->setNome('Nome');
        $entity->setDescricao('Descrição');
        $entity->setAtivo(true);
        $entity->setArea('100');
        $comodoFuncao = $this->buildComodoFuncao();
        $entity->setComodoFuncao($comodoFuncao);
        $predio = $this->buildPredio();
        $entity->setPredio($predio);

        return $entity;
    }
}