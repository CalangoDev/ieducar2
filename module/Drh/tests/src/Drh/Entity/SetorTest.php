<?php
namespace Drh\Entity;

use Core\Test\EntityTestCase;
use Drh\Entity\Setor;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class SetorTest extends EntityTestCase
{
	public function setup()
	{
		parent::setup();
	}

	/**
	 * verificando se existem filtros
	 */
	public function testGetInputFilter()
	{
		$setor = new Setor();
		$if = $setor->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);

		return $if;
	}

	/**
	 * @depends	testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		$this->assertEquals(11, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('nome'));
		$this->assertTrue($if->has('pessoaExclu'));
		$this->assertTrue($if->has('pessoaCad'));
		$this->assertTrue($if->has('refCodSetor'));
		$this->assertTrue($if->has('siglaSetor'));		
		$this->assertTrue($if->has('ativo'));
		$this->assertTrue($if->has('noPaco'));
		$this->assertTrue($if->has('tipo'));
		$this->assertTrue($if->has('secretario'));		
	}

	/**
	 * testa a insercao de um setor
	 */
	public function testInsert()
	{
		$setor = $this->buildSetor();
		$this->em->persist($setor);		
		$this->em->flush();

		$this->assertNotNull($setor->getId());		

		/**
		 * Buscando no banco de dados o setor que foi cadastrado
		 */
		$savedSetor = $this->em->find(get_class($setor), $setor->getId());

		$this->assertInstanceOf(get_class($setor), $savedSetor);
		$this->assertEquals($setor->getId(), $savedSetor->getId());		
		$this->assertEquals('Setor X', $savedSetor->getNome());
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidSetor()
	{
		$setor = $this->buildSetor();		
		$setor->setSiglaSetor("Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i 
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.");		
		$this->em->persist($setor);
		$this->em->flush();
	}

	public function testUpdate()
	{
		$setor = $this->buildSetor();
		$this->em->persist($setor);

		$savedSetor = $this->em->find('Drh\Entity\Setor', $setor->getId());

		$this->assertEquals('Setor X', $savedSetor->getNome());

		$savedSetor->setNome('Setor Y');
		$this->em->persist($savedSetor);
		$this->em->flush();

		$savedSetor = $this->em->find('Drh\Entity\Setor', $savedSetor->getId());

		$this->assertEquals('Setor Y', $savedSetor->getNome());

	}

	public function testDelete()
	{
		$setor = $this->buildSetor();		
		$this->em->persist($setor);
		$this->em->flush();

		$id = $setor->getId();

		$savedSetor = $this->em->find('Drh\Entity\Setor', $id);

		$this->em->remove($setor);
		$this->em->flush();

		$savedSetor = $this->em->find('Drh\Entity\Setor', $id);
		$this->assertNull($savedSetor);
	}


	// private function buildFisica()
	// {	
 //    	/**
 //    	 * Dados fisica
 //    	 */    	
	// 	$fisica = new \Usuario\Entity\Fisica;
	// 	$fisica->setSexo("M");
	// 	$fisica->setOrigemGravacao("M");
	// 	$fisica->setOperacao("I");
	// 	$fisica->setIdsisCad(1);
	// 	$fisica->setNome('Steve Jobs');
	// 	$fisica->setTipo("F");
 //    	$fisica->setSituacao("A");
 //    	$fisica->setOrigemGravacao("M");
 //    	$fisica->setOperacao("I");
 //    	$fisica->setIdsisCad(1);    	

 //    	return $fisica;
	// }


	// private function buildFuncionario()
	// {
	// 	$funcionario = new Funcionario;
	// 	$funcionario->setMatricula('admin');
	// 	$funcionario->setSenha('admin');
	// 	$funcionario->setAtivo(1);		

	// 	return $funcionario;
	// }
	
	private function buildSetor()
	{
		$setor = new Setor;
		$setor->setNome('Setor X');
		$setor->setSiglaSetor('STX');
		$setor->setAtivo(1);
		$setor->setNivel(1);
		$setor->setNoPaco(1);
		$setor->setEndereco('Rua do Setor X');
		$setor->setTipo('s');

		return $setor;
	}
	
}