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
		$setorB->setSiglaSetor('STY');
		
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
		$controllerData = $variables['dados'];
		$this->assertEquals($setorA->getNome(), $controllerData[0]->getNome());
		$this->assertEquals($setorB->getNome(), $controllerData[1]->getNome());
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

		$nome = $form->get('nome');
		$this->assertEquals('nome', $nome->getName());
		$this->assertEquals('text', $nome->getAttribute('type'));

		$sigla_setor = $form->get('sigla_setor');
		$this->assertEquals('sigla_setor', $sigla_setor->getName());
		$this->assertEquals('text', $sigla_setor->getAttribute('type'));

		$no_paco = $form->get('no_paco');
		$this->assertEquals('no_paco', $no_paco->getName());
		$this->assertEquals('text', $no_paco->getAttribute('type'));

		$endereco = $form->get('endereco');
		$this->assertEquals('endereco', $endereco->getName());
		$this->assertEquals('text', $endereco->getAttribute('type'));

		$tipo = $form->get('tipo');
		$this->assertEquals('tipo', $tipo->getName());
		$this->assertEquals('select', $tipo->getAttribute('type'));

		$secretario = $form->get('secretario');
		$this->assertEquals('secretario', $secretario->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $secretario->getAttribute('type'));

		$ativo = $form->get('ativo');
		$this->assertEquals('ativo', $ativo->getName());
		$this->assertEquals('select', $ativo->getAttribute('type'));

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

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /usuario/setor', $headers->get('Location'));
	}


	private function buildSetor()
	{
		$setor = new Setor;
		$setor->setNome('Setor X');
		$setor->setSiglaSetor('STX');
		$setor->setAtivo(1);
		$setor->setNivel(1);
		$setor->setNoPaco(1);
		$setor->setEndereco('Rua do Setor X');
		$setor->setTipo('s');

		return $setor;
	}
}