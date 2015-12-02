<?php
use Core\Test\ControllerTestCase;
use Usuario\Controller\FisicaController;
use Usuario\Entity\Fisica;
use Usuario\Entity\Raca;
use Usuario\Entity\EnderecoExterno;
use Core\Entity\tipoLogradouro;
use Core\Entity\Uf;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group  Controller
 */
class FisicaControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do Controller
	 * @var string FisicaController
	 */
	protected $controllerFQDN = 'Usuario\Controller\FisicaController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string usuario
	 */
	protected $controllerRoute = 'usuario';

	/**
	 * Testa a pagina inicial, que deve mostrar as pessoas fisicas
	 */
	public function testFisicaIndexAction()
	{
		//	cria pessoas fisicas para testar
		$pA = $this->buildFisica();
		$pA->setCpf("111.111.111-11");
		$pB = $this->buildFisica();
		$pB->setNome("GOLD");
		$pB->setCpf("222.222.222-22");
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($pA);
		$em->persist($pB);
		$em->flush();

		//	Invoca a rota index
		$this->routeMatch->setParam('action', 'index');
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica o response
		$response = $this->controller->getResponse();		
		$this->assertEquals(200, $response->getStatusCode());

		//	Testa se um ViewModel foi retornado
		$this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
		

		//	Testa os dados da View
		$variables = $result->getVariables();

		$this->assertArrayHasKey('dados', $variables);

		//	Faz a comparação dos dados		
		$paginator = $variables["dados"];
		$this->assertEquals('Zend\Paginator\Paginator', get_class($paginator));		
		$this->assertEquals($pA->getNome(), $paginator->getItem(1)->getNome());
		$this->assertEquals($pB->getNome(), $paginator->getItem(2)->getNome());

	}

	/**
	 * Testa a tela de inclusao de um novo registro
	 * @return void
	 */
	public function testFisicaSaveActionNewRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a resposta
		$response = $this->controller->getResponse();
		$this->assertEquals(200, $response->getStatusCode());

		//	Testa se recebeu um ViewModel
		$this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

		//	Verifica se existe um form
		$variables = $result->getVariables();
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];
		//	Testa os itens do formulario
		$id = $form->get('id');
		$this->assertEquals('id', $id->getName());
		$this->assertEquals('hidden', $id->getAttribute('type'));

		$dataNasc = $form->get('dataNasc');
		$this->assertEquals('dataNasc', $dataNasc->getName());
		$this->assertEquals('text', $dataNasc->getAttribute('type'));

		$sexo = $form->get('sexo');
		$this->assertEquals('sexo', $sexo->getName());
		$this->assertEquals('Zend\Form\Element\Select', $sexo->getAttribute('type'));

		$dataUniao = $form->get('dataUniao');
		$this->assertEquals('dataUniao', $dataUniao->getName());
		$this->assertEquals('text', $dataUniao->getAttribute('type'));

		$nacionalidade = $form->get('nacionalidade');
		$this->assertEquals('nacionalidade', $nacionalidade->getName());
		$this->assertEquals('Zend\Form\Element\Select', $nacionalidade->getAttribute('type'));

		$dataChegadaBrasil = $form->get('dataChegadaBrasil');
		$this->assertEquals('dataChegadaBrasil', $dataChegadaBrasil->getName());
		$this->assertEquals('text', $dataChegadaBrasil->getAttribute('type'));

		$ultimaEmpresa = $form->get('ultimaEmpresa');
		$this->assertEquals('ultimaEmpresa', $ultimaEmpresa->getName());
		$this->assertEquals('text', $ultimaEmpresa->getAttribute('type'));

		$nomeMae = $form->get('nomeMae');
		$this->assertEquals('nomeMae', $nomeMae->getName());
		$this->assertEquals('text', $nomeMae->getAttribute('type'));

		$nomeResponsavel = $form->get('nomeResponsavel');
		$this->assertEquals('nomeResponsavel', $nomeResponsavel->getName());
		$this->assertEquals('text', $nomeResponsavel->getAttribute('type'));

		$justificativaProvisorio = $form->get('justificativaProvisorio');
		$this->assertEquals('justificativaProvisorio', $justificativaProvisorio->getName());
		$this->assertEquals('text', $justificativaProvisorio->getAttribute('type'));

		$cpf = $form->get('cpf');
		$this->assertEquals('cpf', $cpf->getName());
		$this->assertEquals('text', $cpf->getAttribute('type'));

		$paisEstrangeiro = $form->get('paisEstrangeiro');
		$this->assertEquals('paisEstrangeiro', $paisEstrangeiro->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $paisEstrangeiro->getAttribute('type'));

		$idesco = $form->get('idesco');
		$this->assertEquals('idesco', $idesco->getName());
		$this->assertEquals('Zend\Form\Element\Select', $idesco->getAttribute('type'));

		$ideciv = $form->get('ideciv');
		$this->assertEquals('ideciv', $ideciv->getName());
		$this->assertEquals('Zend\Form\Element\Select', $ideciv->getAttribute('type'));

		$idocup = $form->get('idocup');
		$this->assertEquals('idocup', $idocup->getName());
		$this->assertEquals('text', $idocup->getAttribute('type'));

		$refCodReligiao = $form->get('refCodReligiao');
		$this->assertEquals('refCodReligiao', $refCodReligiao->getName());
		$this->assertEquals('Zend\Form\Element\Select', $refCodReligiao->getAttribute('type'));		

		$enderecoExterno = $form->get('enderecoExterno');

		// $apartamento = $form->get('apartamento');
		$apartamento = $enderecoExterno->get('apartamento');		
		$this->assertEquals('apartamento', $apartamento->getName());
		$this->assertEquals('text', $apartamento->getAttribute('type'));

		// $bloco = $form->get('bloco');
		$bloco = $enderecoExterno->get('bloco');
		$this->assertEquals('bloco', $bloco->getName());
		$this->assertEquals('text', $bloco->getAttribute('type'));

		// $andar = $form->get('andar');
		$andar = $enderecoExterno->get('andar');
		$this->assertEquals('andar', $andar->getName());
		$this->assertEquals('text', $andar->getAttribute('type'));

