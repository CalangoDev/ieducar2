<?php
use Core\Test\ControllerTestCase;
use Auth\Controller\RoleController;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;
use Auth\Entity\Resource;

/**
 * @group Controller
 */
class ResourceControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do controller
	 * @var string ResourceController
	 */
	protected $controllerFQDN = 'Auth\Controller\ResourceController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string auth
	 */
	protected $controllerRoute = 'auth';

	/**	 
	 * Show resources data save
	 */
	public function testResourceIndexAction()
	{		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');		
		
		$resource = $this->buildResource();
		$em->persist($resource);		
		
		$resourceB = $this->buildResource();
		$resourceB->setNome('Application\Controller\Index.save');
		$em->persist($resourceB);

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
		$this->assertEquals($resource->getNome(), $controllerData[0]->getNome());
		$this->assertEquals($resourceB->getNome(), $controllerData[1]->getNome());

	}

	/**
	 * Testa a tela de um novo resource
	 * 
	 * Test screen of new resource
	 * 
	 * @return  void
	 */
	public function testResourceActionNewRequest()
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

		$nome = $form->get('nome');
		$this->assertEquals('nome', $nome->getName());
		$this->assertEquals('text', $nome->getAttribute('type'));

		$descricao = $form->get('descricao');
		$this->assertEquals('descricao', $descricao->getName());
		$this->assertEquals('textarea', $descricao->getAttribute('type'));

	}

	/**
	 * Testa a tela de alteracao de um registro
	 * 
	 * Test screen update registry
	 */
	public function testResourceSaveActionUpdateFormRequest()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		
		$resource = $this->buildResource();
		$em->persist($resource);		
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $resource->getId());
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
		$this->assertEquals('id', $id->getName());
		$this->assertEquals('hidden', $id->getAttribute('type'));

		$nome = $form->get('nome');
		$this->assertEquals('nome', $nome->getName());
		$this->assertEquals('text', $nome->getAttribute('type'));

		$descricao = $form->get('descricao');
		$this->assertEquals('descricao', $descricao->getName());
		$this->assertEquals('textarea', $descricao->getAttribute('type'));

	}

	/**
	 * Testa a inclusao de um novo resource
	 * 
	 * Test insert of new resource
	 */
	public function testResourceSaveActionPostRequest()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		

		$resource = $this->buildResource();
		// $em->persist($resource);		
		// $em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('nome', $resource->getNome());
		$this->request->getPost()->set('descricao', $resource->getDescricao());			
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /auth/resource', $headers->get('Location'));
	}

	/**
	 * Testa o update
	 * 
	 * Test update
	 */
	public function testResourceUpdateAction()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
			
		$resource = $this->buildResource();
		$em->persist($resource);		
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $resource->getId());
		$this->request->getPost()->set('nome', "Application\Controller\Index.save");
		$this->request->getPost()->set('descricao', $resource->getDescricao());		
		
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /auth/resource', $headers->get('Location'));
	}

	/**
	 * Testa salvar com dados invalidos
	 */
	public function testResourceSaveActionInvalidPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('nome', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');


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
			"The input is more than 120 characters long", $nomeErrors['stringLengthTooLong']
		);		
	}

	/**
	 * Testa a exclusao sem passar o id da regra
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testResourceInvalidDeleteAction()
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
	 * Testa a exclusao de uma regra
	 */
	public function testResourceDeleteAction()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
			
		$resource = $this->buildResource();
		$em->persist($resource);		
		$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $resource->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /auth/resource', $headers->get('Location')
		);
	}

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testResourceInvalidIdDeleteAction()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
			
		$resource = $this->buildResource();
		$em->persist($resource);
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
			'Location: /auth/resource', $headers->get('Location')
		);	
	}
	
	private function buildResource()
	{
		$resource = new Resource;
		$resource->setNome('Application\Entity\Index.index');
		$resource->setDescricao('Tela inicial do sistema');

		return $resource;
	}

}