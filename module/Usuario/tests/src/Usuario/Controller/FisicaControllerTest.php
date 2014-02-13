<?php

use Core\Test\ControllerTestCase;
use Usuario\Controller\FisicaController;
use Usuario\Entity\Fisica;
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
	 * @var string PessoaController
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
		$controllerData = $variables['dados'];
		$this->assertEquals($pA->getNome(), $controllerData[0]->getNome());
		$this->assertEquals($pB->getNome(), $controllerData[1]->getNome());

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

		$data_nasc = $form->get('data_nasc');
		$this->assertEquals('data_nasc', $data_nasc->getName());
		$this->assertEquals('text', $data_nasc->getAttribute('type'));

		$sexo = $form->get('sexo');
		$this->assertEquals('sexo', $sexo->getName());
		$this->assertEquals('Zend\Form\Element\Select', $sexo->getAttribute('type'));

		$data_uniao = $form->get('data_uniao');
		$this->assertEquals('data_uniao', $data_uniao->getName());
		$this->assertEquals('text', $data_uniao->getAttribute('type'));

		$nacionalidade = $form->get('nacionalidade');
		$this->assertEquals('nacionalidade', $nacionalidade->getName());
		$this->assertEquals('Zend\Form\Element\Select', $nacionalidade->getAttribute('type'));

		$data_chegada_brasil = $form->get('data_chegada_brasil');
		$this->assertEquals('data_chegada_brasil', $data_chegada_brasil->getName());
		$this->assertEquals('text', $data_chegada_brasil->getAttribute('type'));

		$ultima_empresa = $form->get('ultima_empresa');
		$this->assertEquals('ultima_empresa', $ultima_empresa->getName());
		$this->assertEquals('text', $ultima_empresa->getAttribute('type'));

		$nome_mae = $form->get('nome_mae');
		$this->assertEquals('nome_mae', $nome_mae->getName());
		$this->assertEquals('text', $nome_mae->getAttribute('type'));

		$nome_pai = $form->get('nome_pai');
		$this->assertEquals('nome_pai', $nome_pai->getName());
		$this->assertEquals('text', $nome_pai->getAttribute('type'));

		$nome_conjuge = $form->get('nome_conjuge');
		$this->assertEquals('nome_conjuge', $nome_conjuge->getName());
		$this->assertEquals('text', $nome_conjuge->getAttribute('type'));

		$nome_responsavel = $form->get('nome_responsavel');
		$this->assertEquals('nome_responsavel', $nome_responsavel->getName());
		$this->assertEquals('text', $nome_responsavel->getAttribute('type'));

		$justificativa_provisorio = $form->get('justificativa_provisorio');
		$this->assertEquals('justificativa_provisorio', $justificativa_provisorio->getName());
		$this->assertEquals('text', $justificativa_provisorio->getAttribute('type'));

		$cpf = $form->get('cpf');
		$this->assertEquals('cpf', $cpf->getName());
		$this->assertEquals('text', $cpf->getAttribute('type'));

		$idmun_nascimento = $form->get('idmun_nascimento');
		$this->assertEquals('idmun_nascimento', $idmun_nascimento->getName());
		$this->assertEquals('Zend\Form\Element\Select', $idmun_nascimento->getAttribute('type'));

		$idpais_estrangeiro = $form->get('idpais_estrangeiro');
		$this->assertEquals('idpais_estrangeiro', $idpais_estrangeiro->getName());
		$this->assertEquals('Zend\Form\Element\Select', $idpais_estrangeiro->getAttribute('type'));

		$idesco = $form->get('idesco');
		$this->assertEquals('idesco', $idesco->getName());
		$this->assertEquals('Zend\Form\Element\Select', $idesco->getAttribute('type'));

		$ideciv = $form->get('ideciv');
		$this->assertEquals('ideciv', $ideciv->getName());
		$this->assertEquals('Zend\Form\Element\Select', $ideciv->getAttribute('type'));

		$idocup = $form->get('idocup');
		$this->assertEquals('idocup', $idocup->getName());
		$this->assertEquals('text', $idocup->getAttribute('type'));

		$ref_cod_religiao = $form->get('ref_cod_religiao');
		$this->assertEquals('ref_cod_religiao', $ref_cod_religiao->getName());
		$this->assertEquals('Zend\Form\Element\Select', $ref_cod_religiao->getAttribute('type'));
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
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('sexo', 'M');
		$this->request->getPost()->set('nome', 'Garrincha');
		$this->request->getPost()->set('url', 'www.eduardojunior.com');		
		$this->request->getPost()->set('email', 'ej@eduardojunior.com');
		$this->request->getPost()->set('situacao', 'A');
		$this->request->getPost()->set('nacionalidade', "1");
		

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /usuario/fisica', $headers->get('Location'));
	}

	/**
	 * Testa o update de uma pessoa fisica
	 */
	public function testFisicaUpdateAction()
	{
		$fisica = $this->buildFisica();
		$fisica->setNome('Bill Gates');
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

}