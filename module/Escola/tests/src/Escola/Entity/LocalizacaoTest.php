<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 18/01/16
 * Time: 12:11
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;

/**
 * @group Entity
 */
class LocalizacaoTest extends EntityTestCase
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
        $localizacao = new Localizacao();
        $if = $localizacao->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(2, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('nome'));
    }

    public function testInsert()
    {
        $localizacao = $this->buildLocalizacao();
        $this->em->persist($localizacao);
        $this->em->flush();
        $this->assertNotNull($localizacao->getId());
        $this->assertEquals(1, $localizacao->getId());

        // searching record in db
        $savedLocalizacao = $this->em->find(get_class($localizacao), $localizacao->getId());
        $this->assertInstanceOf(get_class($localizacao), $savedLocalizacao);
        $this->assertEquals($localizacao->getId(), $savedLocalizacao->getId());
        $this->assertEquals('Urbana', $savedLocalizacao->getNome());
    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidNome()
    {
        $localizacao = $this->buildLocalizacao();
        $localizacao->setNome('qwertyuiop[]\asdfghjkl;zxcvbnm,/1234567890-=qwertyuiiop[]\asdfghkjk;;;vbnm,../qwertryuiop
        pasdfhjhlxzc,xzmkjasdoioqwe qweoioi wqe oiasodi osadi osadioa sdoas oi oasid o oi osad oioi oi dasoi oido oi
         oisdoio asd oi oadoi oia dsoi oiasdoi oi dosaidoi asdois osadoi oisdoi oi asdoi o asdoi oia sdoi asdoi asdoi
         oi adoi sadoaisdoi sadoi sado asdoi odoasi dsooioiasd oiasd osdoai sadoi oiasdoasi doasdoid oia odiasodi oia
         doi oi dasodi oi asdoi oi oi asdoi oiasd oio i doasi oasido asdio ');
        $this->em->persist($localizacao);
        $this->em->flush();
    }

    public function testUpdate()
    {
        $localizacao = $this->buildLocalizacao();
        $this->em->persist($localizacao);
        $this->em->flush();
        $savedLocalizacao = $this->em->find(get_class($localizacao), $localizacao->getId());

        $this->assertEquals('Urbana', $savedLocalizacao->getNome());
        $savedLocalizacao->setNome('Rural');
        $this->em->flush();
        $savedLocalizacao = $this->em->find(get_class($localizacao), $savedLocalizacao->getId());
        $this->assertEquals('Rural', $savedLocalizacao->getNome());
    }

    public function testDelete()
    {
        $localizacao = $this->buildLocalizacao();
        $this->em->persist($localizacao);
        $this->em->flush();
        $id = $localizacao->getId();
        $savedLocalizacao = $this->em->find(get_class($localizacao), $id);
        $this->em->remove($savedLocalizacao);
        $this->em->flush();
        $savedLocalizacao = $this->em->find(get_class($localizacao), $id);
        $this->assertNull($savedLocalizacao);
    }

    private function buildLocalizacao()
    {
        $localizacao = new Localizacao();
        $localizacao->setNome('Urbana');

        return $localizacao;
    }

}