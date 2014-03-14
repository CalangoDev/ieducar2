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
		
		$this->assertEquals(12, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('cnpj'));
		$this->assertTrue($if->has('insc_estadual'));
		$this->assertTrue($if->has('idpes_rev'));
		$this->assertTrue($if->has('data_rev'));
		$this->assertTrue($if->has('origem_gravacao'));
		$this->assertTrue($if->has('idpes_cad'));
		// $this->assertTrue($if->has('data_cad'));
		$this->assertTrue($if->has('operacao'));
		$this->assertTrue($if->has('idsis_rev'));
		$this->assertTrue($if->has('idsis_cad'));
		$this->assertTrue($if->has('fantasia'));
		$this->assertTrue($if->has('capital_social'));
	}

	/**
	 * Teste de insercao de pessoa juridica
	 */
	public function testInsert()
	{
		$juridica = $this->buildJuridica();
		$juridica->setNome("Steve Jobs");
		$juridica->setTipo("J");
		$juridica->setSituacao("A");
		$juridica->setOrigemGravacao("M");
		$juridica->setOperacao("I");
		$juridica->setIdSisCad(1);

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
	 * Teste que insere uma pessoa
	 * depois faz um clone dessa pessoa e copia seus dados para uma pessoaJuridica gerando novos registros 
	 * 
	 * isso representa um cenario onde digamos que tenha inserido uma Entity Pessoa no banco, e depois queira
	 * que essa pessoa seja uma Entidade Fisica
	 */
	public function testInsertAfter()
	{
		/**
		 * Cadastrando uma pessoa
		 */
		$pessoa = $this->buildPessoa();
		$this->em->persist($pessoa);
		$this->em->flush();

		/**
		 * Buscando pessoa cadastrada no banco
		 */
		$savedPessoa = $this->em->find('Usuario\Entity\Pessoa', 1);

		/**
		 * Verificando se o id do banco é igual a 1
		 */
		$this->assertEquals(1, $savedPessoa->getId());

		/**
		 * Cadastrando uma pessoa juridica
		 */		
		$teste = clone $savedPessoa;
		$this->em->remove($savedPessoa);
		$this->em->flush();		
		$juridica = $this->buildJuridica();
		$juridica->setId($teste->getId());
		$juridica->setNome($teste->getNome());
		$juridica->setSituacao($teste->getSituacao());
		$juridica->setOperacao($teste->getOperacao());
		$juridica->setOrigemGravacao($teste->getOrigemGravacao());
		$juridica->setIdsisCad($teste->getIdsisCad());
		$juridica->setTipo("J");		
		$this->em->persist($juridica);
		$this->em->flush();

		$this->assertNotNull($juridica->getId());
		

		$savedJuridica = $this->em->find('Usuario\Entity\Juridica', $juridica->getId());
		
		$this->assertEquals($juridica->getId(), $savedJuridica->getId());
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
		$juridica->setNome("Steve Jobs");
		$juridica->setTipo("J");
		$juridica->setSituacao("A");
		$juridica->setOrigemGravacao("M");
		$juridica->setOperacao("I");
		$juridica->setIdSisCad(1);

		$this->em->persist($juridica);

		$savedJuridica = $this->em->find('Usuario\Entity\Juridica', $juridica->getId());

		$this->assertEquals('Eduardojunior.com', $juridica->getFantasia());

		$savedJuridica->setFantasia('Apple');

		$this->em->persist($savedJuridica);
		$this->em->flush();

		$savedJuridica = $this->em->find('Usuario\Entity\Juridica', $savedJuridica->getId());

		$this->assertEquals('Apple', $savedJuridica->getFantasia());
	}

	public function testDelete()
	{
		$juridica = $this->buildJuridica();
		$juridica->setNome("Steve Jobs");
		$juridica->setTipo("J");
		$juridica->setSituacao("A");
		$juridica->setOrigemGravacao("M");
		$juridica->setOperacao("I");
		$juridica->setIdSisCad(1);		

		$this->em->persist($juridica);
		$this->em->flush();

		$id = $juridica->getId();

		$savedJuridica = $this->em->find('Usuario\Entity\Juridica', $id);
		$this->em->remove($juridica);
		$this->em->flush();

		$savedJuridica = $this->em->find('Usuario\Entity\Juridica', $id);
		$this->assertNull($savedJuridica);
	}

	private function buildPessoa()
	{
		$pessoa = new Pessoa;
		$pessoa->setNome("Steve Jobs");
    	$pessoa->setTipo("P");
    	$pessoa->setSituacao("A");
    	$pessoa->setOrigemGravacao("M");
    	$pessoa->setOperacao("I");
    	$pessoa->setIdsisCad(1);    	
    	
    	return $pessoa;
	}

	private function buildJuridica()
	{
		/**
		 * Daddos Juridica		 
		 */
		$juridica = new Juridica;
		//52.476.528/0001-35
		$juridica->setCnpj('52.476.528/0001-35');
		$juridica->setInscEstadual('866498342');
		$juridica->setFantasia('Eduardojunior.com');
		$juridica->setCapitalSocial('capital social');
		
		return $juridica;
	}
}