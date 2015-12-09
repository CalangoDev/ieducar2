<?php
use Core\Test\ControllerTestCase;
use Drh\Controller\FuncionarioController;
use Drh\Entity\Funcionario;
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
	protected $controllerFQDN = 'Drh\Controller\FuncionarioController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string portal
	 */
	protected $controllerRoute = 'drh';

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
		$funcionarioA->setFisica($pA);
		// $funcionarioA->setId($pA);

		$funcionarioB = $this->buildFuncionario();
		// $funcionarioB->setId($pB);
		$funcionarioB->setFisica($pB);
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
		$pagination = $variables['dados'];
		$this->assertEquals($funcionarioA->getMatricula(), $pagination->getItem(1)->getMatricula());
		$this->assertEquals($funcionarioB->getMatricula(), $pagination->getItem(2)->getMatricula());

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

		$fisica = $form->get('fisica');
		$this->assertEquals('fisica', $fisica->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $fisica->getAttribute('type'));

		$matricula = $form->get('matricula');
		$this->assertEquals('matricula', $matricula->getName());
		$this->assertEquals('text', $matricula->getAttribute('type'));

		$senha = $form->get('senha');
		$this->assertEquals('senha', $senha->getName());
		$this->assertEquals('password', $senha->getAttribute('type'));

		$ativo = $form->get('ativo');
		$this->assertEquals('ativo', $ativo->getName());
		$this->assertEquals('Zend\Form\Element\Select', $ativo->getAttribute('type'));
		$this->assertInstanceOf('Zend\Form\Element', $ativo);

		$ramal = $form->get('ramal');
        $this->assertEquals('ramal', $ramal->getName());
        $this->assertEquals('text', $ramal->getAttribute('type'));
		
		$vinculo = $form->get('vinculo');
		$this->assertEquals('vinculo', $vinculo->getName());
		$this->assertEquals('Zend\Form\Element\Select', $vinculo->getAttribute('type'));

		$tempoExpiraConta = $form->get('tempoExpiraConta');
		$this->assertEquals('tempoExpiraConta', $tempoExpiraConta->getName());
		$this->assertEquals('Zend\Form\Element\Select', $tempoExpiraConta->getAttribute('type'));

		$banido = $form->get('banido');
		$this->assertEquals('banido', $banido->getName());
		$this->assertEquals('Zend\Form\Element\Checkbox', $banido->getAttribute('type'));

		$matriculaPermanente = $form->get('matriculaPermanente');
		$this->assertEquals('matriculaPermanente', $matriculaPermanente->getName());
		$this->assertEquals('Zend\Form\Element\Checkbox', $matriculaPermanente->getAttribute('type'));			

		$codigoSetor = $form->get('codigoSetor');
		$this->assertEquals('codigoSetor', $codigoSetor->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $codigoSetor->getAttribute('type'));

        $superAdmin = $form->get('superAdmin');
        $this->assertEquals('superAdmin', $superAdmin->getName());
        $this->assertEquals('Zend\Form\Element\Checkbox', $superAdmin->getAttribute('type'));
		
	}

	/**
	 * Testa a tela de alteracao de um registro
	 */
	public function testFuncionarioSaveActionUpdateFormRequest()
	{
		
		$fisica = $this->buildFisica();

		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		// $funcionario->setId($fisica);				
		$funcionario->setFisica($fisica);
		
		$em->persist($funcionario);
		$em->flush();

		// var_dump($funcionario->getId()->getId());

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $funcionario->getFisica()->getId());
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

		$fisica = $form->get('fisica');
		$this->assertEquals('fisica', $fisica->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $fisica->getAttribute('type'));

		$matricula = $form->get('matricula');
		$this->assertEquals('matricula', $matricula->getName());
		$this->assertEquals('text', $matricula->getAttribute('type'));

		$senha = $form->get('senha');
		$this->assertEquals('senha', $senha->getName());
		$this->assertEquals('password', $senha->getAttribute('type'));

		$ativo = $form->get('ativo');
		$this->assertEquals('ativo', $ativo->getName());
		$this->assertEquals('Zend\Form\Element\Select', $ativo->getAttribute('type'));
		$this->assertInstanceOf('Zend\Form\Element', $ativo);

		$ramal = $form->get('ramal');
		$this->assertEquals('ramal', $ramal->getName());
		$this->assertEquals('text', $ramal->getAttribute('type'));

		$vinculo = $form->get('vinculo');
		$this->assertEquals('vinculo', $vinculo->getName());
		$this->assertEquals('Zend\Form\Element\Select', $vinculo->getAttribute('type'));

		$tempoExpiraConta = $form->get('tempoExpiraConta');
		$this->assertEquals('tempoExpiraConta', $tempoExpiraConta->getName());
		$this->assertEquals('Zend\Form\Element\Select', $tempoExpiraConta->getAttribute('type'));

		$banido = $form->get('banido');
		$this->assertEquals('banido', $banido->getName());
		$this->assertEquals('Zend\Form\Element\Checkbox', $banido->getAttribute('type'));

		$matriculaPermanente = $form->get('matriculaPermanente');
		$this->assertEquals('matriculaPermanente', $matriculaPermanente->getName());
		$this->assertEquals('Zend\Form\Element\Checkbox', $matriculaPermanente->getAttribute('type'));

		$codigoSetor = $form->get('codigoSetor');
		$this->assertEquals('codigoSetor', $codigoSetor->getName());
		$this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $codigoSetor->getAttribute('type'));

		$superAdmin = $form->get('superAdmin');
		$this->assertEquals('superAdmin', $superAdmin->getName());
		$this->assertEquals('Zend\Form\Element\Checkbox', $superAdmin->getAttribute('type'));

	}

	/**
	 * Testa a inclusao de um novo funcionario
	 */
	public function testFuncionarioSaveActionPostRequest()
	{
		//	Gravando uma pessoa no banco pre requisito para um funcionario existir
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
		//	gravando um setor
		$setor = $this->buildSetor();
		$em->persist($setor);

		$em->flush();


		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		// $this->routeMatch->setParam('fisica', $fisica->getId());		

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('fisica', $fisica->getId());
		$this->request->getPost()->set('matricula', 'admin');
		$this->request->getPost()->set('senha', 'admin');
		$this->request->getPost()->set('ativo', true);
		$this->request->getPost()->set('vinculo', 3);
		$this->request->getPost()->set('tempoExpiraConta', 10);
		$this->request->getPost()->set('banido', '0');
		$this->request->getPost()->set('matriculaPermanente', '0');		
		$this->request->getPost()->set('codigoSetor', $setor->getId());
		
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /drh/funcionario', $headers->get('Location'));

	}

	/**
	 * Testa o update de um funcionario
	 */
	public function testFuncionarioUpdateAction()
	{
		//	Gravando uma pessoa no banco pre requisito para um funcionario existir
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
		//	gravando um setor
		$setor = $this->buildSetor();
		$em->persist($setor);

		$funcionario = $this->buildFuncionario();
		$funcionario->setFisica($fisica);
		$em->persist($funcionario);

		
		$em->flush();
				
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		// $this->routeMatch->setParam('fisica', $funcionario->getRefCodPessoaFj()->getId());
		$this->routeMatch->setParam('id', $funcionario->getId());
		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $funcionario->getId());
		$this->request->getPost()->set('fisica', $fisica->getId());
		$this->request->getPost()->set('matricula', 'teste');
		$this->request->getPost()->set('senha', 'admin');
		$this->request->getPost()->set('ativo', true);
		$this->request->getPost()->set('vinculo', 3);
		$this->request->getPost()->set('tempoExpiraConta', 10);
		$this->request->getPost()->set('banido', '0');
		$this->request->getPost()->set('matriculaPermanente', '0');		
		$this->request->getPost()->set('codigoSetor', $setor->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		$response = $this->controller->getResponse();
		//	a pagina rediriciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();

		$this->assertEquals(
			'Location: /drh/funcionario', $headers->get('Location')
		);
	}

	/**
	 * Tenta salvar com dados invalidos
	 */
	public function testFuncionarioSaveActionInvalidPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('matricula', 'Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá,
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
		$matricula = $form->get('matricula');
		$matriculaErrors = $matricula->getMessages();		
		$this->assertEquals(
			"The input is more than 12 characters long", $matriculaErrors['stringLengthTooLong']
		);
	}

	/**
	 * Testa a exclusao sem passar o id da pessoa
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testFuncionarioInvalidDeleteAction()
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
	 * Testa a exclusao de um funcionario
	 */
	public function testFuncionarioDeleteAction()
	{
		//	Gravando uma pessoa no banco pre requisito para um funcionario existir
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
		//	gravando um setor
		$setor = $this->buildSetor();
		$em->persist($setor);
		$em->flush();

		$funcionario = $this->buildFuncionario();
		// $funcionario->setId($fisica);
		$funcionario->setFisica($fisica);
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($funcionario);
    	$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $funcionario->getFisica()->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /drh/funcionario', $headers->get('Location')
		);
	}

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testFuncionarioInvalidIdDeleteAction()
	{
		//	Gravando uma pessoa no banco pre requisito para um funcionario existir
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);

		$funcionario = $this->buildFuncionario();
		// $funcionario->setId($fisica);	
		$funcionario->setFisica($fisica);
		$em->persist($funcionario);
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
			'Location: /drh/funcionario', $headers->get('Location')
		);	
	}


    /**
     * Testa a busca com resultados
     */
    public function testFuncionarioBuscaPostActionRequest()
    {
        //	cria pessoas fisicas para testar
        $pA = $this->buildFisica();
        $pA->setCpf("111.111.111-11");

        $pB = $this->buildFisica();
        $pB->setNome("GOLD");
        $pB->setCpf("222.222.222-22");

        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');

        $funcionarioA = $this->buildFuncionario();
        $funcionarioA->setFisica($pA);

        $funcionarioB = $this->buildFuncionario();
        $funcionarioB->setMatricula('steve');
        $funcionarioB->setFisica($pB);

        $em->persist($funcionarioA);
        $em->persist($funcionarioB);

        $em->flush();

        //	Invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', 'admin');

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
        $dados = $variables["dados"];
        $this->assertEquals('admin', $dados[0]->getMatricula());
    }

	private function buildFisica()
	{	
    	/**
    	 * Dados fisica
    	 */    	
		$fisica = new \Usuario\Entity\Fisica;		
		$fisica->setSexo("M");
		$fisica->setNome('Steve Jobs');
		$fisica->setSituacao('A');
		$fisica->setCpf('111.111.111-11');

    	return $fisica;
	}


	private function buildSetor()
	{
		/**
		 * Dados setor
		 */
		$setor = new \Drh\Entity\Setor;
		$setor->setNome('Prefeitura Municipal de CalangoDev');
		$setor->setSiglaSetor('PMC');
		$setor->setTipo('s');

		return $setor;
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