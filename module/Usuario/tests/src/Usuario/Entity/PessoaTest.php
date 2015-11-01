<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Pessoa;
use Historico\Entity\Pessoa as HistoricoPessoa;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;

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
		$this->assertEquals(12, $if->count());

		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('nome'));
		$this->assertTrue($if->has('url'));
		$this->assertTrue($if->has('tipo'));
		$this->assertTrue($if->has('dataRev'));
		$this->assertTrue($if->has('email'));
		$this->assertTrue($if->has('situacao'));
		$this->assertTrue($if->has('origemGravacao'));
		$this->assertTrue($if->has('operacao'));
		$this->assertTrue($if->has('idsisRev'));
		$this->assertTrue($if->has('idsisCad'));
		$this->assertTrue($if->has('idpesRev'));
		//$this->assertTrue($if->has('idpes_cad'));
	}

	/**
	 * Teste de Insercao de Pessoa
	 * @todo jogar o rev para o test de update, pois trata de uma revisao de registro
	 */
	public function testInsert()
	{
		$pessoaA = $this->buildPessoa();		
		$this->em->persist($pessoaA);

		$pessoaB = $this->buildPessoa();
		$pessoaB->setNome("GOLD");
										
		// $pessoaCadCollection = new ArrayCollection();
		// $pessoaCadCollection->add($pessoaA);
				
		//$pessoaB->pessoa_cad = $pessoaCadCollection;
		
		$pessoaB->setPessoaCad($pessoaA);

		$this->em->persist($pessoaB);		
		$this->em->flush();

		$savedPessoaA = $this->em->find('Usuario\Entity\Pessoa', $pessoaA->getId());
		$savedPessoaB = $this->em->find('Usuario\Entity\Pessoa', $pessoaB->getId());

		/**
		 * Verificando se salvou o registro no banco para a pessoaA
		 */
		$this->assertEquals('Steve Jobs', $savedPessoaA->getNome());
		$this->assertEquals(1, $savedPessoaA->getId());

		/**
		 * Verificando se os id da pessoa que cadastrou o registro são iguais
		 */
		$this->assertEquals($savedPessoaA->getId(), $savedPessoaB->getPessoaCad()->getId());
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
		$pessoa->setNome(null);
		$this->em->persist($pessoa);
		$this->em->flush();
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidoTipo()
	{
		$pessoa = $this->buildPessoa();
		$pessoa->setTipo("FF");
		$this->em->persist($pessoa);
		$this->em->flush();
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
	public function testInputFilterInvalidoDataRev()
	{
		$pessoa = $this->buildPessoa();
		$pessoa->setDataRev("2001-10-10 00:00:001");
		$this->em->persist($pessoa);
		$this->em->flush();
	}	

	/**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidoUrl()
    {
        $pessoa = $this->buildPessoa();
        $pessoa->setUrl("Mussum ipsum cacilds, vidis litro abertis. 
        Consetis adipiscings elitis. Pra lá , depois divoltis porris, 
        paradis. Paisis, filhis, espiritis santis. 
        Mé faiz elementum girarzis, nisi eros vermeio, 
        in elementis mé pra quem é amistosis quis leo. 
        Manduma pindureta quium dia nois paga. 
        Sapien in monti palavris qui num significa nadis i pareci latim.
         Interessantiss quisso pudia ce receita de bolis, 
         mais bolis eu num gostis.");
        $this->em->persist($pessoa);
		$this->em->flush();
    }

	public function testUpdate()
	{
		/**
		 * Primeira pessoa do sistema
		 */
		$pessoaA = $this->buildPessoa();
		$this->em->persist($pessoaA);

		/**
		 * Segunda pessoa do sistema
		 */
		$pessoaB = $this->buildPessoa();
		$pessoaB->setNome("Gold");

		/**
		 * Pessoa que esta cadastrando o segundo registro
		 */		
		$pessoaB->setPessoaCad($pessoaA);

		$this->em->persist($pessoaB);

		$id = $pessoaB->getId();
		
		/**
		 * Verificando se o idpes é igual a 2 da segunda pessoa cadastrada no sistema
		 */
		$this->assertEquals(2, $id);

		/**
		 * get data of Pessoa, id = 2
		 */
		$savedPessoaB = $this->em->find('Usuario\Entity\Pessoa', $id);

		/**
		 * Verificando se o nome da pessoaB é igual a Gold
		 */
		$this->assertEquals('Gold', $savedPessoaB->getNome());

		/**
		 * Alterando o nome da pessoa B
		 */
		$savedPessoaB->setNome('Bill <br>Gates');

		/**
		 * Pessoa que esta revisando o cadastro da pessoa B
		 */		
		$savedPessoaB->setPessoaRev($pessoaA) ;

		$this->em->persist($savedPessoaB);
		$this->em->flush();

		/**
		 * get data of Pessoa, id = 2 atualizados
		 */
		$savedPessoaB = $this->em->find('Usuario\Entity\Pessoa', $id);
		/**
		 * Verificando se o nome da pessoa B agora é Bill Gates
		 */
		$this->assertEquals('Bill Gates', $savedPessoaB->getNome());

		/**
		 * Verificando o idpes do usuario que fez a revisao é igual ao da pessoaA
		 */		
		$this->assertEquals($pessoaA->getId(), $savedPessoaB->getPessoaRev()->getId());
	}

	public function testDelete()
	{
		$pessoaA = $this->buildPessoa();		
		$this->em->persist($pessoaA);

		/**
		 * Inserindo uma segunda pessoa para testar a remocao em cascade
		 */
		$pessoaB = $this->buildPessoa();
		$pessoaB->setNome("Bill Gates");
		$pessoaB->setPessoaCad($pessoaA);

		$this->em->persist($pessoaB);		
		$this->em->flush();		

		$id = $pessoaA->getId();		
		$pessoa = $this->em->find('Usuario\Entity\Pessoa', $id);
				
		$this->em->remove($pessoa);		
		$this->em->flush();
		
		/**
		 * Verifica se a pessoa removida esta como null
		 */
		$pessoa = $this->em->find('Usuario\Entity\Pessoa', $id);
		$this->assertNull($pessoa);

		/**
		 * Verifica se a PessoaB ainda tem o id da PessoaA que foi removida
		 */
		$savedPessoaB = $this->em->find('Usuario\Entity\Pessoa', $pessoaB->getId());
		$this->assertNull($savedPessoaB->getPessoaCad()->getId());		
	}	

	// public function testeDeleteSimple()
	// {
	// 	$pessoa = $this->buildPessoa();
	// 	$this->em->persist($pessoa);		
	// 	$this->em->flush();
	// 	$pessoaSaved = $this->em->find('Usuario\Entity\Pessoa', $pessoa->id);		
	// 	$this->em->remove($pessoaSaved);
	// 	$this->em->flush();
	// 	$savedPessoa = $this->em->find('Usuario\Entity\Pessoa', 1);
	// 	$this->assertNull($savedPessoa);
	// }
	
	public function testHistoricoDelete()
	{
		$pessoaA = $this->buildPessoa();
		$pessoaA->setNome('Jose');
		$this->em->persist($pessoaA);

		$pessoa = $this->buildPessoa();
		$pessoa->setNome('Gold');
		$pessoa->setPessoaCad($pessoaA);
		
		$this->em->persist($pessoa);
		$this->em->flush();

		$id = $pessoa->getId();

		$savedPessoa = $this->em->find('Usuario\Entity\Pessoa', $id);

		$pessoaOriginal = clone $pessoa;

		$this->em->remove($savedPessoa);
		$this->em->flush();

		/**
		 * Verifica se a pessoa foi removida		 
		 */
		$savedPessoa = $this->em->find('Usuario\Entity\Pessoa', $id);
		$this->assertNull($savedPessoa);

		/**
		 * Verificar se os dados do historico bate com o clone feito
		 */		
		$savedHistorico = $this->em->getRepository('Historico\Entity\Pessoa')->findOneBy(array('idpes' => $pessoaOriginal->getId()));
		
		$this->assertEquals($savedHistorico->getNome(), $pessoaOriginal->getNome());
		$this->assertEquals($savedHistorico->getDataCad(), $pessoaOriginal->getDataCad());
		$this->assertEquals($savedHistorico->getUrl(), $pessoaOriginal->getUrl());		
		$this->assertEquals($savedHistorico->getTipo(), $pessoaOriginal->getTipo());		
		$this->assertNotEquals($savedHistorico->getDataRev(), $pessoaOriginal->getDataRev());
		$this->assertEquals($savedHistorico->getEmail(), $pessoaOriginal->getEmail());
		$this->assertEquals($savedHistorico->getSituacao(), $pessoaOriginal->getSituacao());
		$this->assertEquals($savedHistorico->getOrigemGravacao(), $pessoaOriginal->getOrigemGravacao());
		$this->assertEquals($savedHistorico->getOperacao(), $pessoaOriginal->getOperacao());
		$this->assertEquals($savedHistorico->getIdsisRev(), $pessoaOriginal->getIdsisRev());
		$this->assertEquals($savedHistorico->getIdSisCad(), $pessoaOriginal->getIdSisCad());

		//$this->assertEquals($savedHistorico->getIdpesCad(), $pessoaOriginal->getIdpesCad());
		//$this->asserEquals($savedHistorico->getIdpesRev(), $pessoaOriginal->getIdpesRev());
	}

	public function testHistoricoUpdate()
	{
		$pessoaA = $this->buildPessoa();
		$pessoaA->setNome('Jose');
		$this->em->persist($pessoaA);

		$pessoaB = $this->buildPessoa();
		$pessoaB->setNome('Joao');
		$this->em->persist($pessoaB);

		$pessoa = $this->buildPessoa();
		$pessoa->setPessoaCad($pessoaA);
		$this->em->persist($pessoa);
		$this->em->flush();

		$savedPessoa = $this->em->find('Usuario\Entity\Pessoa', $pessoa->getId());
		$this->assertEquals('Steve Jobs', $savedPessoa->getNome());

		$savedPessoa->setNome('Gold');
		// $this->em->persist($savedPessoa);
		$this->em->flush();

		$savedPessoa = $this->em->find('Usuario\Entity\Pessoa', $pessoa->getId());
		$savedHistorico = $this->em->getRepository('Historico\Entity\Pessoa')->findOneBy(array('idpes' => $pessoa->getId()));
		
		/**
		 * Verificar se os dados do historico bate com a pessoa Salva
		 */
		$this->assertEquals($savedHistorico->getNome(), 'Steve Jobs');
		$this->assertEquals($savedHistorico->getDataCad(), $savedPessoa->getDataCad());
		$this->assertEquals($savedHistorico->getUrl(), $savedPessoa->getUrl());
		$this->assertEquals($savedHistorico->getTipo(), $savedPessoa->getTipo());
		$this->assertNotEquals($savedHistorico->getDataRev(), $savedPessoa->getDataRev());
		$this->assertEquals($savedHistorico->getEmail(), $savedPessoa->getEmail());
		$this->assertEquals($savedHistorico->getSituacao(), $savedPessoa->getSituacao());
		$this->assertEquals($savedHistorico->getOrigemGravacao(), $savedPessoa->getOrigemGravacao());
		$this->assertEquals($savedHistorico->getOperacao(), $savedPessoa->getOperacao());
		$this->assertEquals($savedHistorico->getIdsisRev(), $savedPessoa->getIdsisRev());
		$this->assertEquals($savedHistorico->getIdSisCad(), $savedPessoa->getIdSisCad());
		$this->assertEquals($savedHistorico->getPessoaCad(), $savedPessoa->getPessoaCad());
		$this->assertNotEquals($savedHistorico->getPessoaCad(), $pessoaB);
		//$this->assertEquals($savedHistorico->getIdpesCad(), $savedPessoa->getIdpesCad());
		//$this->assertEquals($savedHistorico->getIdpesRev(), $savedPessoa->getIdpesRev());
	}
	

	private function buildPessoa()
	{
		// $pessoa1 = $this->pessoa();
		// $this->addPessoa($pessoa1);
		$pessoa = new Pessoa;
		$pessoa->setNome("Steve Jobs");
    	$pessoa->setTipo("F");
    	$pessoa->setSituacao("A");
    	$pessoa->setOrigemGravacao("M");
    	$pessoa->setOperacao("I");
    	$pessoa->setIdsisCad(1);
    	//$pessoa->idpes_cad = 10;
    	//$pessoa->idpes_rev = 1;    	
    	//$pessoa->forum_opiniao = 2;

    	return $pessoa;
	}
}