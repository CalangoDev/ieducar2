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
		$this->assertEquals(13, $if->count());

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
        $this->assertTrue($if->has('zonaLocalizacao'));
	}

	/**
	 * Teste de inserção de um endereço externo
	 */
	public function testInsert()
	{
		$enderecoExterno = $this->buildEnderecoExterno();
		$this->em->persist($enderecoExterno);
		$this->em->flush();

		/**
		 * Buscando no banco de dados o endereço externo que foi cadastrado
		 */		
		$savedEnderecoExterno = $this->em->find('Usuario\Entity\EnderecoExterno', $enderecoExterno->getId());
		$this->assertEquals(1, $savedEnderecoExterno->getId());
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
		$enderecoExterno = $this->buildEnderecoExterno();
		$this->em->persist($enderecoExterno);
		$this->em->flush();

		$savedEnderecoExterno = $this->em->find('Usuario\Entity\EnderecoExterno', $enderecoExterno->getId());

		$this->assertEquals('Teste', $savedEnderecoExterno->getLogradouro());

		$savedEnderecoExterno->setLogradouro('João José');		
		$this->em->flush();

		$savedEnderecoExterno = $this->em->find('Usuario\Entity\EnderecoExterno', $savedEnderecoExterno->getId());

		$this->assertEquals('João José', $savedEnderecoExterno->getLogradouro());
	}

	public function testDelete()
	{
		$enderecoExterno = $this->buildEnderecoExterno();
		$this->em->persist($enderecoExterno);
		$this->em->flush();

		$id = $enderecoExterno->getId();
		$savedEnderecoExterno = $this->em->find('Usuario\Entity\EnderecoExterno', $id);

		$this->em->remove($savedEnderecoExterno);
		$this->em->flush();

		$savedEnderecoExterno = $this->em->find('Usuario\Entity\EnderecoExterno', $id);
		$this->assertNull($savedEnderecoExterno);
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
		$enderecoExterno->setBloco('A');
		$enderecoExterno->setAndar('1');
		$enderecoExterno->setApartamento('102');
		$enderecoExterno->setZonaLocalizacao(1);

		return $enderecoExterno;
	}
}