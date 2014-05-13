<?php
use Core\Test\ControllerTestCase;
use Auth\Controller\RoleController;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;
use Auth\Entity\Role;
use Auth\Entity\Resource;
use Usuario\Entity\Fisica;

/**
 * @group Controller
 */
class RoleControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do controller
	 * @var string FuncionarioController
	 */
	protected $controllerFQDN = 'Auth\Controller\RoleController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string auth
	 */
	protected $controllerRoute = 'auth';

	/**
	 * Testa a pagina inicial, que deve mostrar as regras
	 */
	public function testRoleIndexAction()
	{		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
	
		$fisica = $this->buildFisica();				
		$em->persist($fisica);
		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$em->persist($funcionario);

		$fisicadeny = $this->buildFisica();		
		$em->persist($fisicadeny);
		$funcionariodeny = $this->buildFuncionario();
		$funcionariodeny->setRefCodPessoaFj($fisicadeny);
		$em->persist($funcionariodeny);
		
		$resource = $this->buildResource();
		$em->persist($resource);
		
		$role = $this->buildRole();
		$role->setFuncionario($funcionario);
		$role->setResource($resource);
		$em->persist($role);

		$roledeny = $this->buildRole();
		$roledeny->setPrivilegio(1);
		$roledeny->setFuncionario($funcionariodeny);
		$roledeny->setResource($resource);
		$em->persist($roledeny);
		
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
		$this->assertEquals($role->getResource(), $controllerData[0]->getResource());
		$this->assertEquals($roledeny->getResource(), $controllerData[1]->getResource());

	}

	/**
	 * Testa a tela de nova regra
	 * @return  void
	 */
	public function testRoleActionNewRequest()
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

		$funcionario = $form->get('funcionario');
		$this->assertEquals('funcionario', $funcionario->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $funcionario->getAttribute('type'));

		$resource = $form->get('resource');
		$this->assertEquals('resource', $resource->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $resource->getAttribute('type'));

		$privilegio = $form->get('privilegio');
		$this->assertEquals('privilegio', $privilegio->getName());
		$this->assertEquals('Zend\Form\Element\Select', $privilegio->getAttribute('type'));
	}

	/**
	 * Testa a tela de alteracao de um registro
	 */
	public function testRoleSaveActionUpdateFormRequest()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
	
		$fisica = $this->buildFisica();				
		$em->persist($fisica);
		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$em->persist($funcionario);

		$resource = $this->buildResource();
		$em->persist($resource);
		
		$role = $this->buildRole();
		$role->setFuncionario($funcionario);
		$role->setResource($resource);
		$em->persist($role);		
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $role->getId());
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

		$funcionario = $form->get('funcionario');
		$this->assertEquals('funcionario', $funcionario->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $funcionario->getAttribute('type'));

		$resource = $form->get('resource');
		$this->assertEquals('resource', $resource->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $resource->getAttribute('type'));

		$privilegio = $form->get('privilegio');
		$this->assertEquals('privilegio', $privilegio->getName());
		$this->assertEquals('Zend\Form\Element\Select', $privilegio->getAttribute('type'));
	}

	/**
	 * Testa a inclusao de uma nova regra
	 */
	public function testRoleSaveActionPostRequest()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
	
		$fisica = $this->buildFisica();				
		$em->persist($fisica);
		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$em->persist($funcionario);

		$resource = $this->buildResource();
		$em->persist($resource);
		
		$role = $this->buildRole();
		$role->setFuncionario($funcionario);
		$role->setResource($resource);
		$em->persist($role);		
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('funcionario', $fisica->getId());
		$this->request->getPost()->set('resource', $resource->getId());
		$this->request->getPost()->set('privilegio', 0);
		
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /auth/role', $headers->get('Location'));
	}

	/**
	 * Testa o update de uma regra
	 */
	public function testRoleUpdateAction()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
	
		$fisica = $this->buildFisica();				
		$em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$em->persist($funcionario);

		$resource = $this->buildResource();
		$em->persist($resource);
		
		$role = $this->buildRole();
		$role->setFuncionario($funcionario);
		$role->setResource($resource);
		$em->persist($role);		
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $role->getId());
		$this->request->getPost()->set('funcionario', $fisica->getId());
		$this->request->getPost()->set('resource', $resource->getId());
		$this->request->getPost()->set('privilegio', 1);
		
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /auth/role', $headers->get('Location'));
	}

	/**
	 * Testa salvar com dados invalidos
	 */
	public function testRoleSaveActionInvalidPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('privilegio', 3);


		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica se existe um form		
		$variables = $result->getVariables();
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];

		//	testa os erros do formulario
		$funcionario = $form->get('funcionario');
		$funcionarioErrors = $funcionario->getMessages();

		$this->assertEquals(
			"Value is required and can't be empty", $funcionarioErrors['isEmpty']
		);

		$resource = $form->get('resource');
		$resourceErrors = $resource->getMessages();

		$this->assertEquals(
			"Value is required and can't be empty", $resourceErrors['isEmpty']
		);

		$privilegio = $form->get('privilegio');
		$privilegioErrors = $privilegio->getMessages();		
		$this->assertEquals(
			"The input was not found in the haystack", $privilegioErrors['notInArray']
		);
	}

	/**
	 * Testa a exclusao sem passar o id da regra
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testRoleInvalidDeleteAction()
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
	public function testRoleDeleteAction()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
	
		$fisica = $this->buildFisica();				
		$em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$em->persist($funcionario);

		$resource = $this->buildResource();
		$em->persist($resource);
		
		$role = $this->buildRole();
		$role->setFuncionario($funcionario);
		$role->setResource($resource);
		$em->persist($role);		
		$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $role->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /auth/role', $headers->get('Location')
		);
	}

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testRoleInvalidIdDeleteAction()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
	
		$fisica = $this->buildFisica();				
		$em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		$funcionario->setRefCodPessoaFj($fisica);
		$this->em->persist($funcionario);

		$resource = $this->buildResource();
		$em->persist($resource);
		
		$role = $this->buildRole();
		$role->setFuncionario($funcionario);
		$role->setResource($resource);
		$em->persist($role);		
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
			'Location: /auth/role', $headers->get('Location')
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
		$fisica->setNome("Steve Jobs");
		$fisica->setTipo("F");
		$fisica->setSituacao("A");

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
	
	private function buildResource()
	{
		$resource = new Resource;
		$resource->setNome('Application\Entity\Index.index');
		$resource->setDescricao('Tela inicial do sistema');

		return $resource;
	}

	private function buildRole()
	{
		$role = new Role;
		$role->setPrivilegio(0);
    	
    	return $role;
	}

}