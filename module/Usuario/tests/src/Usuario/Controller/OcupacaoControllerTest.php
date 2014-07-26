<?php
use Core\Test\ControllerTestCase;
use Usuario\Controller\OcupacaoController;
use Usuario\Entity\Ocupacao;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group  Controller
 */
class OcupacaoControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do Controller
	 * @var string OcupacaoController
	 */
	protected $controllerFQDN = 'Usuario\Controller\OcupacaoController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string usuario
	 */
	protected $controllerRoute = 'usuario';

	/**
	 * Testa a pagina inicial que deve mostrar as ocupacoes
	 */
	public function testOcupacaoIndexAction()
	{
		$ocupacaoA = $this->buildOcupacao();
		$ocupacaoB = $this->buildOcupacao();
		$ocupacaoB->setDescricao('Ocupação 2');
		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($ocupacaoA);
		$em->persist($ocupacaoB);
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
		$this->assertEquals($ocupacaoA->getDescricao(), $controllerData[0]->getDescricao());
		$this->assertEquals($ocupacaoB->getDescricao(), $controllerData[1]->getDescricao());
	}

	/**
	 * Teta a tela de inclusao de um novo registro
	 * @return void 
	 */
	public function testOcupacaoSaveActionNewRequest()
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
	public function testOcupacaoSaveActionUpdateFormRequest()
	{
		$ocupacao = $this->buildOcupacao();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($ocupacao);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $ocupacao->getId());
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
		$this->assertEquals($ocupacao->getId(), $id->getValue());
		$this->assertEquals($ocupacao->getDescricao(), $descricao->getValue());
	}

	/**
	 * Testa a inclusao de uma nova Ocupação
	 */
	public function testOcupacaoSaveActionPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('descricao', 'Divorciado(a)');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /usuario/ocupacao', $headers->get('Location'));
	}

	/**
	 * Testa o update de uma ocupacao
	 */
	public function testOcupacaoUpdateAction()
	{
		$ocupacao = $this->buildOcupacao();		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($ocupacao);
    	$em->flush();
				
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $ocupacao->getId());

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $ocupacao->getId());
		$this->request->getPost()->set('descricao', 'Ocupação 1');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		$response = $this->controller->getResponse();
		//	a pagina rediriciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();

		$this->assertEquals(
			'Location: /usuario/ocupacao', $headers->get('Location')
		);
	}

	/**
	 * Tenta salvar com dados invalidos
	 */
	public function testOcupacaoSaveActionInvalidPostRequest()
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
	public function testOcupacaoInvalidDeleteAction()
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
	 * Testa a exclusao de uma ocupacao
	 */
	public function testOcupacaoDeleteAction()
	{
		$ocupacao = $this->buildOcupacao();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($ocupacao);
    	$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $ocupacao->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /usuario/ocupacao', $headers->get('Location')
		);
	}

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testOcupacaoInvalidIdDeleteAction()
	{
		$ocupacao = $this->buildOcupacao();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($ocupacao);
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
			'Location: /usuario/ocupacao', $headers->get('Location')
		);	
	}

	private function buildOcupacao()
	{
		$ocupacao = new Ocupacao;
		$ocupacao->setDescricao('Ocupacao 1');

		return $ocupacao;
	}

}