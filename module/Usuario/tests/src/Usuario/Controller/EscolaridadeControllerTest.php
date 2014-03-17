<?php
use Core\Test\ControllerTestCase;
use Usuario\Controller\EscolaridadeController;
use Usuario\Entity\Escolaridade;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group  Controller
 */
class EscolaridadeControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do Controller
	 * @var string EscolaridadeController
	 */
	protected $controllerFQDN = 'Usuario\Controller\EscolaridadeController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string usuario
	 */
	protected $controllerRoute = 'usuario';

	/**
	 * Testa a pagina inicial que deve mostrar as escolaridades cadastradas
	 */
	public function testEscolaridadeIndexAction()
	{
		$escoA = $this->buildEscolaridade();
		$escoB = $this->buildEscolaridade();
		$escoB->setDescricao('NÍVEL V');
		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($escoA);
		$em->persist($escoB);
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

		//	Faz a comparacao dos dados
		$controllerData = $variables['dados'];
		$this->assertEquals($escoA->getDescricao(), $controllerData[0]->getDescricao());
		$this->assertEquals($escoB->getDescricao(), $controllerData[1]->getDescricao());
	}

	/**
	 * Teta a tela de inclusao de um novo registro
	 * @return void 
	 */
	public function testEscolaridadeSaveActionNewRequest()
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

		$descricao = $form->get('descricao');
		$this->assertEquals('descricao', $descricao->getName());
		$this->assertEquals('text', $descricao->getAttribute('type'));
	}

	/**
	 * Testa a tela de alteracao de um regsitro
	 */
	public function testEscolaridadeSaveActionUpdateFormRequest()
	{
		$escolaridade = $this->buildEscolaridade();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($escolaridade);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $escolaridade->getId());
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
		$descricao = $form->get('descricao');
		$this->assertEquals('id', $id->getName());
		$this->assertEquals($escolaridade->getId(), $id->getValue());
		$this->assertEquals($escolaridade->getDescricao(), $descricao->getValue());
	}

	/**
	 * Testa a inclusao de uma nova escolaridade
	 */
	public function testEscolaridadeSaveActionPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('descricao', 'NÍVEL III');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /usuario/escolaridade', $headers->get('Location'));
	}

	/**
	 * Testa o update de uma escolaridade
	 */
	public function testEscolaridadeUpdateAction()
	{
		$escolaridade = $this->buildEscolaridade();		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($escolaridade);
    	$em->flush();
				
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $escolaridade->getId());

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $escolaridade->getId());
		$this->request->getPost()->set('descricao', 'NÍVEL V');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		$response = $this->controller->getResponse();
		//	a pagina rediriciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();

		$this->assertEquals(
			'Location: /usuario/escolaridade', $headers->get('Location')
		);
	}

	/**
	 * Tenta salvar com dados invalidos
	 */
	public function testEscolaridadeSaveActionInvalidPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('descricao', 'Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
		depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum
		girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i 
		pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.');
		
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica se existe um form		
		$variables = $result->getVariables();
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];

		//	testa os erros do formulario
		$descricao = $form->get('descricao');
		$descricaoErrors = $descricao->getMessages();		
		$this->assertEquals(
			"The input is more than 60 characters long", $descricaoErrors['stringLengthTooLong']
		);
	}

	/**
	 * Testa a exclusao sem passar o id da pessoa
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testEscolaridadeInvalidDeleteAction()
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
	 * Testa a exclusao de uma escolaridade
	 */
	public function testEscolaridadeDeleteAction()
	{
		$escolaridade = $this->buildEscolaridade();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($escolaridade);
    	$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $escolaridade->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /usuario/escolaridade', $headers->get('Location')
		);
	}

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testEscolaridadeInvalidIdDeleteAction()
	{
		$escolaridade = $this->buildEscolaridade();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($escolaridade);
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
			'Location: /usuario/escolaridade', $headers->get('Location')
		);	
	}

	private function buildEscolaridade()
	{
		$escolaridade = new Escolaridade;
		$escolaridade->setDescricao('Fundamental Incompleto');

		return $escolaridade;
	}

}