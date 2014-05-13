<?php
namespace Auth\Entity;

use Core\Test\EntityTestCase;
use Auth\Entity\Role;
use Auth\Entity\Resource;
use Usuario\Entity\Fisica;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class RoleTest extends EntityTestCase
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
		$role = new Role();
		$if = $role->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);
		return $if;
	}

	/**
	 * @depends testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		//testa os filtros 
		$this->assertEquals(2, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('privilegio'));
		
	}

	/**
	 * Teste de Insercao de Pessoa	 
	 */
	public function testInsert()
	{
		$fisica = $this->buildFisica();				
		$this->em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$this->em->persist($funcionario);

		$resource = $this->buildResource();
		$this->em->persist($resource);

		$role = $this->buildRole();
		$role->setFuncionario($funcionario);
		$role->setResource($resource);
		
		$this->em->persist($role);
		$this->em->flush();

		$role = $this->em->find('Auth\Entity\Role', $role->getId());
		

		/**
		 * Verificando se salvou o registro no banco para a pessoaA
		 */
		$this->assertEquals('admin', $role->getFuncionario()->getMatricula());
		$this->assertEquals(1, $role->getId());		
	}

	/**
	 * Teste insert Nome Invalido
	 * @expectedException Core\Entity\EntityException
	 * @expectedExceptionMessage Input inválido: nome = 
	 */
	// public function testeInsertNomeInvalido()
	// {
	// 	$pessoa = new Pessoa();
	// 	$pessoa->setNome(null);
	// 	$this->em->persist($pessoa);
	// 	$this->em->flush();
	// }

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidoTipo()
	{
		$fisica = $this->buildFisica();				
		$this->em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$this->em->persist($funcionario);

		$resource = $this->buildResource();
		$this->em->persist($resource);

		$role = $this->buildRole();
		$role->setFuncionario($funcionario);
		$role->setResource($resource);
		$role->setPrivilegio(2);
		$this->em->persist($role);
		$this->em->flush();		
	}	

	public function testUpdate()
	{
		$fisica = $this->buildFisica();				
		$this->em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$this->em->persist($funcionario);

		$resource = $this->buildResource();
		$this->em->persist($resource);

		$role = $this->buildRole();
		$role->setFuncionario($funcionario);
		$role->setResource($resource);
		$this->em->persist($role);
		$this->em->flush();

		$id = $role->getId();
		$privilegio = $role->getPrivilegio();		
		/**
		 * get data 
		 */
		$role = $this->em->find('Auth\Entity\Role', $id);

		/**
		 * Verificando se o privilegio é 0 (allow)
		 */
		$this->assertEquals(0, $role->getPrivilegio());

		/**
		 * Alterando o privilegio
		 */
		$role->setPrivilegio(1);

		$this->em->persist($role);
		$this->em->flush();

		/**
		 * get data 
		 */
		$role = $this->em->find('Auth\Entity\Role', $id);
		/**
		 * Verificando o update
		 */		
		$this->assertEquals(1, $role->getPrivilegio());
	}

	public function testDelete()
	{
		$fisica = $this->buildFisica();				
		$this->em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$this->em->persist($funcionario);

		$resource = $this->buildResource();
		$this->em->persist($resource);

		$role = $this->buildRole();
		$role->setFuncionario($funcionario);
		$role->setResource($resource);
		$this->em->persist($role);
		$this->em->flush();


		$id = $role->getId();		
		$role = $this->em->find('Auth\Entity\Role', $id);
				
		$this->em->remove($role);
		$this->em->flush();
		
		/**
		 * Verifica se a role removida esta como null
		 */
		$role = $this->em->find('Auth\Entity\Role', $id);
		$this->assertNull($role);		
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
		$fisica->setNome("Steve Jobs");
		$fisica->setTipo("F");
		$fisica->setSituacao("A");

    	return $fisica;
	}

	private function buildFuncionario()
	{
		$funcionario = new \Portal\Entity\Funcionario;
		$funcionario->setMatricula('admin');
		$funcionario->setSenha('admin');
		$funcionario->setAtivo(1);		

		return $funcionario;
	}
	
	private function buildResource()
	{
		$resource = new Resource;
		$resource->setNome('Application\Entity\Index.index');
		$resource->setDescricao('Tela inicial do sistema');

		return $resource;
	}

	private function buildRole()
	{
		$role = new Role;
		$role->setPrivilegio(0);
    	
    	return $role;
	}
}