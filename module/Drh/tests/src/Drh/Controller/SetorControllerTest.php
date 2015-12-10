<?php
use Core\Test\ControllerTestCase;
use Drh\Controller\SetorController;
use Drh\Entity\Setor;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group  Controller
 */
class SetorControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do Controller
	 * @var string EscolaridadeController
	 */
	protected $controllerFQDN = 'Drh\Controller\SetorController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string drh
	 */
	protected $controllerRoute = 'drh';

	/**
	 * Testa a pagina inicial que deve mostrar os setores cadastrados
	 */
	public function testSetorIndexAction()
	{
		$setorA = $this->buildSetor();
		$setorB = $this->buildSetor();
		$setorB->setNome('Setor Y');
		$setorB->setSigla('STY');
		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($setorA);
		$em->persist($setorB);
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
        $pagination = $variables['dados'];
        $this->assertEquals($setorA->getNome(), $pagination->getItem(1)->getNome());
        $this->assertEquals($setorB->getNome(), $pagination->getItem(2)->getNome());
	}

	/**
	 * Teta a tela de inclusao de um novo registro
	 * @return void 
	 */
	public function testSetorSaveActionNewRequest()
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

        $parentSetor = $form->get('parentSetor');
        $this->assertEquals('parentSetor', $parentSetor->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $parentSetor->getAttribute('type'));

		$nome = $form->get('nome');
		$this->assertEquals('nome', $nome->getName());
		$this->assertEquals('text', $nome->getAttribute('type'));

		$sigla = $form->get('sigla');
		$this->assertEquals('sigla', $sigla->getName());
		$this->assertEquals('text', $sigla->getAttribute('type'));


		$endereco = $form->get('endereco');
		$this->assertEquals('endereco', $endereco->getName());
		$this->assertEquals('textarea', $endereco->getAttribute('type'));

		$ativo = $form->get('ativo');
		$this->assertEquals('ativo', $ativo->getName());
		$this->assertEquals('Zend\Form\Element\Select', $ativo->getAttribute('type'));

        $nivel = $form->get('nivel');
        $this->assertEquals('nivel', $nivel->getName());
        $this->assertEquals('Zend\Form\Element\Select', $nivel->getAttribute('type'));

	}

	/**
	 * Testa a tela de alteracao de um registro
	 */
	public function testSetorSaveActionUpdateFormRequest()
	{
		$setor = $this->buildSetor();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($setor);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $setor->getId());
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
		$this->assertEquals($setor->getId(), $id->getValue());
		$this->assertEquals($setor->getNome(), $nome->getValue());
	}

	/**
	 * Testa a inclusao de um novo setor
	 */
	public function testSetorSaveActionPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('nome', 'Setor Y');
		$this->request->getPost()->set('sigla', 'STY');
		$this->request->getPost()->set('ativo', 1);
		$this->request->getPost()->set('nivel', 1);
		$this->request->getPost()->set('endereco', 'Rua do setor y');
		$this->request->getPost()->set('tipo', 's');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /drh/setor', $headers->get('Location'));
	}

	/**
	 * Testa o update de um setor
	 */
	public function testSetorUpdateAction()
	{		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		
		$setor = $this->buildSetor();		
		$em->persist($setor);
		$em->flush();
				
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');	
		$this->routeMatch->setParam('id', $setor->getId());

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $setor->getId());
		$this->request->getPost()->set('nome', 'Setor X');
		$this->request->getPost()->set('sigla', 'STY');
		$this->request->getPost()->set('ativo', 1);
		$this->request->getPost()->set('nivel', 1);
		$this->request->getPost()->set('endereco', 'Rua do setor y');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		$response = $this->controller->getResponse();
		//	a pagina rediriciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();

		$this->assertEquals(
			'Location: /drh/setor', $headers->get('Location')
		);
	}

	/**
	 * Tenta salvar com dados invalidos
	 */
	public function testSetorSaveActionInvalidPostRequest()
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
			"The input is more than 255 characters long", $nomeErrors['stringLengthTooLong']
		);
	}

	/**
	 * Testa a exclusao sem passar o id do setor
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testSetorInvalidDeleteAction()
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
	 * Testa a exclusao de um setor
	 */
	public function testSetorDeleteAction()
	{		
		$setor = $this->buildSetor();		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($setor);
    	$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $setor->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /drh/setor', $headers->get('Location')
		);
	}

	/**
	 * Testa a exclusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testFuncionarioInvalidIdDeleteAction()
	{		
		$setor = $this->buildSetor();		
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($setor);
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
			'Location: /drh/setor', $headers->get('Location')
		);	
	}

	private function buildSetor()
	{
		$setor = new Setor;
		$setor->setNome('Setor X');
		$setor->setSigla('STX');
		$setor->setAtivo(1);
		$setor->setNivel(1);
		$setor->setEndereco('Rua do Setor X');

		return $setor;
	}
}