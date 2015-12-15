<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Juridica;
use Usuario\Entity\Pessoa;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class JuridicaTest extends EntityTestCase
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
		$juridica = new Juridica();
		$if = $juridica->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);
		return $if;
	}

	/**
	 * @depends testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		//testa os filtros
		
		$this->assertEquals(11, $if->count());

		$this->assertTrue($if->has('cnpj'));
		$this->assertTrue($if->has('inscricaoEstadual'));
		$this->assertTrue($if->has('fantasia'));
		$this->assertTrue($if->has('capitalSocial'));
	}

	/**
	 * Teste de insercao de pessoa juridica
	 */
	public function testInsert()
	{
		$juridica = $this->buildJuridica();
		$this->em->persist($juridica);
		$this->em->flush();


		$this->assertNotNull($juridica->getId());
		$this->assertEquals(1, $juridica->getId());

		/**
		 * Buscando no banco de dados a pessoa juridica que foi cadastrada
		 */
		$savedPessoaJuridica = $this->em->find(get_class($juridica), $juridica->getId());

		$this->assertInstanceOf(get_class($juridica), $savedPessoaJuridica);
		$this->assertEquals($juridica->getId(), $savedPessoaJuridica->getId());
	}


	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidCnpjf()
	{
		$juridica = $this->buildJuridica();
		$juridica->setCnpj('52.476.528/0001-351');
		$this->em->persist($juridica);
		$this->em->flush();
	}

	public function testUpdate()
	{
		$juridica = $this->buildJuridica();
		$this->em->persist($juridica);
		$this->em->flush();

		$savedJuridica = $this->em->find('Usuario\Entity\Juridica', $juridica->getId());

		$this->assertEquals('CalangoDev', $juridica->getFantasia());
		$savedJuridica->setFantasia('Apple');
		$this->em->flush();

		$savedJuridica2 = $this->em->find('Usuario\Entity\Juridica', $savedJuridica->getId());
		$this->assertEquals('Apple', $savedJuridica2->getFantasia());
	}

	public function testDelete()
	{
		$juridica = $this->buildJuridica();
		$this->em->persist($juridica);
		$this->em->flush();

		$id = $juridica->getId();

		$savedJuridica = $this->em->find('Usuario\Entity\Juridica', $id);
		$this->em->remove($savedJuridica);
		$this->em->flush();

		$savedJuridica = $this->em->find('Usuario\Entity\Juridica', $id);
		$this->assertNull($savedJuridica);
	}

	private function buildJuridica()
	{
		/**
		 * Daddos Juridica		 
		 */
		$juridica = new Juridica;

		$juridica->setCnpj('52.476.528/0001-35');
		$juridica->setInscricaoEstadual('866498342');
		$juridica->setNome('Eduardo Junior');
		$juridica->setFantasia('CalangoDev');
		$juridica->setCapitalSocial('capital social');
		$juridica->setSituacao('A');
		
		return $juridica;
	}
}