//		$zonaLocalizacao = $form->get('zonaLocalizacao');
		$zonaLocalizacao = $enderecoExterno->get('zonaLocalizacao');
		$this->assertEquals('zonaLocalizacao', $zonaLocalizacao->getName());
		$this->assertEquals('select', $zonaLocalizacao->getAttribute('type'));

        $raca = $form->get('raca');
        $this->assertEquals('raca', $raca->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $raca->getAttribute('type'));

        $estadoCivil = $form->get('estadoCivil');
        $this->assertEquals('estadoCivil', $estadoCivil->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $estadoCivil->getAttribute('type'));

		$pessoaPai = $form->get('pessoaPai');
		$this->assertEquals('pessoaPai', $pessoaPai->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $pessoaPai->getAttribute('type'));

        $pessoaMae = $form->get('pessoaMae');
        $this->assertEquals('pessoaMae', $pessoaMae->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $pessoaMae->getAttribute('type'));

		$municipioNascimento = $form->get('municipioNascimento');
        $this->assertEquals('municipioNascimento', $municipioNascimento->getName());
        $this->assertEquals('hidden', $municipioNascimento->getAttribute('type'));

        /**
         * documento form fieldset
         */
        $documento = $form->get('documento');

        $idDocumento = $documento->get('id');
        $this->assertEquals('id', $idDocumento->getName());
        $this->assertEquals('hidden', $idDocumento->getAttribute('type'));

        $rg = $documento->get('rg');
        $this->assertEquals('rg', $rg->getName());
        $this->assertEquals('text', $rg->getAttribute('type'));

        $dataEmissaoRg = $documento->get('dataEmissaoRg');
        $this->assertEquals('dataEmissaoRg', $dataEmissaoRg->getName());
        $this->assertEquals('text', $dataEmissaoRg->getAttribute('type'));

        $siglaUfEmissaoRg = $documento->get('siglaUfEmissaoRg');
        $this->assertEquals('siglaUfEmissaoRg', $siglaUfEmissaoRg->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $siglaUfEmissaoRg->getAttribute('type'));

        $tipoCertidaoCivil = $documento->get('tipoCertidaoCivil');
        $this->assertEquals('tipoCertidaoCivil', $tipoCertidaoCivil->getName());
        $this->assertEquals('Zend\Form\Element\Select', $tipoCertidaoCivil->getAttribute('type'));

        $termo = $documento->get('termo');
        $this->assertEquals('termo', $termo->getName());
        $this->assertEquals('text', $termo->getAttribute('type'));

        $livro = $documento->get('livro');
        $this->assertEquals('livro', $livro->getName());
        $this->assertEquals('text', $termo->getAttribute('type'));

        $folha = $documento->get('folha');
        $this->assertEquals('folha', $folha->getName());
        $this->assertEquals('text', $folha->getAttribute('type'));

        $dataEmissaoCertidaoCivil = $documento->get('dataEmissaoCertidaoCivil');
        $this->assertEquals('dataEmissaoCertidaoCivil', $dataEmissaoCertidaoCivil->getName());
        $this->assertEquals('text', $dataEmissaoCertidaoCivil->getAttribute('type'));

        $siglaUfCertidaoCivil = $documento->get('siglaUfCertidaoCivil');
        $this->assertEquals('siglaUfCertidaoCivil', $siglaUfCertidaoCivil->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $siglaUfCertidaoCivil->getAttribute('type'));

        $cartorioCertidaoCivil = $documento->get('cartorioCertidaoCivil');
        $this->assertEquals('cartorioCertidaoCivil', $cartorioCertidaoCivil->getName());
        $this->assertEquals('textarea', $cartorioCertidaoCivil->getAttribute('type'));

        $numeroCarteiraTrabalho = $documento->get('numeroCarteiraTrabalho');
        $this->assertEquals('numeroCarteiraTrabalho', $numeroCarteiraTrabalho->getName());
        $this->assertEquals('text', $numeroCarteiraTrabalho->getAttribute('type'));

        $serieCarteiraTrabalho = $documento->get('serieCarteiraTrabalho');
        $this->assertEquals('serieCarteiraTrabalho', $serieCarteiraTrabalho->getName());
        $this->assertEquals('text', $serieCarteiraTrabalho->getAttribute('type'));

        $dataEmissaoCertidaoCivil = $documento->get('dataEmissaoCertidaoCivil');
        $this->assertEquals('dataEmissaoCertidaoCivil', $dataEmissaoCertidaoCivil->getName());
        $this->assertEquals('text', $dataEmissaoCertidaoCivil->getAttribute('type'));

        $siglaUfCarteiraTrabalho = $documento->get('siglaUfCarteiraTrabalho');
        $this->assertEquals('siglaUfCarteiraTrabalho', $siglaUfCarteiraTrabalho->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $siglaUfCarteiraTrabalho->getAttribute('type'));

        $numeroTituloEleitor = $documento->get('numeroTituloEleitor');
        $this->assertEquals('numeroTituloEleitor', $numeroTituloEleitor->getName());
        $this->assertEquals('text', $numeroTituloEleitor->getAttribute('type'));

        $zonaTituloEleitor = $documento->get('zonaTituloEleitor');
        $this->assertEquals('zonaTituloEleitor', $zonaTituloEleitor->getName());
        $this->assertEquals('text', $zonaTituloEleitor->getAttribute('type'));

        $secaoTituloEleitor = $documento->get('secaoTituloEleitor');
        $this->assertEquals('secaoTituloEleitor', $secaoTituloEleitor->getName());
        $this->assertEquals('text', $secaoTituloEleitor->getAttribute('type'));

        $orgaoEmissorRg = $documento->get('orgaoEmissorRg');
        $this->assertEquals('orgaoEmissorRg', $orgaoEmissorRg->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $orgaoEmissorRg->getAttribute('type'));

        $certidaoNascimento = $documento->get('certidaoNascimento');
        $this->assertEquals('certidaoNascimento', $certidaoNascimento->getName());
        $this->assertEquals('text', $certidaoNascimento->getAttribute('type'));

        $telefones = $form->get('telefones');
        $this->assertEquals('telefones', $telefones->getName());
        $this->assertEquals('Zend\Form\Element\Collection', $telefones->getAttribute('type'));

	}

	/**
	 * Testa a tela de alteracao de um registro
	 */
	public function testFisicaSaveActionUpdateFormRequest()
	{
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $fisica->getId());
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a resposta
		$response = $this->controller->getResponse();
		$this->assertEquals(200, $response->getStatusCode());

		//	Testa se recebeu um ViewModel
		$this->assertInstanceOf('Zend\View\Model\ViewModel', $result);		
		$variables = $result->getVariables();

		//	Verifica se existe um form
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];

		//	Testa os itens do formulario
		$id = $form->get('id');
		$cpf = $form->get('cpf');
		$this->assertEquals('id', $id->getName());
		$this->assertEquals($fisica->getId(), $id->getValue());
		$this->assertEquals($fisica->getCpf(), $cpf->getValue());
	}

	/**
	 * Testa a inclusao de uma nova pessoa fisica
	 */
	public function testFisicaSaveActionPostRequest()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');

        //	Cadastra uma raça
		$raca = $this->buildRaca();
		$em->persist($raca);

        // Cadastra um tipo de logradouro
		$tipoLogradouro = $this->buildTipoLogradouro();
		$em->persist($tipoLogradouro);

        // Cadastra um Uf
		$uf = $this->buildUf();
		$em->persist($uf);

        // Cadastra um municipio
        $cepUnico = $this->buildCepUnico();
        $em->persist($cepUnico);

        // Cadastra um pais
        $paisEstrangeiro = $this->buildPaisEstrangeiro();
        $em->persist($paisEstrangeiro);
		
		$em->flush();		

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('situacao', 'A');
		$this->request->getPost()->set('nome', 'Garrincha');
		$this->request->getPost()->set('sexo', 'M');
		$this->request->getPost()->set('cep', '44900-000');		
		$this->request->getPost()->set('url', 'www.calangodev.com.br');
		$this->request->getPost()->set('email', 'ej@calangodev.com.br');
		$this->request->getPost()->set('situacao', 'A');
		$this->request->getPost()->set('raca', "0");
		$enderecoExterno = array(
                'id' => '0',
				'cep' => '',
				'tipoLogradouro' => '0',
				'logradouro' => '',
				'numero' => '',
				'cidade' => '',
				'bairro' => '',
				'siglaUf' => '',
				'letra' => '',
				'apartamento' =>'',
				'bloco' => '',
				'andar' => '',
				'zonaLocalizacao' => ''
		);

        $this->request->getPost()->set('enderecoExterno', $enderecoExterno);

        $documento = array(
            'id' => '0',
            'rg' => '',
            'dataEmissaoRg' => '',
            'siglaUfEmissaoRg' => '0',
            'tipoCertidaoCivil' => '',
            'termo' => '',
            'livro' => '',
            'folha' => '',
            'dataEmissaoCertidaoCivil' => '',
            'siglaUfCertidaoCivil' => '0',
            'cartorioCertidaoCivil' => '',
            'numeroCarteiraTrabalho' => '',
            'serieCarteiraTrabalho' => '',
            'dataEmissaoCarteiraTrabalho' => '',
            'siglaUfCarteiraTrabalho' => '0',
            'numeroTituloEleitor' => '',
            'zonaTituloEleitor' => '',
            'secaoTituloEleitor' => '',
            'orgaoEmissorRg' => '0',
            'certidaoNascimento' => '',
        );
        $this->request->getPost()->set('documento', $documento);
        $this->request->getPost()->set('municipioNascimento', $cepUnico);

		$telefones = array(array(
		    'id' => '',
            'ddd' => '74',
            'numero' => '12345678'
            )
		);
        $this->request->getPost()->set('telefones', $telefones);
        $this->request->getPost()->set('paisEstrangeiro', $paisEstrangeiro->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302		
		// var_dump($result->getVariables()['form']);
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /usuario/fisica', $headers->get('Location'));
	}

    public function testFisicaSaveActionPostRequestEmptyEntity()
    {
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');

        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'CalangoDev');
        $this->request->getPost()->set('raca', "0");
        $this->request->getPost()->set('situacao', 'A');
        $this->request->getPost()->set('sexo', 'M');
        $enderecoExterno = array(
            'id' => '0',
            'cep' => '',
            'tipoLogradouro' => '0',
            'logradouro' => '',
            'numero' => '',
            'cidade' => '',
            'bairro' => '',
            'siglaUf' => '',
            'letra' => '',
            'apartamento' =>'',
            'bloco' => '',
            'andar' => '',
            'zonaLocalizacao' => ''
        );
        $this->request->getPost()->set('enderecoExterno', $enderecoExterno);
        $documento = array(
            'id' => '0',
            'rg' => '',
            'dataEmissaoRg' => '',
            'siglaUfEmissaoRg' => '0',
            'tipoCertidaoCivil' => '',
            'termo' => '',
            'livro' => '',
            'folha' => '',
            'dataEmissaoCertidaoCivil' => '',
            'siglaUfCertidaoCivil' => '0',
            'cartorioCertidaoCivil' => '',
            'numeroCarteiraTrabalho' => '',
            'serieCarteiraTrabalho' => '',
            'dataEmissaoCarteiraTrabalho' => '',
            'siglaUfCarteiraTrabalho' => '0',
            'numeroTituloEleitor' => '',
            'zonaTituloEleitor' => '',
            'secaoTituloEleitor' => '',
            'orgaoEmissorRg' => '0',
            'certidaoNascimento' => '',
        );
        $this->request->getPost()->set('documento', $documento);
        $this->request->getPost()->set('municipioNascimento', '');
        $telefones = array(array(
            'id' => '',
            'ddd' => '',
            'numero' => ''
            )
        );
        $this->request->getPost()->set('telefones', $telefones);
        $this->request->getPost()->set('paisEstrangeiro', '');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, estao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /usuario/fisica', $headers->get('Location'));

        $savedFisica = $em->find('Usuario\Entity\Fisica', 1);
        $this->assertEquals('CalangoDev', $savedFisica->getNome());
    }

    /**
     * Testa a inclusao de uma pessoa fisica, e depois verifica se os dados foram cadastrados com sucesso
     */
    public function testFisicaSaveActionPostRequestAndCheckData()
    {
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        //	Cadastra uma raça
        $raca = $this->buildRaca();
        $em->persist($raca);
        // Cadastra um tipo de logradouro
        $tipoLogradouro = $this->buildTipoLogradouro();
        $em->persist($tipoLogradouro);
        // Cadastra um Uf
        $uf = $this->buildUf();
        $em->persist($uf);

        // Cadastrar estado Civil
        $estadoCivil = $this->buildEstadoCivil();
        $em->persist($estadoCivil);

        // Cadastrar Mae
        $pessoaMae = $this->buildFisica();
        $pessoaMae->setSexo('F');
        $pessoaMae->setNome('Mae do Menino');
        $em->persist($pessoaMae);

        // Cadastrar Pai
        $pessoaPai = $this->buildFisica();
        $pessoaPai->setSexo('M');
        $pessoaPai->setNome('Pai do Menino');
        $em->persist($pessoaPai);

        // Cadastrar OrgaoEmissorRg
        $orgaoEmissorRg = $this->buildOrgaoEmissorRg();
        $em->persist($orgaoEmissorRg);

        // Cadastra um municipio
        $cepUnico = $this->buildCepUnico();
        $em->persist($cepUnico);

        // Cadastrar um pais
        $pais = $this->buildPaisEstrangeiro();
        $em->persist($pais);

        $em->flush();

        //	Dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'CalangoDev');
        $this->request->getPost()->set('cpf', '001-555-345-05');
        $this->request->getPost()->set('situacao', 'A');
        $this->request->getPost()->set('dataNasc', '03-05-1982');
        $this->request->getPost()->set('sexo', 'M');
        $this->request->getPost()->set('estadoCivil', $estadoCivil->getId());
        $this->request->getPost()->set('pessoaMae', $pessoaMae->getId());
        $this->request->getPost()->set('pessoaPai', $pessoaPai->getId());
        $this->request->getPost()->set('raca', $raca->getId());

        $enderecoExterno = array(
            'cep' => '44900-000',
            'tipoLogradouro' => $tipoLogradouro->getId(),
            'logradouro' => 'Endereço do Fulano',
            'numero' => '1',
            'cidade' => 'Irecê',
            'bairro' => 'Centro',
            'siglaUf' => '5',
            'letra' => 'A',
            'apartamento' =>'101',
            'bloco' => '1',
            'andar' => '1',
            'zonaLocalizacao' => '1',
            'id' => '0'
        );

        $this->request->getPost()->set('enderecoExterno', $enderecoExterno);

        //new \DateTime("10-10-2015", new \DateTimeZone('America/Sao_Paulo'))

        $documento = array(
            'id' => '0',
            'rg' => '1234567890',
            'dataEmissaoRg' => '10-10-2015',
            'siglaUfEmissaoRg' => $uf->getId(),
            'tipoCertidaoCivil' => '1',
            'termo' => 'termo',
            'livro' => 'livro',
            'folha' => '1234',
            'dataEmissaoCertidaoCivil' => '10-10-2015',
            'siglaUfCertidaoCivil' => $uf->getId(),
            'cartorioCertidaoCivil' => 'cartorio',
            'numeroCarteiraTrabalho' => 'numero',
            'serieCarteiraTrabalho' => '12345',
            'dataEmissaoCarteiraTrabalho' => '10-10-2015',
            'siglaUfCarteiraTrabalho' => $uf->getId(),
            'numeroTituloEleitor' => 'numerotitulo',
            'zonaTituloEleitor' => '1234',
            'secaoTituloEleitor' => '1234',
            'orgaoEmissorRg' => $orgaoEmissorRg->getId(),
            'certidaoNascimento' => 'certidaonascimento'
        );
        $this->request->getPost()->set('documento', $documento);
        $this->request->getPost()->set('municipioNascimento', '1');
        $telefones = array(array(
            'id' => '',
            'ddd' => '74',
            'numero' => '12345678'
            )
        );
        $this->request->getPost()->set('telefones', $telefones);
        $this->request->getPost()->set('paisEstrangeiro', $pais->getId());


        $this->request->getPost()->set('url', 'www.calangodev.com.br');
        $this->request->getPost()->set('email', 'ej@calangodev.com.br');



        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, estao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /usuario/fisica', $headers->get('Location'));

        $savedFisica = $em->find('Usuario\Entity\Fisica', 3);
        $this->assertEquals('CalangoDev', $savedFisica->getNome());
        $this->assertEquals('00155534505', $savedFisica->getCpf());
        $this->assertEquals('A', $savedFisica->getSituacao());
        $date = new \DateTime("03-05-1982", new \DateTimeZone('America/Sao_Paulo'));
        $this->assertEquals($date->format('d-m-Y'), $savedFisica->getDataNasc()->format('d-m-Y'));
        $this->assertEquals('M', $savedFisica->getSexo());
        $this->assertEquals($estadoCivil, $savedFisica->getEstadoCivil());
        $this->assertEquals('www.calangodev.com.br', $savedFisica->getUrl());
        $this->assertEquals('ej@calangodev.com.br', $savedFisica->getEmail());
        $this->assertEquals($raca, $savedFisica->getRaca());
        $this->assertEquals($pessoaPai, $savedFisica->getPessoaPai());
        $this->assertEquals($pessoaMae, $savedFisica->getPessoaMae());
        $this->assertEquals($raca, $savedFisica->getRaca());
        $this->assertEquals('44900-000', $savedFisica->getEnderecoExterno()->getCep());
		$this->assertEquals($tipoLogradouro, $savedFisica->getEnderecoExterno()->getTipoLogradouro());
        $this->assertEquals('Endereço do Fulano', $savedFisica->getEnderecoExterno()->getLogradouro());
        $this->assertEquals('1', $savedFisica->getEnderecoExterno()->getNumero());
        $this->assertEquals('Irecê', $savedFisica->getEnderecoExterno()->getCidade());
        $this->assertEquals('Centro', $savedFisica->getEnderecoExterno()->getBairro());
        $this->assertEquals('5', $savedFisica->getEnderecoExterno()->getSiglaUf());
        $this->assertEquals('A', $savedFisica->getEnderecoExterno()->getLetra());
        $this->assertEquals('101', $savedFisica->getEnderecoExterno()->getApartamento());
        $this->assertEquals('1', $savedFisica->getEnderecoExterno()->getBloco());
        $this->assertEquals('1', $savedFisica->getEnderecoExterno()->getAndar());
        $this->assertEquals('1', $savedFisica->getEnderecoExterno()->getZonaLocalizacao());
        //var_dump($savedFisica->getDocumento());
        //var_dump($savedFisica->getDocumento()->getFisica());
        $this->assertEquals('1', $savedFisica->getDocumento()->getId());
        $this->assertEquals('1234567890', $savedFisica->getDocumento()->getRg());
        $this->assertEquals("10-10-2015", $savedFisica->getDocumento()->getDataEmissaoRg());
        $this->assertEquals($uf, $savedFisica->getDocumento()->getSiglaUfEmissaoRg());
        $this->assertEquals('1', $savedFisica->getDocumento()->getTipoCertidaoCivil());
        $this->assertEquals('termo', $savedFisica->getDocumento()->getTermo());
        $this->assertEquals('livro', $savedFisica->getDocumento()->getLivro());
        $this->assertEquals('1234', $savedFisica->getDocumento()->getFolha());
        $this->assertEquals("10-10-2015", $savedFisica->getDocumento()->getDataEmissaoCertidaoCivil());
        $this->assertEquals($uf, $savedFisica->getDocumento()->getSiglaUfCertidaoCivil());
        $this->assertEquals('cartorio', $savedFisica->getDocumento()->getCartorioCertidaoCivil());
        $this->assertEquals('numero', $savedFisica->getDocumento()->getNumeroCarteiraTrabalho());
        $this->assertEquals('12345', $savedFisica->getDocumento()->getSerieCarteiraTrabalho());
        $this->assertEquals("10-10-2015", $savedFisica->getDocumento()->getDataEmissaoCarteiraTrabalho());
        $this->assertEquals($uf, $savedFisica->getDocumento()->getSiglaUfCarteiraTrabalho());
        $this->assertEquals('numerotitulo', $savedFisica->getDocumento()->getNumeroTituloEleitor());
        $this->assertEquals('1234', $savedFisica->getDocumento()->getZonaTituloEleitor());
        $this->assertEquals('1234', $savedFisica->getDocumento()->getSecaoTituloEleitor());
        $this->assertEquals($orgaoEmissorRg, $savedFisica->getDocumento()->getOrgaoEmissorRg());
        $this->assertEquals('certidaonascimento', $savedFisica->getDocumento()->getCertidaoNascimento());
        $this->assertEquals($cepUnico->getId(), $savedFisica->getMunicipioNascimento());

        foreach ($savedFisica->getTelefones() as $telefone) {

            $this->assertEquals('74', $telefone->getDdd());
            $this->assertEquals('12345678', $telefone->getNumero());

        }

        $this->assertEquals($pais, $savedFisica->getPaisEstrangeiro());

    }



    /**
     * Testa a inclusao, formulario invalido e cpf vazio
     * Nome vazio, formulario igual a invalido
     */
    public function testFisicaSaveActionInvalidFormPostRequest()
    {
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        //	Cadastra uma raça
        $raca = $this->buildRaca();
        $em->persist($raca);
        // Cadastra um tipo de logradouro
        $tipoLogradouro = $this->buildTipoLogradouro();
        $em->persist($tipoLogradouro);
        // Cadastra um Uf
        $uf = $this->buildUf();
        $em->persist($uf);

        // Cadastra um municipio
        $cepUnico = $this->buildCepUnico();
        $em->persist($cepUnico);

        // cadastra um pais
        $pais = $this->buildPaisEstrangeiro();
        $em->persist($pais);

        $em->flush();

        //	Dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('url', 'www.eduardojunior.com');
        $this->request->getPost()->set('email', 'ej@eduardojunior.com');
        $this->request->getPost()->set('situacao', 'A');
        $this->request->getPost()->set('nacionalidade', "1");
        $this->request->getPost()->set('raca', $raca->getId());
        $this->request->getPost()->set('apartamento', '001');
        $this->request->getPost()->set('bloco', 'A');
		$this->request->getPost()->set('andar', '1');
		$this->request->getPost()->set('dddTelefone1', '71');
		$this->request->getPost()->set('telefone1', '1111-1111');
		$this->request->getPost()->set('dddTelefone2', '71');
		$this->request->getPost()->set('telefone2', '1111-1111');
		$this->request->getPost()->set('dddCelular', '71');
		$this->request->getPost()->set('celular', '1111-1111');
		$this->request->getPost()->set('dddFax', '71');
		$this->request->getPost()->set('fax', '1111-1111');
		$this->request->getPost()->set('logradouro', 'Rua X');
		$this->request->getPost()->set('cidade', 'Irecê');
		$this->request->getPost()->set('cpf', '');
        $this->request->getPost()->set('sexo', '');
        $this->request->getPost()->set('tipoLogradouro', '');
        $this->request->getPost()->set('siglaUf', '');
        $this->request->getPost()->set('zonaLocalizacao', '');
        $this->request->getPost()->set('cep', '');
        // Parametros requiridos que vao ser passados em branco
        $this->request->getPost()->set('nome', '');
        $enderecoExterno = array(
            'id' => '0',
            'cep' => '',
            'tipoLogradouro' => '0',
            'logradouro' => '',
            'numero' => '',
            'cidade' => '',
            'bairro' => '',
            'siglaUf' => '',
            'letra' => '',
            'apartamento' =>'',
            'bloco' => '',
            'andar' => '',
            'zonaLocalizacao' => ''
        );
        $this->request->getPost()->set('enderecoExterno', $enderecoExterno);
        $documento = array(
            'id' => '0',
            'rg' => '',
            'dataEmissaoRg' => '',
            'siglaUfEmissaoRg' => '0',
            'tipoCertidaoCivil' => '',
            'termo' => '',
            'livro' => '',
            'folha' => '',
            'dataEmissaoCertidaoCivil' => '',
            'siglaUfCertidaoCivil' => '0',
            'cartorioCertidaoCivil' => '',
            'numeroCarteiraTrabalho' => '',
            'serieCarteiraTrabalho' => '',
            'dataEmissaoCarteiraTrabalho' => '',
            'siglaUfCarteiraTrabalho' => '0',
            'numeroTituloEleitor' => '',
            'zonaTituloEleitor' => '',
            'secaoTituloEleitor' => '',
            'orgaoEmissorRg' => '0',
            'certidaoNascimento' => '',
        );
        $this->request->getPost()->set('documento', $documento);
        $this->request->getPost()->set('municipioNascimento', $cepUnico->getId());
        $telefones = array(array(
            'id' => '',
            'ddd' => '',
            'numero' => ''
        )
        );
        $this->request->getPost()->set('telefones', $telefones);
        $this->request->getPost()->set('pais', $pais->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();

        //	a pagina nao redireciona por causa do erro, estao o status = 200
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();

        //	Verify Filters Validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs["nome"]['isEmpty']);
        $this->assertEquals('Value is required and can\'t be empty', $msgs["sexo"]['isEmpty']);
    }

	/**
	 * Testa o update de uma pessoa fisica
	 */
	public function testFisicaUpdateAction()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		//	Cadastra uma raça
		$raca = $this->buildRaca();		
		$em->persist($raca);
		// Cadastra um tipo de logradouro
		$tipoLogradouro = $this->buildTipoLogradouro();
		$em->persist($tipoLogradouro);
		// Cadastra um Uf
		$uf = $this->buildUf();
		$em->persist($uf);

        // Cadastra um municipio
        $cepUnico = $this->buildCepUnico();
        $em->persist($cepUnico);

        // Cadastra um municipio
        $cepUnicoSalvador = $this->buildCepUnico();
        $cepUnicoSalvador->setNome('Salvador');
        $em->persist($cepUnicoSalvador);

        // cadastra um pais
        $pais = $this->buildPaisEstrangeiro();
        $em->persist($pais);
		

		$fisica = $this->buildFisica();
		$fisica->setNome('Bill Gates');
        $date = new \DateTime('03/05/1982', new \DateTimeZone('America/Sao_Paulo'));
        $fisica->setDataNasc($date);
        $fisica->setMunicipioNascimento($cepUnico);
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
    	$em->flush();
				
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $fisica->getId());

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $fisica->getId());
		$this->request->getPost()->set('nome', 'Alan Turing');
		$this->request->getPost()->set('url', '');
		$this->request->getPost()->set('tipo', 'J');
		$this->request->getPost()->set('email', '');
		$this->request->getPost()->set('situacao', 'I');
		$this->request->getPost()->set('sexo', 'M');
		$this->request->getPost()->set('cpf', '222.222.222-22');
		$this->request->getPost()->set('raca', $raca->getId());
		$this->request->getPost()->set('tipoLogradouro', $tipoLogradouro->getId());
		$this->request->getPost()->set('siglaUf', $uf->getId());
        $this->request->getPost()->set('dataNasc', '03/05/1982');
        $this->request->getPost()->set('apartamento', '001');
		$this->request->getPost()->set('bloco', 'A');
		$this->request->getPost()->set('andar', '1');
		$this->request->getPost()->set('zonaLocalizacao', '1');
		$this->request->getPost()->set('dddTelefone1', '71');
		$this->request->getPost()->set('telefone1', '1111-1111');
		$this->request->getPost()->set('dddTelefone2', '71');
		$this->request->getPost()->set('telefone2', '1111-1111');
		$this->request->getPost()->set('dddCelular', '71');
		$this->request->getPost()->set('celular', '1111-1111');
		$this->request->getPost()->set('dddFax', '71');
		$this->request->getPost()->set('fax', '1111-1111');
		$this->request->getPost()->set('logradouro', 'Rua X');
		$this->request->getPost()->set('cidade', 'Irecê');
        $enderecoExterno = array(
            'id' => '0',
            'cep' => '',
            'tipoLogradouro' => '0',
            'logradouro' => '',
            'numero' => '',
            'cidade' => '',
            'bairro' => '',
            'siglaUf' => '',
            'letra' => '',
            'apartamento' =>'',
            'bloco' => '',
            'andar' => '',
            'zonaLocalizacao' => ''
        );

        $this->request->getPost()->set('enderecoExterno', $enderecoExterno);

        $documento = array(
            'id' => '0',
            'rg' => '',
            'dataEmissaoRg' => '',
            'siglaUfEmissaoRg' => '0',
            'tipoCertidaoCivil' => '',
            'termo' => '',
            'livro' => '',
            'folha' => '',
            'dataEmissaoCertidaoCivil' => '',
            'siglaUfCertidaoCivil' => '0',
            'cartorioCertidaoCivil' => '',
            'numeroCarteiraTrabalho' => '',
            'serieCarteiraTrabalho' => '',
            'dataEmissaoCarteiraTrabalho' => '',
            'siglaUfCarteiraTrabalho' => '0',
            'numeroTituloEleitor' => '',
            'zonaTituloEleitor' => '',
            'secaoTituloEleitor' => '',
            'orgaoEmissorRg' => '0',
            'certidaoNascimento' => '',
        );
        $this->request->getPost()->set('documento', $documento);
        $this->request->getPost()->set('municipioNascimento', $cepUnicoSalvador->getId());
        $this->request->getPost()->set('paisEstrangeiro', $pais->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		$response = $this->controller->getResponse();
		//	a pagina rediriciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();

		$this->assertEquals(
			'Location: /usuario/fisica', $headers->get('Location')
		);

        //$savedPessoa = $this->em->find('Usuario\Entity\Pessoa', 1);
        $savedFisica = $em->find('Usuario\Entity\Fisica', $fisica->getId());
        $date = new \DateTime('03/05/1982', new \DateTimeZone('America/Sao_Paulo'));        
        $this->assertEquals($date->format('d-m-Y'), $savedFisica->getDataNasc()->format('d-m-Y'));
        $this->assertEquals($cepUnicoSalvador->getId(), $savedFisica->getMunicipioNascimento());
	}

	/**
	 * Testa o hydrator utilizandos as entity fisica e enderecoExterno
	 */
	public function testFisicaHydratorUpdateFormRequestAction()
	{				
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');		

		$tipoLogradouro = $this->buildTipoLogradouro();
		$em->persist($tipoLogradouro);

		$fisica = $this->buildFisica();
		$fisica->setNome('Bill Gates');
		$date = new \DateTime('03/05/1982', new \DateTimeZone('America/Sao_Paulo'));
        $fisica->setDataNasc($date);        		
		

		$enderecoExterno = $this->buildEnderecoExterno();
		$enderecoExterno->setTipoLogradouro($tipoLogradouro);
		$fisica->setEnderecoExterno($enderecoExterno);		
		$em->persist($fisica);

    	$em->flush();
    	
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $fisica->getId());
        $this->routeMatch->setParam('documento', array());
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a resposta
		$response = $this->controller->getResponse();
		$this->assertEquals(200, $response->getStatusCode());

		//	Testa se recebeu um ViewModel
		$this->assertInstanceOf('Zend\View\Model\ViewModel', $result);		
		$variables = $result->getVariables();

		//	Verifica se existe um form
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];

		//	Testa os itens do formulario		
		$id = $form->get('id');
		$cpf = $form->get('cpf');
		$endExterno = $form->get('enderecoExterno');
		$logradouro = $endExterno->get('logradouro');
		$tipoLogradouro = $endExterno->get('tipoLogradouro');
		$this->assertEquals('id', $id->getName());
		$this->assertEquals($fisica->getId(), $id->getValue());
		$this->assertEquals($fisica->getCpf(), $cpf->getValue());		
		$this->assertEquals($enderecoExterno->getLogradouro(), $logradouro->getValue());		
		$this->assertEquals($enderecoExterno->getTipoLogradouro()->getId(), $tipoLogradouro->getValue());
	}

	/**
	 * Tenta salvar com dados invalidos
	 */
	public function testFisicaSaveActionInvalidPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('cpf', '222.222.222-222');
		$this->request->getPost()->set('siglaUf', 'BA');
		$this->request->getPost()->set('cidade', 'Irecê');
		$this->request->getPost()->set('logradouro', 'Rua X');
        $enderecoExterno = array(
            'id' => '0',
            'cep' => '',
            'tipoLogradouro' => '0',
            'logradouro' => '',
            'numero' => '',
            'cidade' => '',
            'bairro' => '',
            'siglaUf' => '',
            'letra' => '',
            'apartamento' =>'',
            'bloco' => '',
            'andar' => '',
            'zonaLocalizacao' => ''
        );

        $this->request->getPost()->set('enderecoExterno', $enderecoExterno);

        $documento = array(
            'id' => '0',
            'rg' => '',
            'dataEmissaoRg' => '',
            'siglaUfEmissaoRg' => '0',
            'tipoCertidaoCivil' => '',
            'termo' => '',
            'livro' => '',
            'folha' => '',
            'dataEmissaoCertidaoCivil' => '',
            'siglaUfCertidaoCivil' => '0',
            'cartorioCertidaoCivil' => '',
            'numeroCarteiraTrabalho' => '',
            'serieCarteiraTrabalho' => '',
            'dataEmissaoCarteiraTrabalho' => '',
            'siglaUfCarteiraTrabalho' => '0',
            'numeroTituloEleitor' => '',
            'zonaTituloEleitor' => '',
            'secaoTituloEleitor' => '',
            'orgaoEmissorRg' => '0',
            'certidaoNascimento' => '',
        );
        $this->request->getPost()->set('documento', $documento);
		
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica se existe um form		
		$variables = $result->getVariables();
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];

		//	testa os erros do formulario
		$cpf = $form->get('cpf');
		$cpfErrors = $cpf->getMessages();		
		$this->assertEquals(
			"The input is more than 11 characters long", $cpfErrors['stringLengthTooLong']
		);
	}

    /**
     * Testa a busca com resultados
     */
    public function testFisicaBuscaPostActionRequest()
    {
        //	cria pessoas fisicas para testar
        $pA = $this->buildFisica();
        $pA->setCpf("111.111.111-11");
        $pB = $this->buildFisica();
        $pB->setNome("GOLD");
        $pB->setCpf("222.222.222-22");
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($pA);
        $em->persist($pB);
        $em->flush();

        //	Invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', 'GOLD');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        //	Verifica o response
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        //	Testa se um ViewModel foi retornado
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);


        //	Testa os dados da View
        $variables = $result->getVariables();

        $this->assertArrayHasKey('dados', $variables);

        //	Faz a comparação dos dados
        $dados = $variables["dados"];
        $this->assertEquals($pB->getNome(), $dados[0]->getNome());
    }
	/**
	 * Testa a exclusao sem passar o id da pessoa
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testFisicaInvalidDeleteAction()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a resposta
		$response = $this->controller->getResponse();
	}


	/**
	 * Testa a exclusao de uma pessoa
	 */
	public function testFisicaDeleteAction()
	{
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
    	$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $fisica->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /usuario/fisica', $headers->get('Location')
		);
	}

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testFisicaInvalidIdDeleteAction()
	{
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
    	$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', 2);

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /usuario/fisica', $headers->get('Location')
		);	
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
		$fisica->setNome('Steve Jobs');
		$fisica->setSituacao('A');
		$fisica->setCpf('11111111111');

    	return $fisica;
	}

	private function buildTipoLogradouro()
	{
		$tipoLogradouro = new tipoLogradouro;
		// RUA	Rua
		$tipoLogradouro->setId('RUA');
		$tipoLogradouro->setDescricao('Rua');

		return $tipoLogradouro;
	}

	private function buildUf()
	{
		$uf = new Uf;
		$uf->setUf('BA');
		$uf->setNome('Bahia');
		$uf->setCep1('44900');
		$uf->setCep2('44905');

		return $uf;
	}

	public function buildRaca()
	{
		$raca = new Raca;
		$raca->setNome('Raca Teste');

		return $raca;
	}


    private function buildEstadoCivil()
    {
        $estadoCivil = new \Usuario\Entity\EstadoCivil();
        $estadoCivil->setDescricao('Solteiro(a)');

        return $estadoCivil;
    }

    private function buildOrgaoEmissorRg()
    {
        $orgaoEmissorRg = new \Usuario\Entity\OrgaoEmissorRg();
        $orgaoEmissorRg->setSigla('SSP');
        $orgaoEmissorRg->setDescricao('SSP');

        return $orgaoEmissorRg;
    }

    private function buildCepUnico()
    {
        $cepUnico = new \Core\Entity\CepUnico();
        $cepUnico->setNome('Irecê');
        $cepUnico->setUf('BA');

        return $cepUnico;
    }

    private function buildPaisEstrangeiro()
    {
        $pais = new \Core\Entity\Pais();
        $pais->setNome('Argetina');

        return $pais;
    }

}