<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Religiao;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Entity
 */
class ReligiaoTest extends EntityTestCase
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
		$religiao = new Religiao();
		$if = $religiao->getInputFilter();
		$this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

		return $if;
	}

	/**
	 * @depends	testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		$this->assertEquals(5, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('nome'));
		$this->assertTrue($if->has('dataCadastro'));
		$this->assertTrue($if->has('dataExclusao'));
		$this->assertTrue($if->has('ativo'));
		// $this->assertTrue($if->has('pessoa_cad'));
		// $this->assertTrue($if->has('pessoa_exclu'));
	}

	/**
	 * Teste de insercao de religiao
	 */
	public function testInsert()
	{
		$religiao = $this->buildReligiao();
		$this->em->persist($religiao);
		$this->em->flush();

		$this->assertNotNull($religiao->getId());
		$this->assertEquals(1, $religiao->getId());

		/**
		 * Buscando no banco de dados a religiao que foi cadastrada
		 */
		$savedReligiao = $this->em->find(get_class($religiao), $religiao->getId());

		$this->assertInstanceOf(get_class($religiao), $savedReligiao);
		$this->assertEquals($religiao->getId(), $savedReligiao->getId());
	}

	public function testBuildPessoa()
	{
		$pessoa = new \Usuario\Entity\Pessoa;
		$pessoa->setNome("Steve Jobs");
    	$pessoa->setTipo("F");
    	$pessoa->setSituacao("A");
    	$pessoa->setOrigemGravacao("M");
    	$pessoa->setOperacao("I");
    	$pessoa->setIdsisCad(1);
    	$this->em->persist($pessoa);
    	$this->em->flush();

    	return $pessoa;
	}

	/**
	 * Teste de insercao salvando a pessoa que cadastrou na tabela
	 * dependencia de Usuario\Entity\Pessoa
	 * @depends testBuildPessoa
	 */
	public function testInsertWithPessoa($pessoa)
	{		
		$religiao = $this->buildReligiao();
		$religiao->setPessoaCad($pessoa);
		$this->em->persist($religiao);
		$this->em->flush();

		$this->assertNotNull($religiao->getId());
		$this->assertEquals(1, $religiao->getId());

		/**
		 * Buscando no banco de dados a religiao que foi cadastrada
		 */
		$savedReligiao = $this->em->find(get_class($religiao), $religiao->getId());

		$this->assertInstanceOf(get_class($religiao), $savedReligiao);
		$this->assertEquals($religiao->getId(), $savedReligiao->getId());
		$this->assertEquals($pessoa, $savedReligiao->getPessoaCad());		
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidReligiao()
	{		
		$religiao = $this->buildReligiao();		
		$religiao->setNome('Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i 
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
		$this->em->persist($religiao);
		$this->em->flush();		
	}

	public function testUpdate()
	{
		$religiao = $this->buildReligiao();
		$this->em->persist($religiao);

		$savedReligiao = $this->em->find('Usuario\Entity\Religiao', $religiao->getId());

		$this->assertEquals('Protestante', $religiao->getNome());

		$savedReligiao->setNome('Católica');

		$this->em->persist($savedReligiao);
		$this->em->flush();

		$savedReligiao = $this->em->find('Usuario\Entity\Religiao', $savedReligiao->getId());

		$this->assertEquals('Católica', $savedReligiao->getNome());
	}

	public function testDelete()
	{
		$religiao = $this->buildReligiao();
		$this->em->persist($religiao);
		$this->em->flush();

		$id = $religiao->getId();
		$savedReligiao = $this->em->find('Usuario\Entity\Religiao', $id);

		$this->em->remove($religiao);
		$this->em->flush();

		$savedReligiao = $this->em->find('Usuario\Entity\Religiao', $id);
		$this->assertNull($savedReligiao);

	}

	private function buildReligiao()
	{
		$religiao = new Religiao;
		$religiao->setNome('Protestante');
		$religiao->setDataCadastro(new \DateTime);
		// $religiao->setDataExclusao('');
		$religiao->setAtivo(true);
		// $religiao->setPessoaExclu('');
		// $religiao->setPessoaCad('');

		return $religiao;
	}
}