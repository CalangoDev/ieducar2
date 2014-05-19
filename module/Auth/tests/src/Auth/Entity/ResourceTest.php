<?php
namespace Auth\Entity;

use Core\Test\EntityTestCase;
use Auth\Entity\Resource;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class ResourceTest extends EntityTestCase
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
		$resource = new Resource();
		$if = $resource->getInputFilter();
		$this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);
		return $if;
	}

	/**
	 * @depends testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		//testa os filtros 
		$this->assertEquals(3, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('nome'));
		$this->assertTrue($if->has('descricao'));
		
	}

	/**
	 * Test insert of resource
	 */
	public function testInsert()
	{		
		$resource = $this->buildResource();
		$this->em->persist($resource);
		$this->em->flush();

		$resource = $this->em->find('Auth\Entity\Resource', $resource->getId());
		
		/**
		 * Verificando se salvou o registro no banco
		 */		
		$this->assertEquals('Application\Entity\Index.index', $resource->getNome());
		$this->assertEquals(1, $resource->getId());		
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidoTipo()
	{		
		$resource = $this->buildResource();
		$resource->setNome('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
		$this->em->persist($resource);		
		$this->em->flush();		
	}	

	public function testUpdate()
	{		
		$resource = $this->buildResource();
		$this->em->persist($resource);
		$this->em->flush();

		$id = $resource->getId();		
		/**
		 * get data 
		 */
		$resource = $this->em->find('Auth\Entity\Resource', $id);

		/**
		 * Check the Resource
		 */
		$this->assertEquals('Application\Entity\Index.index', $resource->getNome());

		/**
		 * Change the resource
		 */
		$resource->setNome('Application\Controller\Index.save');
		// $this->em->persist($res);
		$this->em->flush();

		/**
		 * get data 
		 */
		$resource = $this->em->find('Auth\Entity\Resource', $id);
		/**
		 * Verificando o update
		 */		
		$this->assertEquals('Application\Controller\Index.save', $resource->getNome());
	}

	public function testDelete()
	{		
		$resource = $this->buildResource();
		$this->em->persist($resource);
		$this->em->flush();


		$id = $resource->getId();		
		$resource = $this->em->find('Auth\Entity\Resource', $id);
				
		$this->em->remove($resource);
		$this->em->flush();
		
		/**
		 * Check this remove resource is null
		 */
		$resource = $this->em->find('Auth\Entity\Resource', $id);
		$this->assertNull($resource);		
	}	
		
	private function buildResource()
	{
		$resource = new Resource;
		$resource->setNome('Application\Entity\Index.index');
		$resource->setDescricao('Tela inicial do sistema');

		return $resource;
	}
	
}