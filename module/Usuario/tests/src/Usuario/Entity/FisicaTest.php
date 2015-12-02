<?php
namespace Usuario\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Fisica;
use Usuario\Entity\Pessoa;
use Usuario\Entity\EnderecoExterno;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\ArrayCollection;


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
		$this->assertEquals(24, $if->count());
		$this->assertTrue($if->has('raca'));
		$this->assertTrue($if->has('dataNasc'));
		$this->assertTrue($if->has('sexo'));
		$this->assertTrue($if->has('dataUniao'));
		$this->assertTrue($if->has('dataObito'));
		$this->assertTrue($if->has('nacionalidade'));
		$this->assertTrue($if->has('dataChegadaBrasil'));
		$this->assertTrue($if->has('ultimaEmpresa'));
		$this->assertTrue($if->has('justificativaProvisorio'));
		$this->assertTrue($if->has('cpf'));
		$this->assertTrue($if->has('estadoCivil'));
		$this->assertTrue($if->has('foto'));
		$this->assertTrue($if->has('pessoaMae'));
		$this->assertTrue($if->has('pessoaPai'));
		$this->assertTrue($if->has('documento'));
		$this->assertTrue($if->has('municipioNascimento'));
        $this->assertTrue($if->has('telefones'));
        $this->assertTrue($if->has('paisEstrangeiro'));
	}

	/**
	 * Teste de Insercao de Fisica
	 * 
	 * Existe um relacionamento das Entidades Pessoa->Fisica One-To-One
	 * Onde para se ter um registro da entidade Fisica é necessario ter um registro na Entidade Pessoa
	 * Com o efeito cascade ao persistir uma instancia Pessoa, faz a persistencia na instacia Fisica
	 *
	 */
	public function testInsert()
	{
        $documento = $this->buildDocumento();
        $cepUnico = $this->buildCepUnico();
        $telefones = $this->buildTelefones();
        $pais = $this->buildPaisEstrangeiro();
		/**
		 * Cadastrando uma nova pessoa Fisica
		 */
		$fisica = $this->buildFisica();
		$fisica->setNome("Steve Jobs");
		$fisica->setSituacao("A");
        $fisica->setDocumento($documento);
        //$fisica->setMunicipioNascimento($cepUnico);
        $fisica->addTelefones($telefones);
        $fisica->setPaisEstrangeiro($pais);
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
        $this->assertEquals("1111111111", $savedPessoaFisica->getDocumento()->getRg());
        $this->assertNull($savedPessoaFisica->getMunicipioNascimento());
        // Telefones
        $this->assertEquals("74", $savedPessoaFisica->getTelefones()[0]->getDdd());
        $this->assertEquals("12345678", $savedPessoaFisica->getTelefones()[0]->getNumero());
        $this->assertEquals("74", $savedPessoaFisica->getTelefones()[0]->getDdd());
        $this->assertEquals("12345678", $savedPessoaFisica->getTelefones()[0]->getNumero());
        $this->assertEquals("74", $savedPessoaFisica->getTelefones()[1]->getDdd());
        $this->assertEquals("87654321", $savedPessoaFisica->getTelefones()[1]->getNumero());
        $this->assertEquals($savedPessoaFisica->getId(), $savedPessoaFisica->getTelefones()[0]->getFisica()->getId());
        $this->assertEquals($pais, $savedPessoaFisica->getPaisEstrangeiro());

	}


	/**
	 * @expectedException Core\Entity\EntityException	 
	 */
	public function testInputFilterInvalidCpf()
	{
		$fisica = $this->buildFisica();
		$fisica->setNome('Steve Jobs');
		$fisica->setSituacao('A');
		$fisica->setCpf('111.111.111-111');//cpf invalido 
		$this->em->persist($fisica);
		$this->em->flush();
	}

	/**
	 * @expectedException Core\Entity\EntityException
	 */
    public function testInputFilterInvalidNascionalidade()
    {
        $fisica = $this->buildFisica();
        $fisica->setNacionalidade(4);
        $this->em->persist($fisica);
        $this->em->flush();
    }
		
	public function testUpdate()
	{
		$fisica = $this->buildFisica();
		$fisica->setNome('Steve Jobs');
		$fisica->setSituacao('A');
		$fisica->setCpf('111.111.111-11');
		$this->em->persist($fisica);
		$this->em->flush();

		$savedFisica = $this->em->find('Usuario\Entity\Fisica', $fisica->getId());
		$this->assertEquals('Steve Jobs', $savedFisica->getNome());
		$savedFisica->setNome("Gold");
		$this->em->flush();

		$savedFisica2 = $this->em->find('Usuario\Entity\Fisica', $savedFisica->getId());
		$this->assertEquals('Gold', $savedFisica2->getNome());
	}

	public function testDelete()
	{
		$fisica = $this->buildFisica();
		$fisica->setNome('Steve Jobs');
		$fisica->setSituacao('A');
		$this->em->persist($fisica);
		$this->em->flush();

		$id = $fisica->getId();

		$savedFisica = $this->em->find('Usuario\Entity\Fisica', $id);

		$this->em->remove($savedFisica);
		$this->em->flush();

		$savedFisica = $this->em->find('Usuario\Entity\Fisica', $id);		
		$this->assertNull($savedFisica);

	}

    public function testRemoveTelefones()
    {
        $telefones = $this->buildTelefones();
        $fisica = $this->buildFisica();
        $fisica->setNome('Steve Jobs');
        $fisica->setSituacao('A');
        $fisica->addTelefones($telefones);
        $this->em->persist($fisica);
        $this->em->flush();
        $id = $fisica->getId();

        $savedFisica = $this->em->find('Usuario\Entity\Fisica', $id);
        $savedFisica->removeTelefones($telefones);

        $this->em->flush();

        $savedFisica = $this->em->find('Usuario\Entity\Fisica', $id);

        //Verfify colletion is empty
        $this->assertTrue($savedFisica->getTelefones()->isEmpty());
    }

	/**
	 * Teste inserindo e checando todos os dados possíveis para uma pessoa física
	 */
	public function testInsertFullData()
	{
        $estadoCivil = $this->builEstadoCivil();
        $this->em->persist($estadoCivil);

        $raca = $this->buildRaca();
        $this->em->persist($raca);

        $enderecoExterno = $this->buildEnderecoExterno();
        $this->em->persist($enderecoExterno);

        // Cadastrando Pai
        $pessoaPai = $this->buildFisica();
        $pessoaPai->setNome('Pai do Menino');
        $pessoaPai->setSituacao("A");
        $this->em->persist($pessoaPai);

        // Cadastrando Mae
        $pessoaMae = $this->buildFisica();
        $pessoaMae->setNome('Mae do Menino');
        $pessoaMae->setSexo('F');
        $pessoaMae->setSituacao("A");
        $this->em->persist($pessoaMae);

        // Cadastrando Municipio de Nascimento
        $cepUnico = $this->buildCepUnico();
        $this->em->persist($cepUnico);

        // Cadastrando pais
        $pais = $this->buildPaisEstrangeiro();
        $this->em->persist($pais);

        $this->em->flush();

		$fisica = $this->buildFisica();
        $fisica->setNome("Steve Jobs");
        $fisica->setSituacao("A");
        $date = new \DateTime("03-05-1982", new \DateTimeZone('America/Sao_Paulo'));
        $fisica->setDataNasc($date);
        $fisica->setCpf("111.111.111-11");
        $fisica->setSexo("M");
        $fisica->setEstadoCivil($estadoCivil);
        $fisica->setUrl('www.calangodev.com.br');
        $fisica->setEmail('ej@calangodev.com.br');
        $fisica->setRaca($raca);
        $fisica->setEnderecoExterno($enderecoExterno);
        $fisica->setPessoaPai($pessoaPai);
        $fisica->setPessoaMae($pessoaMae);
        $fisica->setMunicipioNascimento($cepUnico);
        $fisica->setPaisEstrangeiro($pais);

        $this->em->persist($fisica);
        $this->em->flush();

        $id = $fisica->getId();
        $savedFisica = $this->em->find('Usuario\Entity\Fisica', $id);

        $this->assertEquals(3, $savedFisica->getId());
        $this->assertEquals("Steve Jobs", $savedFisica->getNome());
        $this->assertEquals("A", $savedFisica->getSituacao());
        $date = new \DateTime("03-05-1982", new \DateTimeZone('America/Sao_Paulo'));
        $this->assertEquals($date, $savedFisica->getDataNasc());
        $this->assertEquals("11111111111", $savedFisica->getCpf());
        $this->assertEquals("M", $savedFisica->getSexo());
        $this->assertEquals("Solteiro(a)", $savedFisica->getEstadoCivil()->getDescricao());
        $this->assertEquals("www.calangodev.com.br", $savedFisica->getUrl());
        $this->assertEquals('ej@calangodev.com.br', $savedFisica->getEmail());
        $this->assertEquals($raca, $savedFisica->getRaca());
        $this->assertEquals($enderecoExterno, $savedFisica->getEnderecoExterno());
        $this->assertEquals($pessoaPai, $savedFisica->getPessoaPai());
        $this->assertEquals($pessoaMae, $savedFisica->getPessoaMae());
        $this->assertEquals($cepUnico->getId(), $savedFisica->getMunicipioNascimento());
        $this->assertEquals($pais->getId(), $savedFisica->getPaisEstrangeiro()->getId());

	}

	private function buildPessoa()
	{
		$pessoa = new Pessoa;
		$pessoa->setNome("Steve Jobs");
    	$pessoa->setSituacao("A");
    	
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

    	return $fisica;
	}

    private function buildRaca()
    {
        $raca = new \Usuario\Entity\Raca();
        $raca->setNome('Nome Raca');

        return $raca;
    }

    private function builEstadoCivil()
    {
        $estadoCivil = new \Usuario\Entity\EstadoCivil();
        $estadoCivil->setDescricao('Solteiro(a)');

        return $estadoCivil;
    }

	private function buildDocumento()
	{
        $orgaoEmissor = $this->buildOrgaoEmissorRg();
        $this->em->persist($orgaoEmissor);

        $uf = $this->buildUf();
        $this->em->persist($uf);

        $this->em->flush();

        $documento = new \Usuario\Entity\Documento();
        $documento->setRg('1111111111');
        $documento->setDataEmissaoRg(new \DateTime("10-10-2015", new \DateTimeZone('America/Sao_Paulo')));
        $documento->setSiglaUfEmissaoRg($uf);
        $documento->setTipoCertidaoCivil('1');
        $documento->setTermo('12345678');
        $documento->setLivro('LIVRO');
        $documento->setFolha('1234');
        $documento->setDataEmissaoCertidaoCivil(new \DateTime("10-10-2015", new \DateTimeZone('America/Sao_Paulo')));
        $documento->setSiglaUfCertidaoCivil($uf);
        $documento->setCartorioCertidaoCivil('CARTORIO CIVIL');
        $documento->setNumeroCarteiraTrabalho('123456789');
        $documento->setDataEmissaoCarteiraTrabalho(new \DateTime("10-10-2015", new \DateTimeZone('America/Sao_Paulo')));
        $documento->setSiglaUfCarteiraTrabalho($uf);
        $documento->setNumeroTituloEleitor('1234567890123');
        $documento->setZonaTituloEleitor('1234');
        $documento->setSecaoTituloEleitor('1234');
        $documento->setOrgaoEmissorRg($orgaoEmissor);

        return $documento;
	}

    private function buildOrgaoEmissorRg()
    {
        $orgaoEmissor = new \Usuario\Entity\OrgaoEmissorRg();
        $orgaoEmissor->setSigla('SSP');
        $orgaoEmissor->setDescricao('SSP');

        return $orgaoEmissor;
    }

    private function buildUf()
    {
        $uf = new \Core\Entity\Uf();
        $uf->setNome('Bahia');
        $uf->setUf('BA');
        $uf->setCep1('44000');
        $uf->setCep2('48900');

        return $uf;
    }

    private function buildCepUnico()
    {
        $cepUnico = new \Core\Entity\CepUnico();
        $cepUnico->setNome('Irecê');
        $cepUnico->setUf('BA');

        return $cepUnico;
    }

	private function buildTelefones()
    {
        $telefones = new ArrayCollection();

        $telefone = new \Usuario\Entity\Telefone();
        $telefone->setDdd('74');
        $telefone->setNumero('12345678');

        $telefones->add($telefone);

        $telefone2 = new \Usuario\Entity\Telefone();
        $telefone2->setDdd('74');
        $telefone2->setNumero('87654321');
        $telefones->add($telefone2);

        return $telefones;

    }

    private function buildPaisEstrangeiro()
    {
        $pais = new \Core\Entity\Pais();
        $pais->setNome('Argentina');

        return $pais;
    }

}