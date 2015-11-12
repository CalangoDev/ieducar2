<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\EnderecoExterno;
use Usuario\Entity\Fisica;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class EnderecoExternoTest extends EntityTestCase
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
		$enderecoExterno = new EnderecoExterno();
		$if = $enderecoExterno->getInputFilter();
		$this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

		return $if;
	}

	/**
	 * @depends testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		$this->assertEquals(14, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('tipo'));
		$this->assertTrue($if->has('tipoLogradouro'));
		$this->assertTrue($if->has('logradouro'));
		$this->assertTrue($if->has('numero'));
		$this->assertTrue($if->has('letra'));		
		$this->assertTrue($if->has('complemento'));
		$this->assertTrue($if->has('bairro'));
		$this->assertTrue($if->has('cep'));
		$this->assertTrue($if->has('cidade'));
		$this->assertTrue($if->has('siglaUf'));
		$this->assertTrue($if->has('resideDesde'));
		$this->assertTrue($if->has('dataRev'));
		$this->assertTrue($if->has('origemGravacao'));		
	}

	/**
	 * Teste de inserção de um endereço externo
	 */
	public function testInsert()
	{		
		$fisica = $this->buildFisica();		
		$this->em->persist($fisica);

		$enderecoExterno = $this->buildEnderecoExterno();
		$enderecoExterno->setPessoa($fisica);
		$this->em->persist($enderecoExterno);		
		$this->em->flush();

		$this->assertNotNull($enderecoExterno->getPessoa());
		$this->assertEquals(1, $enderecoExterno->getPessoa()->getId());

		/**
		 * Buscando no banco de dados o endereço externo que foi cadastrado
		 */		
		$savedEnderecoExterno = $this->em->find(get_class($enderecoExterno), $enderecoExterno->getPessoa()->getId());		
		//	Comparando a intancia das classes
		$this->assertInstanceOf(get_class($enderecoExterno), $savedEnderecoExterno);		
		$this->assertEquals($enderecoExterno->getPessoa(), $savedEnderecoExterno->getPessoa());
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidLogradouro()
	{
		$enderecoExterno = $this->buildEnderecoExterno();		
		$enderecoExterno->setLogradouro('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois 
		paga. Sapien in monti palavris qui num significa nadis i 
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');

		$this->em->persist($enderecoExterno);
		$this->em->flush();
	}

	public function testUpdate()
	{
		$fisica = $this->buildFisica();		
		$this->em->persist($fisica);		
		$enderecoExterno = $this->buildEnderecoExterno();
		$enderecoExterno->setPessoa($fisica);		
		$this->em->persist($enderecoExterno);
		$this->em->flush();

		$savedEnderecoExterno = $this->em->find('Usuario\Entity\EnderecoExterno', $enderecoExterno->getPessoa()->getId());

		$this->assertEquals('Teste', $enderecoExterno->getLogradouro());

		$savedEnderecoExterno->setLogradouro('João José');		
		$this->em->flush();

		$savedEnderecoExterno = $this->em->find('Usuario\Entity\EnderecoExterno', $savedEnderecoExterno->getPessoa()->getId());

		$this->assertEquals('João José', $savedEnderecoExterno->getLogradouro());
	}

	public function testDelete()
	{
		$fisica = $this->buildFisica();		
		$this->em->persist($fisica);		
		$enderecoExterno = $this->buildEnderecoExterno();
		$enderecoExterno->setPessoa($fisica);
		$this->em->persist($enderecoExterno);
		$this->em->flush();

		$id = $enderecoExterno->getPessoa()->getId();
		$savedEnderecoExterno = $this->em->find('Usuario\Entity\EnderecoExterno', $id);

		$this->em->remove($savedEnderecoExterno);
		$this->em->flush();

		$savedEnderecoExterno = $this->em->find('Usuario\Entity\EnderecoExterno', $id);
		$this->assertNull($savedEnderecoExterno);
	}

	private function buildFisica()
	{	
    	/**
    	 * Dados fisica
    	 */    	
		$fisica = new Fisica;	
		$fisica->setNome("Steve Jobs");
		$fisica->setTipo("F");
		$fisica->setSituacao("A");			
		$fisica->setSexo("M");
		$fisica->setOrigemGravacao("M");
		$fisica->setOperacao("I");
		$fisica->setIdsisCad(1);

    	return $fisica;
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
}