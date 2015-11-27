<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/11/15
 * Time: 16:32
 */
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Telefone;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class TelefoneTest extends EntityTestCase
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
        $telefone = new Telefone();
        $if = $telefone->getInputFilter();
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
        $this->assertTrue($if->has('ddd'));
        $this->assertTrue($if->has('numero'));
    }

    /**
     * Teste de insercao de telefones
     */
    public function testInsert()
    {
        $telefone = $this->buildTelefone();
        $this->em->persist($telefone);
        $this->em->flush();

        $this->assertNotNull($telefone->getId());
        $this->assertEquals(1, $telefone->getId());

        /**
         * Buscando no banco de dados o telefone que foi cadastrado
         */
        $savedTelefone = $this->em->find(get_class($telefone), $telefone->getId());

        $this->assertInstanceOf(get_class($telefone), $savedTelefone);
        $this->assertEquals($telefone->getId(), $savedTelefone->getId());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNumero()
    {
        $telefone = $this->buildTelefone();
        $telefone->setNumero('123456789012');
        $this->em->persist($telefone);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $telefone = $this->buildTelefone();
        $this->em->persist($telefone);
        $this->em->flush();

        $savedTelefone = $this->em->find('Usuario\Entity\Telefone', $telefone->getId());
        $savedTelefone->setDdd('222');
        $savedTelefone->setNumero('87654321');
        $this->em->flush();

        $savedTelefone = $this->em->find('Usuario\Entity\Telefone', $telefone->getId());
        $this->assertEquals('222', $savedTelefone->getDdd());
        $this->assertEquals('87654321', $savedTelefone->getNumero());
    }

    public function testDelete()
    {
        $telefone = $this->buildTelefone();
        $this->em->persist($telefone);
        $this->em->flush();
        $id = $telefone->getId();

        $savedTelefone = $this->em->find('Usuario\Entity\Telefone', $id);
        $this->em->remove($savedTelefone);
        $this->em->flush();

        $savedTelefone = $this->em->find('Usuario\Entity\Telefone', $id);
        $this->assertNull($savedTelefone);
    }

    private function buildTelefone()
    {
        $telefone = new Telefone();
        $telefone->setDdd('74');
        $telefone->setNumero('12345678');

        return $telefone;
    }
}
