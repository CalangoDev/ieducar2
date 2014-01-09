<?php

use Core\Test\ControllerTestCase;
use Usuario\Controller\PessoaController;
use Usuario\Entity\Pessoa;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;


/**
 * @group Controller
 */

class PessoaControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do Controller
	 * @var string PessoaController
	 */
	protected $controllerFQDN = 'Usuario\Controller\PessoaController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string usuario
	 */
	protected $controllerRoute = 'usuario';

	/**
	 * Testa a pagina inicial, que deve mostrar as pessoas
	 */
	public function testPessoaIndexAction()
	{
		//	Cria pessoas para testar		
		$pessoaA = $this->pessoa();
		$this->addPessoa($pessoaA);
		$pessoaB = $this->pessoa();
		$pessoaB->nome = "GOLD";
		$this->addPessoa($pessoaB);

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

		$this->assertArrayHasKey('pessoas', $variables);

		//	Faz a comparação dos dados
		$controllerData = $variables['pessoas'];
		$this->assertEquals($pessoaA->nome, $controllerData[0]->nome);
		$this->assertEquals($pessoaB->nome, $controllerData[1]->nome);

	}

	/**
	 * Testa a tela de inclusao de um novo registro
	 * @return void
	 */
	public function testPessoaSaveActionNewRequest()
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

	}

	/**
	 * Testa a tela de alteracao de um registro
	 */
	public function testPessoaSaveActionUpdateFormRequest()
	{
		$pessoaA = $this->pessoa();
		$this->addPessoa($pessoaA);

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $pessoaA->id);
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
		$nome = $form->get('nome');
		$this->assertEquals('id', $id->getName());
		$this->assertEquals($pessoaA->id, $id->getValue());
		$this->assertEquals($pessoaA->nome, $nome->getValue());		
	}

	/**
	 * Testa a inclusao de uma nova pessoa
	 */
	public function testPessoaSaveActionPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('nome', 'Garrincha');
		$this->request->getPost()->set('url', 'www.eduardojunior.com');
		$this->request->getPost()->set('tipo', 'F');
		$this->request->getPost()->set('email', 'ej@eduardojunior.com');
		$this->request->getPost()->set('situacao', 'A');		

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /usuario/pessoa', $headers->get('Location'));
	}

	/**
	 * Testa o update de uma pessoa
	 */
	public function testPessoaUpdateAction()
	{
		$pessoa = $this->pessoa();
		$this->addPessoa($pessoa);

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $pessoa->id);
		$this->request->getPost()->set('nome', 'Alan Turing');
		$this->request->getPost()->set('url', '');
		$this->request->getPost()->set('tipo', 'J');
		$this->request->getPost()->set('email', '');
		$this->request->getPost()->set('situacao', 'I');


		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		$response = $this->controller->getResponse();
		//	a pagina rediriciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();

		$this->assertEquals(
			'Location: /usuario/pessoa', $headers->get('Location')
		);
	}

	/**
	 * Tenta salvar com dados invalidos
	 */
	public function testPessoaSaveActionInvalidPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('nome', '');
		
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica se existe um form		
		$variables = $result->getVariables();
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];

		//	testa os erros do formulario
		$nome = $form->get('nome');
		$nomeErrors = $nome->getMessages();
		$this->assertEquals(
			"Value is required and can't be empty", $nomeErrors['isEmpty']
		);
	}

	/**
	 * Testa a exclusao sem passar o id da pessoa
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testPessoaInvalidDeleteAction()
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
	public function testPessoaDeleteAction()
	{
		$pessoa = $this->pessoa();
		$this->addPessoa($pessoa);
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $pessoa->id);

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /usuario/pessoa', $headers->get('Location')
		);
	}

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testPessoaInvalidIdDeleteAction()
	{
		$pessoa = $this->pessoa();
		$this->addPessoa($pessoa);
		
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
			'Location: /usuario/pessoa', $headers->get('Location')
		);	
	}

	private function addPessoa($pessoa)
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($pessoa);
    	$em->flush();    	
	}

	private function pessoa()
	{
		$pessoa = new Pessoa();
		$pessoa->nome = "Steve Jobs";
    	$pessoa->tipo = "F";
    	$pessoa->situacao = "A";
    	$pessoa->origem_gravacao = "M";
    	$pessoa->operacao = "I";
    	$pessoa->idsis_cad = 1;
    	$pessoa->idpes_cad = 1;
    	$pessoa->idpes_rev = 1;

    	return $pessoa;
	}
}