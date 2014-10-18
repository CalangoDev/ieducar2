<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Fisica;
use Usuario\Entity\Pessoa;
use Usuario\Entity\EnderecoExterno;
use Zend\InputFilter\InputFilterInterface;


/**
 * @group Entity
 */
class FisicaTest extends EntityTestCase
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
		$fisica = new Fisica();
		$if = $fisica->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);
		return $if;
	}

	/**
	 * @depends testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		//testa os filtros 
		$this->assertEquals(34, $if->count());

		$this->assertTrue($if->has('id'));		
		$this->assertTrue($if->has('dataNasc'));
		$this->assertTrue($if->has('sexo'));
		$this->assertTrue($if->has('dataUniao'));
		$this->assertTrue($if->has('dataObito'));
		$this->assertTrue($if->has('nacionalidade'));
		$this->assertTrue($if->has('dataChegadaBrasil'));
		$this->assertTrue($if->has('ultimaEmpresa'));
		$this->assertTrue($if->has('nomeMae'));
		$this->assertTrue($if->has('nomePai'));
		$this->assertTrue($if->has('nomeConjuge'));
		$this->assertTrue($if->has('nomeResponsavel'));
		$this->assertTrue($if->has('justificativaProvisorio'));
		$this->assertTrue($if->has('dataRev'));
		$this->assertTrue($if->has('origemGravacao'));
		$this->assertTrue($if->has('operacao'));
		$this->assertTrue($if->has('idsisRev'));
		$this->assertTrue($if->has('idsisCad'));
		$this->assertTrue($if->has('refCodSistema'));
		$this->assertTrue($if->has('cpf'));
		$this->assertTrue($if->has('idpesMae'));
		$this->assertTrue($if->has('idpesPai'));
		$this->assertTrue($if->has('idpesResponsavel'));
		$this->assertTrue($if->has('idmunNascimento'));
		$this->assertTrue($if->has('idpaisEstrangeiro'));
		$this->assertTrue($if->has('idesco'));
		$this->assertTrue($if->has('ideciv'));
		$this->assertTrue($if->has('idpesCon'));
		$this->assertTrue($if->has('idocup'));
		$this->assertTrue($if->has('idpesRev'));
		$this->assertTrue($if->has('idpesCad'));
		$this->assertTrue($if->has('refCodReligiao'));
	}

	/**
	 * Teste de Insercao de Fisica
	 * 
	 * Existe um relacionamento das Entidades Pessoa->Fisica One-To-One
	 * Onde para se ter um registro da entidade Fisica é necessario ter um registro na Entidade Pessoa
	 * Com o efeito cascade ao persistir uma instancia Pessoa, faz a persistencia na instacia Fisica
	 * 
	 * @todo  nao deixar cadastrar uma Entity fisica sem ter uma Entity pessoa
	 */
	public function testInsert()
	{		
		/**
		 * Cadastrando uma nova pessoa Fisica
		 */
		$fisica = $this->buildFisica();
		$fisica->setNome("Steve Jobs");
		$fisica->setTipo("F");
		$fisica->setSituacao("A");
		$fisica->setOrigemGravacao("M");
		$fisica->setOperacao("I");
		$fisica->setIdsisCad(1);		
		$this->em->persist($fisica);
		$this->em->flush();


		$this->assertNotNull($fisica->getId());
		$this->assertEquals(1, $fisica->getId());

		/**
		 * Buscando no banco de dados a pessoa fisica que foi cadastrada
		 */
		$savedPessoaFisica = $this->em->find(get_class($fisica), $fisica->getId());
        
        $this->assertInstanceOf(get_class($fisica), $savedPessoaFisica);
        $this->assertEquals($fisica->getId(), $savedPessoaFisica->getId());
	}

	/**
	 * Teste que insere uma pessoa
	 * depois faz um clone dessa pessoa e copia seus dados para uma pessoaFisica gerando novos registros 
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
		 * Cadastrando uma pessoa fisica
		 */		
		$teste = clone $savedPessoa;
		$this->em->remove($savedPessoa);
		$this->em->flush();		
		$fisica = $this->buildFisica();
		$fisica->setId($teste->getId());
		$fisica->setNome($teste->getNome());
		$fisica->setDataCad($teste->getDataCad());
		$fisica->setSituacao($teste->getSituacao());		
		
		$this->em->persist($fisica);
		$this->em->flush();

		$this->assertNotNull($fisica->getId());
		

		$savedFisica = $this->em->find('Usuario\Entity\Fisica', $fisica->getId());
		
		$this->assertEquals($fisica->getId(), $savedFisica->getId());
	}

	/**
	 * @expectedException Core\Entity\EntityException	 
	 */
	public function testInputFilterInvalidCpf()
	{
		$fisica = $this->buildFisica();
		$fisica->setNome('Steve Jobs');
		$fisica->setSituacao('A');
		$fisica->setOrigemGravacao('M');
		$fisica->setOperacao('I');
		$fisica->setIdsisCad(1);
		$fisica->setCpf('111.111.111-111');//cpf invalido 
		$this->em->persist($fisica);
		$this->em->flush();
	}
		
	public function testUpdate()
	{
		$fisica = $this->buildFisica();
		$fisica->setNome('Steve Jobs');
		$fisica->setSituacao('A');
		$fisica->setOrigemGravacao('M');
		$fisica->setOperacao('I');
		$fisica->setIdsisCad(1);
		$fisica->setCpf('111.111.111-11');
		$this->em->persist($fisica);
		$this->em->flush();

		$savedFisica = $this->em->find('Usuario\Entity\Fisica', $fisica->getId());
		$this->assertEquals('Steve Jobs', $savedFisica->getNome());
		$savedFisica->setNome("Gold");				
		$this->em->flush();
		// $this->em->flush();

		$savedFisica2 = $this->em->find('Usuario\Entity\Fisica', $savedFisica->getId());
		$this->assertEquals('Gold', $savedFisica2->getNome());
	}

	public function testDelete()
	{
		$fisica = $this->buildFisica();
		$fisica->setNome('Steve Jobs');
		$fisica->setSituacao('A');
		$fisica->setOrigemGravacao('M');
		$fisica->setOperacao('I');
		$fisica->setIdsisCad(1);
		$this->em->persist($fisica);
		$this->em->flush();

		$id = $fisica->getId();

		$savedFisica = $this->em->find('Usuario\Entity\Fisica', $id);

		$this->em->remove($fisica);
		$this->em->flush();

		$savedFisica = $this->em->find('Usuario\Entity\Fisica', $id);		
		$this->assertNull($savedFisica);

	}

	private function buildPessoa()
	{
		$pessoa = new Pessoa;
		$pessoa->setNome("Steve Jobs");
    	$pessoa->setTipo("F");
    	$pessoa->setSituacao("A");
    	$pessoa->setOrigemGravacao("M");
    	$pessoa->setOperacao("I");
    	$pessoa->setIdsisCad(1);    	
    	
    	return $pessoa;
	}

	private function buildEnderecoExterno()
	{
		$enderecoExterno = new EnderecoExterno;
		$enderecoExterno->setTipo(1);
		$enderecoExterno->setLogradouro('Teste');
		$enderecoExterno->setNumero('10');
		$enderecoExterno->setLetra('A');
		$enderecoExterno->setComplemento('Casa');
		$enderecoExterno->setBairro('Centro');
		$enderecoExterno->setCep('44900-000');
		$enderecoExterno->setCidade('Irecê');
		$enderecoExterno->setSiglaUf('BA');
		$enderecoExterno->setResideDesde(new \DateTime());
		// $enderecoExterno->setDataRev();
		$enderecoExterno->setOperacao("I");
		$enderecoExterno->setOrigemGravacao("U");
		$enderecoExterno->setIdsisCad(1);
		$enderecoExterno->setBloco('A');
		$enderecoExterno->setAndar('1');
		$enderecoExterno->setApartamento('102');
		$enderecoExterno->setZonaLocalizacao(1);

		return $enderecoExterno;
	}

	private function buildFisica()
	{	
    	/**
    	 * Dados fisica
    	 */    	
		$fisica = new Fisica;		
		$fisica->setSexo("M");
		$fisica->setOrigemGravacao("M");
		$fisica->setOperacao("I");
		$fisica->setIdsisCad(1);

    	return $fisica;
	}

}