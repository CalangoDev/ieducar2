<?php
use Core\Test\ControllerTestCase;
use Usuario\Controller\ReligiaoController;
use Usuario\Entity\Religiao;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group  Controller
 */
class ReligiaoControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do Controller
	 * @var string ReligiaoController
	 */
	protected $controllerFQDN = 'Usuario\Controller\ReligiaoController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string usuario
	 */
	protected $controllerRoute = 'usuario';

	/**
	 * Testa a pagina inicial que deve mostrar as religioes cadastradas
	 */
	public function testReligiaoIndexAction()
	{
		$religiaoA = $this->buildReligiao();
		$religiaoB = $this->buildReligiao();
		$religiaoB->setNome('Católica');
		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($religiaoA);
		$em->persist($religiaoB);
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
		$this->assertEquals($religiaoA->getNome(), $controllerData[0]->getNome());
		$this->assertEquals($religiaoB->getNome(), $controllerData[1]->getNome());
	}

	/**
	 * Teta a tela de inclusao de um novo registro
	 * @return void 
	 */
	public function testReligiaoSaveActionNewRequest()
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

		// $ativo = $form->get('ativo');
		// $this->assertEquals('ativo', $ativo->getName());var_dump($ativo);
		// $this->assertEquals('select', $ativo->getAttribute('type'));
	}

	/**
	 * Testa a tela de alteracao de um registro
	 */
	public function testReligiaoSaveActionUpdateFormRequest()
	{
		$religiao = $this->buildReligiao();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($religiao);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $religiao->getId());
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
		$this->assertEquals($religiao->getId(), $id->getValue()); 
		$this->assertEquals($religiao->getNome(), $nome->getValue());
	}

	/**
	 * Testa a inclusao de uma nova religiao
	 */
	public function testReligiaoSaveActionPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('nome', 'Protestante');
		$this->request->getPost()->set('ativo', true);

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /usuario/religiao', $headers->get('Location'));
	}

	/**
	 * Testa o update de uma religiao
	 */
	public function testReligiaoUpdateAction()
	{
		$religiao = $this->buildReligiao();		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($religiao);
    	$em->flush();
				
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $religiao->getId());

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $religiao->getId());
		$this->request->getPost()->set('nome', 'Católica');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		$response = $this->controller->getResponse();
		//	a pagina rediriciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();

		$this->assertEquals(
			'Location: /usuario/religiao', $headers->get('Location')
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
		$this->request->getPost()->set('nome', 'Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
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
		$nome = $form->get('nome');
		$nomeErrors = $nome->getMessages();		
		$this->assertEquals(
			"The input is more than 50 characters long", $nomeErrors['stringLengthTooLong']
		);
	}

	/**
	 * Testa a exclusao sem passar o id da religiao
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testReligiaoInvalidDeleteAction()
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
	 * Testa a exclusao de uma religiao
	 */
	public function testReligiaoDeleteAction()
	{
		$religiao = $this->buildReligiao();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($religiao);
    	$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $religiao->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /usuario/religiao', $headers->get('Location')
		);
	}

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testReligiaoInvalidIdDeleteAction()
	{
		$religiao = $this->buildReligiao();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($religiao);
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
			'Location: /usuario/religiao', $headers->get('Location')
		);	
	}

	private function buildReligiao()
	{
		$religiao = new Religiao;
		$religiao->setNome('Protestante');			
		$religiao->setDataCadastro(new \DateTime);		
		$religiao->setAtivo(true);	

		return $religiao;
	}

}