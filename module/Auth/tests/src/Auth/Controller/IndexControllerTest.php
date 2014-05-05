<?php
use Core\Test\ControllerTestCase;
use Auth\Controller\IndexController;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group Controller
 */
class IndexControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do controller
	 * @var string FuncionarioController
	 */
	protected $controllerFQDN = 'Auth\Controller\IndexController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string auth
	 */
	protected $controllerRoute = 'auth';

	/**
	 * Testa a tela de autenticacao
	 * @return  void
	 */
	public function testAuthIndexActionNewRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'index');
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
		$matricula = $form->get('matricula');
		$this->assertEquals('matricula', $matricula->getName());
		$this->assertEquals('text', $matricula->getAttribute('type'));

		$senha = $form->get('senha');
		$this->assertEquals('senha', $senha->getName());
		$this->assertEquals('password', $senha->getAttribute('type'));
	}

	/**
	 * Testa a autenticacao 
	 */
	public function testAuthIndexActionPostRequest()
	{
		//	Gravando uma pessoa no banco pre requisito para um funcionario existir
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
		//	Gravando um funcionario
		$funcionario = $this->buildFuncionario();
		// $funcionario->setId($fisica);
		
		$funcionario->setRefCodPessoaFj($fisica);

		$em->persist($funcionario);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'index');

		$this->request->setMethod('post');
		$this->request->getPost()->set('matricula', 'admin');
		$this->request->getPost()->set('senha', 'admin');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a resposta
		$response = $this->controller->getResponse();
		//	a pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /', $headers->get('Location'));

	}

	/**
	 * Testa a autenticacao com dados invalidos
	 */
	public function testAuthInvalidIndexActionPostRequest()
	{
		//	Gravando uma pessoa no banco pre requisito para um funcionario existir
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
		//	Gravando um funcionario
		$funcionario = $this->buildFuncionario();
		// $funcionario->setId($fisica);
		$funcionario->setRefCodPessoaFj($fisica);
		$em->persist($funcionario);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'index');

		$this->request->setMethod('post');
		$this->request->getPost()->set('matricula', 'admin');
		$this->request->getPost()->set('senha', '123456');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a resposta
		$response = $this->controller->getResponse();
		//	a pagina nao redireciona, entao o status = 200 
		$this->assertEquals(200, $response->getStatusCode());
		//	Verificar flashMessages
		$messenger = new \Zend\Mvc\Controller\Plugin\FlashMessenger();
		//	Verifica se existe flashMessages
		$this->assertTrue($messenger->hasMessages());
		//	Get Messages		
		$messages = $messenger->getMessages();
		//	Verifica se a mensagem é uma mensagem de error do tipo Matrícula ou senha inválidos
		$this->assertEquals($messages[0]['error'], "Matrícula ou senha inválidos");
	}

	/**
	 * Test verifica se usuario ta logado
	 */
	public function testAuthLogadoAction()
	{
		//	Gravando uma pessoa no banco pre requisito para um funcionario existir
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
		//	Gravando um funcionario
		$funcionario = $this->buildFuncionario();
		// $funcionario->setId($fisica);
		$funcionario->setRefCodPessoaFj($fisica);
		$em->persist($funcionario);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'index');

		$this->request->setMethod('post');
		$this->request->getPost()->set('matricula', 'admin');
		$this->request->getPost()->set('senha', 'admin');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		

		//	Disparando a acao
		$this->routeMatch->setParam('action', 'logado');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();
		
		//	Verificar flashMessages
		$messenger = new \Zend\Mvc\Controller\Plugin\FlashMessenger();
		//	Verifica se existe flashMessages
		$this->assertTrue($messenger->hasMessages());
		//	Get Messages		
		$messages = $messenger->getMessages();
		//	Verifica se a mensagem é uma mensagem sucesso Você logou com sucesso!
		$this->assertEquals($messages[0]['sucess'], "Você logou com sucesso!");
	}

	/**
	 * Testa o logout  
	 */	
	public function testAuthLogoutAction()
	{
		//	Gravando uma pessoa no banco pre requisito para um funcionario existir
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
		//	Gravando um funcionario
		$funcionario = $this->buildFuncionario();
		// $funcionario->setId($fisica);
		$funcionario->setRefCodPessoaFj($fisica);
		$em->persist($funcionario);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'index');

		$this->request->setMethod('post');
		$this->request->getPost()->set('matricula', 'admin');
		$this->request->getPost()->set('senha', 'admin');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();	
		$this->assertEquals(302, $response->getStatusCode());	


		//	Disparando a acao
		$this->routeMatch->setParam('action', 'logout');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();
		// $this->assertEquals(200, $response->getStatusCode());


		//	Disparando a acao
		$this->routeMatch->setParam('action', 'logado');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();
		
		//	Verificar flashMessages
		$messenger = new \Zend\Mvc\Controller\Plugin\FlashMessenger();
		//	Verifica se existe flashMessages
		$this->assertTrue($messenger->hasMessages());
		//	Get Messages		
		$messages = $messenger->getMessages();
		//	Verifica se a mensagem é uma mensagem error Você não está logado!
		$this->assertEquals($messages[0]['error'], "Você não está logado!");
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
		$fisica->setSituacao('A');
		$fisica->setOrigemGravacao('U');
		$fisica->setOperacao('I');
		$fisica->setIdsisCad(1);
		$fisica->setCpf('111.111.111-11');

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

}