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

		$nomePai = $form->get('nomePai');
		$this->assertEquals('nomePai', $nomePai->getName());
		$this->assertEquals('text', $nomePai->getAttribute('type'));

		$nomeConjuge = $form->get('nomeConjuge');
		$this->assertEquals('nomeConjuge', $nomeConjuge->getName());
		$this->assertEquals('text', $nomeConjuge->getAttribute('type'));

		$nomeResponsavel = $form->get('nomeResponsavel');
		$this->assertEquals('nomeResponsavel', $nomeResponsavel->getName());
		$this->assertEquals('text', $nomeResponsavel->getAttribute('type'));

		$justificativaProvisorio = $form->get('justificativaProvisorio');
		$this->assertEquals('justificativaProvisorio', $justificativaProvisorio->getName());
		$this->assertEquals('text', $justificativaProvisorio->getAttribute('type'));

		$cpf = $form->get('cpf');
		$this->assertEquals('cpf', $cpf->getName());
		$this->assertEquals('text', $cpf->getAttribute('type'));

		$idmunNascimento = $form->get('idmunNascimento');
		$this->assertEquals('idmunNascimento', $idmunNascimento->getName());
		$this->assertEquals('Zend\Form\Element\Select', $idmunNascimento->getAttribute('type'));

		$idpaisEstrangeiro = $form->get('idpaisEstrangeiro');
		$this->assertEquals('idpaisEstrangeiro', $idpaisEstrangeiro->getName());
		$this->assertEquals('Zend\Form\Element\Select', $idpaisEstrangeiro->getAttribute('type'));

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

		$dddTelefone1 = $form->get('dddTelefone1');
		$this->assertEquals('dddTelefone1', $dddTelefone1->getName());
		$this->assertEquals('text', $dddTelefone1->getAttribute('type'));

		$telefone1 = $form->get('telefone1');
		$this->assertEquals('telefone1', $telefone1->getName());
		$this->assertEquals('text', $telefone1->getAttribute('type'));

		$dddTelefone2 = $form->get('dddTelefone2');
		$this->assertEquals('dddTelefone2', $dddTelefone2->getName());
		$this->assertEquals('text', $dddTelefone2->getAttribute('type'));

		$telefone2 = $form->get('telefone2');
		$this->assertEquals('telefone2', $telefone2->getName());
		$this->assertEquals('text', $telefone2->getAttribute('type'));

		$dddCelular = $form->get('dddCelular');
		$this->assertEquals('dddCelular', $dddCelular->getName());
		$this->assertEquals('text', $dddCelular->getAttribute('type'));

		$celular = $form->get('celular');
		$this->assertEquals('celular', $celular->getName());
		$this->assertEquals('text', $celular->getAttribute('type'));

		$dddFax = $form->get('dddFax');
		$this->assertEquals('dddFax', $dddFax->getName());
		$this->assertEquals('text', $dddFax->getAttribute('type'));

		$fax = $form->get('fax');
		$this->assertEquals('fax', $fax->getName());
		$this->assertEquals('text', $fax->getAttribute('type'));

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
		
		$em->flush();		

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('situacao', 'A');
		$this->request->getPost()->set('nome', 'Garrincha');
		$this->request->getPost()->set('sexo', 'M');
		$this->request->getPost()->set('cep', '44900-000');		
		// $this->request->getPost()->set('url', 'www.eduardojunior.com');		
		// $this->request->getPost()->set('email', 'ej@eduardojunior.com');
		// $this->request->getPost()->set('situacao', 'A');
		// $this->request->getPost()->set('nacionalidade', "1");
		// $this->request->getPost()->set('raca', $raca->getId());
		// $this->request->getPost()->set('cpf', '');
		// $this->request->getPost()->set('tipoLogradouro', $tipoLogradouro->getId());		
		// $this->request->getPost()->set('siglaUf', $uf->getId());
		// $this->request->getPost()->set('apartamento', '001');
		// $this->request->getPost()->set('bloco', 'A');
		// $this->request->getPost()->set('andar', '1');
		// $this->request->getPost()->set('logradouro', 'Rua X');
		// $this->request->getPost()->set('cidade', 'Irecê');
		// $this->request->getPost()->set('zonaLocalizacao', '1');
		// $this->request->getPost()->set('dddTelefone1', '71');
		// $this->request->getPost()->set('telefone1', '1111-1111');
		// $this->request->getPost()->set('dddTelefone2', '71');
		// $this->request->getPost()->set('telefone2', '1111-1111');
		// $this->request->getPost()->set('dddCelular', '71');
		// $this->request->getPost()->set('celular', '1111-1111');
		// $this->request->getPost()->set('dddFax', '71');
		// $this->request->getPost()->set('fax', '1111-1111');
		// $this->request->getPost()->set('cep', '44900-000');

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
        // Parametros requiridos que vao ser passados em branco
        $this->request->getPost()->set('sexo', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('tipoLogradouro', '');
        $this->request->getPost()->set('siglaUf', '');       		
		$this->request->getPost()->set('zonaLocalizacao', '');
		$this->request->getPost()->set('cep', '');
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
        // var_dump($result->getVariables()['form']->getMessages());		
        $this->assertEquals('Value is required and can\'t be empty', $msgs["nome"]['isEmpty']);
        $this->assertEquals('Value is required and can\'t be empty', $msgs["sexo"]['isEmpty']);        
        $this->assertEquals('Value is required and can\'t be empty', $msgs["enderecoExterno"]["zonaLocalizacao"]['isEmpty']);
        $this->assertEquals('Value is required and can\'t be empty', $msgs["enderecoExterno"]["tipoLogradouro"]['isEmpty']);
        $this->assertEquals('Value is required and can\'t be empty', $msgs["enderecoExterno"]["siglaUf"]['isEmpty']);
        $this->assertEquals('Value is required and can\'t be empty', $msgs["enderecoExterno"]["cep"]['isEmpty']);

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
		

		$fisica = $this->buildFisica();
		$fisica->setNome('Bill Gates');
        $date = new \DateTime('03/05/1982', new \DateTimeZone('America/Sao_Paulo'));
        $fisica->setDataNasc($date);
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
		// $enderecoExterno->setDataRev();
		$enderecoExterno->setOperacao("I");
		$enderecoExterno->setOrigemGravacao("U");
		$enderecoExterno->setIdsisCad(1);
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
		$fisica->setOrigemGravacao("M");
		$fisica->setOperacao("I");
		$fisica->setIdsisCad(1);
		$fisica->setNome('Steve Jobs');
		$fisica->setSituacao('A');
		$fisica->setOrigemGravacao('U');
		$fisica->setOperacao('I');
		$fisica->setIdsisCad(1);
		$fisica->setCpf('111.111.111-11');

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
		$raca->setNome('Branca');

		return $raca;
	}

}