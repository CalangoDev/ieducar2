<?php
use Core\Test\ControllerTestCase;
use Portal\Controller\FuncionarioController;
use Portal\Entity\Funcionario;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group  Controller
 */
class FuncionarioControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do Controller
	 * @var string FuncionarioController
	 */
	protected $controllerFQDN = 'Portal\Controller\FuncionarioController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string portal
	 */
	protected $controllerRoute = 'portal';

	/**
	 * Testa a pagina inicial, que deve mostrar os funcionarios
	 */
	public function testFuncionarioIndexAction()
	{
		//	cria pessoas fisicas para testar
		$pA = $this->buildFisica();	
		$pB = $this->buildFisica();
		$pB->setNome("GOLD");
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($pA);
		$em->persist($pB);

		$funcionarioA = $this->buildFuncionario();
		$funcionarioA->setId($pA);

		$funcionarioB = $this->buildFuncionario();
		$funcionarioB->setId($pB);
		$funcionarioB->setMatricula('gold');
		
		
		$em->persist($funcionarioA);
		$em->persist($funcionarioB);
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
		$this->assertEquals($funcionarioA->getMatricula(), $controllerData[0]->getMatricula());
		$this->assertEquals($funcionarioB->getMatricula(), $controllerData[1]->getMatricula());

	}

	/**
	 * Testa a tela de inclusao de um novo registro
	 * @return void
	 */
	public function testFuncionarioSaveActionNewRequest()
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

		$matricula = $form->get('matricula');
		$this->assertEquals('matricula', $matricula->getName());
		$this->assertEquals('text', $matricula->getAttribute('type'));

		$senha = $form->get('senha');
		$this->assertEquals('senha', $senha->getName());
		$this->assertEquals('password', $senha->getAttribute('type'));

		$ativo = $form->get('ativo');
		// var_dump($ativo);
		$this->assertEquals('ativo', $ativo->getName());
		$this->assertEquals('select', $ativo->getAttribute('type'));
		$this->assertInstanceOf('Zend\Form\Element', $ativo);
		
		$ref_cod_funcionario_vinculo = $form->get('ref_cod_funcionario_vinculo');
		$this->assertEquals('ref_cod_funcionario_vinculo', $ref_cod_funcionario_vinculo->getName());
		$this->assertEquals('select', $ref_cod_funcionario_vinculo->getAttribute('type'));

		$tempo_expira_conta = $form->get('tempo_expira_conta');
		$this->assertEquals('tempo_expira_conta', $tempo_expira_conta->getName());
		$this->assertEquals('select', $tempo_expira_conta->getAttribute('type'));			

		$proibido = $form->get('proibido');
		$this->assertEquals('proibido', $proibido->getName());
		$this->assertEquals('checkbox', $proibido->getAttribute('type'));

		$matricula_permanente = $form->get('matricula_permanente');
		$this->assertEquals('matricula_permanente', $matricula_permanente->getName());
		$this->assertEquals('checkbox', $matricula_permanente->getAttribute('type'));			
		$ref_cod_setor_new = $form->get('ref_cod_setor_new');		
		$this->assertEquals('ref_cod_setor_new', $ref_cod_setor_new->getName());
		$this->assertEquals('select', $ref_cod_setor_new->getAttribute('type'));			
		
	}

	/**
	 * Testa a tela de alteracao de um registro
	 */
	// public function testFisicaSaveActionUpdateFormRequest()
	// {
	// 	$fisica = $this->buildFisica();
	// 	$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
	// 	$em->persist($fisica);
	// 	$em->flush();

	// 	//	Dispara a acao
	// 	$this->routeMatch->setParam('action', 'save');
	// 	$this->routeMatch->setParam('id', $fisica->getId());
	// 	$result = $this->controller->dispatch(
	// 		$this->request, $this->response
	// 	);

	// 	//	Verifica a resposta
	// 	$response = $this->controller->getResponse();
	// 	$this->assertEquals(200, $response->getStatusCode());

	// 	//	Testa se recebeu um ViewModel
	// 	$this->assertInstanceOf('Zend\View\Model\ViewModel', $result);		
	// 	$variables = $result->getVariables();

	// 	//	Verifica se existe um form
	// 	$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
	// 	$form = $variables['form'];

	// 	//	Testa os itens do formulario
	// 	$id = $form->get('id');
	// 	$cpf = $form->get('cpf');
	// 	$this->assertEquals('id', $id->getName());
	// 	$this->assertEquals($fisica->getId(), $id->getValue());
	// 	$this->assertEquals($fisica->getCpf(), $cpf->getValue());
	// }

	/**
	 * Testa a inclusao de uma nova pessoa fisica
	 */
	// public function testFisicaSaveActionPostRequest()
	// {
	// 	//	Dispara a acao
	// 	$this->routeMatch->setParam('action', 'save');

	// 	$this->request->setMethod('post');
	// 	$this->request->getPost()->set('id', '');
	// 	$this->request->getPost()->set('sexo', 'M');
	// 	$this->request->getPost()->set('nome', 'Garrincha');
	// 	$this->request->getPost()->set('url', 'www.eduardojunior.com');		
	// 	$this->request->getPost()->set('email', 'ej@eduardojunior.com');
	// 	$this->request->getPost()->set('situacao', 'A');
	// 	$this->request->getPost()->set('nacionalidade', "1");
		

	// 	$result = $this->controller->dispatch(
	// 		$this->request, $this->response
	// 	);
	// 	//	Verifica a resposta
	// 	$response = $this->controller->getResponse();		
	// 	//	a pagina redireciona, estao o status = 302
	// 	$this->assertEquals(302, $response->getStatusCode());
	// 	$headers = $response->getHeaders();
	// 	$this->assertEquals('Location: /usuario/fisica', $headers->get('Location'));
	// }

	/**
	 * Testa o update de uma pessoa fisica
	 */
	// public function testFisicaUpdateAction()
	// {
	// 	$fisica = $this->buildFisica();
	// 	$fisica->setNome('Bill Gates');
	// 	$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
	// 	$em->persist($fisica);
 //    	$em->flush();
				
	// 	//	Dispara a acao
	// 	$this->routeMatch->setParam('action', 'save');
	// 	$this->routeMatch->setParam('id', $fisica->getId());

	// 	$this->request->setMethod('post');
	// 	$this->request->getPost()->set('id', $fisica->getId());
	// 	$this->request->getPost()->set('nome', 'Alan Turing');
	// 	$this->request->getPost()->set('url', '');
	// 	$this->request->getPost()->set('tipo', 'J');
	// 	$this->request->getPost()->set('email', '');
	// 	$this->request->getPost()->set('situacao', 'I');
	// 	$this->request->getPost()->set('sexo', 'M');
	// 	$this->request->getPost()->set('cpf', '222.222.222-22');


	// 	$result = $this->controller->dispatch(
	// 		$this->request, $this->response
	// 	);

	// 	$response = $this->controller->getResponse();
	// 	//	a pagina rediriciona, entao o status = 302
	// 	$this->assertEquals(302, $response->getStatusCode());
	// 	$headers = $response->getHeaders();

	// 	$this->assertEquals(
	// 		'Location: /usuario/fisica', $headers->get('Location')
	// 	);
	// }

	/**
	 * Tenta salvar com dados invalidos
	 */
	// public function testFisicaSaveActionInvalidPostRequest()
	// {
	// 	//	Dispara a acao
	// 	$this->routeMatch->setParam('action', 'save');

	// 	$this->request->setMethod('post');
	// 	$this->request->getPost()->set('cpf', '222.222.222-222');
		
	// 	$result = $this->controller->dispatch(
	// 		$this->request, $this->response
	// 	);

	// 	//	Verifica se existe um form		
	// 	$variables = $result->getVariables();
	// 	$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
	// 	$form = $variables['form'];

	// 	//	testa os erros do formulario
	// 	$cpf = $form->get('cpf');
	// 	$cpfErrors = $cpf->getMessages();		
	// 	$this->assertEquals(
	// 		"The input is more than 11 characters long", $cpfErrors['stringLengthTooLong']
	// 	);
	// }

	/**
	 * Testa a exclusao sem passar o id da pessoa
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	// public function testFisicaInvalidDeleteAction()
	// {
	// 	//	Dispara a acao
	// 	$this->routeMatch->setParam('action', 'delete');

	// 	$result = $this->controller->dispatch(
	// 		$this->request, $this->response
	// 	);

	// 	//	Verifica a resposta
	// 	$response = $this->controller->getResponse();
	// }


	/**
	 * Testa a exclusao de uma pessoa
	 */
	// public function testFisicaDeleteAction()
	// {
	// 	$fisica = $this->buildFisica();
	// 	$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
	// 	$em->persist($fisica);
 //    	$em->flush();		
		
	// 	//	Dispara a acao
	// 	$this->routeMatch->setParam('action', 'delete');
	// 	$this->routeMatch->setParam('id', $fisica->getId());

	// 	$result = $this->controller->dispatch(
	// 		$this->request, $this->response
	// 	);

	// 	//	Verifica a reposta
	// 	$response = $this->controller->getResponse();

	// 	//	A pagina redireciona, entao o status = 302
	// 	$this->assertEquals(302, $response->getStatusCode());
	// 	$headers = $response->getHeaders();
	// 	$this->assertEquals(
	// 		'Location: /usuario/fisica', $headers->get('Location')
	// 	);
	// }

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	// public function testFisicaInvalidIdDeleteAction()
	// {
	// 	$fisica = $this->buildFisica();
	// 	$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
	// 	$em->persist($fisica);
 //    	$em->flush();		
		
	// 	//	Dispara a acao
	// 	$this->routeMatch->setParam('action', 'delete');
	// 	$this->routeMatch->setParam('id', 2);

	// 	$result = $this->controller->dispatch(
	// 		$this->request, $this->response
	// 	);

	// 	//	Verifica a reposta
	// 	$response = $this->controller->getResponse();

	// 	//	A pagina redireciona, entao o status = 302
	// 	$this->assertEquals(302, $response->getStatusCode());
	// 	$headers = $response->getHeaders();
	// 	$this->assertEquals(
	// 		'Location: /usuario/fisica', $headers->get('Location')
	// 	);	
	// }

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
		$funcionario = new Funcionario;
		$funcionario->setMatricula('admin');
		$funcionario->setSenha('admin');
		$funcionario->setAtivo(1);		

		return $funcionario;
	}

}