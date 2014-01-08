<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Pessoa;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class PessoaTest extends EntityTestCase
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
		$pessoa = new Pessoa();
		$if = $pessoa->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);
		return $if;
	}

	/**
	 * @depends testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		//testa os filtros 
		$this->assertEquals(13, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('nome'));
		$this->assertTrue($if->has('url'));
		$this->assertTrue($if->has('tipo'));
		$this->assertTrue($if->has('data_rev'));
		$this->assertTrue($if->has('email'));
		$this->assertTrue($if->has('situacao'));
		$this->assertTrue($if->has('origem_gravacao'));
		$this->assertTrue($if->has('operacao'));
		$this->assertTrue($if->has('idsis_rev'));
		$this->assertTrue($if->has('idsis_cad'));
		$this->assertTrue($if->has('idpes_rev'));
		$this->assertTrue($if->has('idpes_cad'));
	}

	/**
	 * Teste de Insercao de Pessoa
	 */
	public function testInsert()
	{
		$pessoa = $this->pessoa();
		$this->addPessoa($pessoa);

		$this->assertEquals('Steve Jobs', $pessoa->nome);
		$this->assertEquals(1, $pessoa->id);
	}

	/**
	 * Teste insert invalido operacao == null
 	 * expectedException Core\Entity\EntityException
	 * expectedExceptionMessage O atributo operacao recebeu um valor inválido: ""	 
	 */
	// public function testInsertInvalido()
	// {				
	// 	$pessoa = $this->pessoa();
	// 	$pessoa->operacao = null;
	// 	$this->addPessoa($pessoa);			
	// }

	/**
	 * Teste insert Nome Invalido
	 * @expectedException Core\Entity\EntityException
	 * @expectedExceptionMessage Input inválido: nome = 
	 */
	public function testeInsertNomeInvalido()
	{
		$pessoa = new Pessoa();
		$pessoa->nome = null;
		$this->addPessoa($pessoa);
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidoTipo()
	{
		$pessoa = $this->pessoa();
		$pessoa->tipo = "FF";
		$this->addPessoa($pessoa);
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidoDataRev()
	{
		$pessoa = $this->pessoa();
		$pessoa->data_rev = "2001-10-10 00:00:001";
		$this->addPessoa($pessoa);
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidoUrl()
	{
		$pessoa = $this->pessoa();
		$pessoa->url = "Mussum ipsum cacilds, vidis litro abertis. 
		Consetis adipiscings elitis. Pra lá , depois divoltis porris, 
		paradis. Paisis, filhis, espiritis santis. 
		Mé faiz elementum girarzis, nisi eros vermeio, 
		in elementis mé pra quem é amistosis quis leo. 
		Manduma pindureta quium dia nois paga. 
		Sapien in monti palavris qui num significa nadis i pareci latim.
		 Interessantiss quisso pudia ce receita de bolis, 
		 mais bolis eu num gostis.";
		$this->addPessoa($pessoa);
	}

	public function testUpdate()
	{
		$pessoa = $this->pessoa();
		
		$this->addPessoa($pessoa);
		
		$id = $pessoa->id;

		$this->assertEquals(1, $id);

		$pessoa = $this->em->find('Usuario\Entity\Pessoa', $id);
		$this->assertEquals('Steve Jobs', $pessoa->nome);

		$pessoa->nome = 'Bill <br>Gates';
		$this->em->persist($pessoa);
		$this->em->flush();

		$pessoa = $this->em->find('Usuario\Entity\Pessoa', $id);
		$this->assertEquals('Bill Gates', $pessoa->nome);
	}

	public function testDelete()
	{
		$pessoa = $this->pessoa();		
		$this->addPessoa($pessoa);
		
		$id = $pessoa->id;

		$pessoa = $this->em->find('Usuario\Entity\Pessoa', $id);
		$this->em->remove($pessoa);
		$this->em->flush();

		$pessoa = $this->em->find('Usuario\Entity\Pessoa', $id);
		$this->assertNull($pessoa);

	}

	private function addPessoa($pessoa)
	{
		$this->em->persist($pessoa);
    	$this->em->flush();    	
	}

	private function pessoa()
	{
		$pessoa = new Pessoa();
		$pessoa->nome = "Steve Jobs";
    	$pessoa->tipo = "F";
    	$pessoa->situacao = "A";
    	$pessoa->origem_gravacao = "M";
    	$pessoa->operacao = "I";
    	$pessoa->idsis_cad = 1;
    	$pessoa->idpes_cad = 1;
    	$pessoa->idpes_rev = 1;

    	return $pessoa;
	}
}