<?php
namespace Portal\Entity;

use Core\Test\EntityTestCase;
use Portal\Entity\Funcionario;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class FuncionarioTest extends EntityTestCase
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
		$funcionario = new Funcionario();
		$if = $funcionario->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);

		return $if;
	}

	/**
	 * @depends	testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		$this->assertEquals(21, $if->count());

		// $this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('matricula'));
		$this->assertTrue($if->has('senha'));
		$this->assertTrue($if->has('ativo'));
		$this->assertTrue($if->has('refSec'));
		$this->assertTrue($if->has('ramal'));
		$this->assertTrue($if->has('sequencial'));
		$this->assertTrue($if->has('opcaoMenu'));
		$this->assertTrue($if->has('refCodSetor'));
		$this->assertTrue($if->has('refCodFuncionarioVinculo'));
		$this->assertTrue($if->has('tempoExpiraSenha'));
		$this->assertTrue($if->has('tempoExpiraConta'));
		$this->assertTrue($if->has('dataTrocaSenha'));
		$this->assertTrue($if->has('dataReativaConta'));
		$this->assertTrue($if->has('proibido'));
		$this->assertTrue($if->has('matriculaNew'));
		$this->assertTrue($if->has('matriculaPermanente'));
		$this->assertTrue($if->has('tipoMenu'));
		$this->assertTrue($if->has('ipLogado'));
		$this->assertTrue($if->has('dataLogin'));
		$this->assertTrue($if->has('email'));
		$this->assertTrue($if->has('statusToken'));
	}

	/**
	 * testa a insercao de um funcionario
	 */
	public function testInsert()
	{
		$fisica = $this->buildFisica();
		$this->em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$this->em->persist($funcionario);
		$this->em->flush();

		// $this->assertNotNull($funcionario->getId());		
		$this->assertNotNull($funcionario->getRefCodPessoaFj());		
		$this->assertEquals($fisica, $funcionario->getRefCodPessoaFj());

		/**
		 * Buscando no banco de dados o funcionario que foi cadastrado
		 */
		// $savedFuncionario = $this->em->find(get_class($funcionario), $funcionario->getId());
		$savedFuncionario = $this->em->find(get_class($funcionario), $funcionario->getRefCodPessoaFj());

		$this->assertInstanceOf(get_class($funcionario), $savedFuncionario);
		// $this->assertEquals($funcionario->getId(), $savedFuncionario->getId());
		$this->assertEquals($funcionario->getRefCodPessoaFj(), $savedFuncionario->getRefCodPessoaFj());
		$this->assertEquals(md5('admin'), $savedFuncionario->getSenha());
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidMatricula()
	{
		$fisica = $this->buildFisica();
		$this->em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setMatricula("Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i 
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.");
		$funcionario->setRefCodPessoaFj($fisica);
		$this->em->persist($funcionario);
		$this->em->flush();
	}

	public function testUpdate()
	{
		$fisica = $this->buildFisica();
		$this->em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$this->em->persist($funcionario);

		// $savedFuncionario = $this->em->find('Portal\Entity\Funcionario', $funcionario->getId());
		$savedFuncionario = $this->em->find('Portal\Entity\Funcionario', $funcionario->getRefCodPessoaFj());

		$this->assertEquals('admin', $savedFuncionario->getMatricula());

		$savedFuncionario->setMatricula('gold');
		$this->em->persist($savedFuncionario);
		$this->em->flush();

		// $savedFuncionario = $this->em->find('Portal\Entity\Funcionario', $savedFuncionario->getId());
		$savedFuncionario = $this->em->find('Portal\Entity\Funcionario', $savedFuncionario->getRefCodPessoaFj());

		$this->assertEquals('gold', $savedFuncionario->getMatricula());

	}

	public function testDelete()
	{
		$fisica = $this->buildFisica();
		$this->em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);

		$this->em->persist($funcionario);
		$this->em->flush();

		$id = $fisica->getId();

		$savedFuncionario = $this->em->find('Portal\Entity\Funcionario', $id);

		$this->em->remove($funcionario);
		$this->em->flush();

		$savedFuncionario = $this->em->find('Portal\Entity\Funcionario', $id);
		$this->assertNull($savedFuncionario);
	}


	private function buildFisica()
	{	
    	/**
    	 * Dados fisica
    	 */    	
		$fisica = new \Usuario\Entity\Fisica;
		$fisica->setSexo("M");
		$fisica->setOrigemGravacao("M");
		$fisica->setOperacao("I");
		$fisica->setIdsisCad(1);
		$fisica->setNome('Steve Jobs');
		$fisica->setTipo("F");
    	$fisica->setSituacao("A");
    	$fisica->setOrigemGravacao("M");
    	$fisica->setOperacao("I");
    	$fisica->setIdsisCad(1);    	

    	return $fisica;
	}


	private function buildFuncionario()
	{
		$funcionario = new Funcionario;
		$funcionario->setMatricula('admin');
		$funcionario->setSenha('admin');
		$funcionario->setAtivo(1);		

		return $funcionario;
	}
}