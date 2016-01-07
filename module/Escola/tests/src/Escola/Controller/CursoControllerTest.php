<?php
/**
 * Created by Vim.
 * User: eduardojunior
 * Date: 02/01/16
 * Time: 17:12
 */
use Escola\Entity\Curso;


/**
 * @group Controller
 */
class CursoControllerTest extends \Core\Test\ControllerTestCase
{
	/**
	 * Namespace completa do controller
	 * @var string CursoController
	 */
	protected $controllerFQDN = 'Escola\Controller\CursoController';

	/**
	 * Nome da rota, geralmente o nome do modulo
	 * @var string escola
	 */
	protected $controllerRoute = 'escola';

	/**
	 * testa a pagina inicial, listando as habilitacoes
	 */
	public function testCursoIndexAction()
	{
		$cursoA = $this->buildCurso();
		$cursoB = $this->buildCurso();
		$cursoB->setNome('Nome b');

		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($cursoA);
		$em->persist($cursoB);
		$em->flush();

		// invoca a rota index
		$this->routeMatch->setParam('action', 'index');
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		// verifica o response
		$response = $this->controller->getResponse();
		$this->assertEquals(200, $response->getStatusCode());

		// testa se um ViewModel foi retornado
		$this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

		// testa os dados da View
		$variables = $result->getVariables();
		$this->assertArrayHasKey('dados', $variables);

		// faz a comparacao dos dados
		$paginator = $variables['dados'];
		$this->assertEquals($cursoA->getNome(), $paginator->getItem(1)->getNome());
		$this->assertEquals($cursoB->getNome(), $paginator->getItem(2)->getNome());

	}

    /**
     * Testa a tela de inclusao de um novo registro
     *
     * @return void
     */
    public function testCursoSaveActionNewRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // testa se recebeu um ViewModel
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        // verifica se existe um form
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        /// testa os itens do formulario
        $id = $form->get('id');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals('hidden', $id->getAttribute('type'));

        $nome = $form->get('nome');
        $this->assertEquals('nome', $nome->getName());
        $this->assertEquals('text', $nome->getAttribute('type'));

        $sigla = $form->get('sigla');
        $this->assertEquals('sigla', $sigla->getName());
        $this->assertEquals('text', $sigla->getAttribute('type'));

        $quantidadeEtapa = $form->get('quantidadeEtapa');
        $this->assertEquals('quantidadeEtapa', $quantidadeEtapa->getName());
        $this->assertEquals('text', $quantidadeEtapa->getAttribute('type'));

        $horaFalta = $form->get('horaFalta');
        $this->assertEquals('horaFalta', $horaFalta->getName());
        $this->assertEquals('text', $horaFalta->getAttribute('type'));

        $cargaHoraria = $form->get('cargaHoraria');
        $this->assertEquals('cargaHoraria', $cargaHoraria->getName());
        $this->assertEquals('text', $cargaHoraria->getAttribute('type'));

        $atoPoderPublico = $form->get('atoPoderPublico');
        $this->assertEquals('atoPoderPublico', $atoPoderPublico->getName());
        $this->assertEquals('text', $atoPoderPublico->getAttribute('type'));

        $habilitacoes = $form->get('habilitacoes');
        $this->assertEquals('habilitacoes', $habilitacoes->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $habilitacoes->getAttribute('type'));

        $objetivo = $form->get('objetivo');
        $this->assertEquals('objetivo', $objetivo->getName());
        $this->assertEquals('textarea', $objetivo->getAttribute('type'));

        $publicoAlvo = $form->get('publicoAlvo');
        $this->assertEquals('publicoAlvo', $publicoAlvo->getName());
        $this->assertEquals('textarea', $publicoAlvo->getAttribute('type'));

        $instituicao = $form->get('instituicao');
        $this->assertEquals('instituicao', $instituicao->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $instituicao->getAttribute('type'));

        $nivelEnsino = $form->get('nivelEnsino');
        $this->assertEquals('nivelEnsino', $nivelEnsino->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $nivelEnsino->getAttribute('type'));

        $tipoEnsino = $form->get('tipoEnsino');
        $this->assertEquals('tipoEnsino', $tipoEnsino->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $tipoEnsino->getAttribute('type'));

        $tipoRegime = $form->get('tipoRegime');
        $this->assertEquals('tipoRegime', $tipoRegime->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $tipoRegime->getAttribute('type'));

        $ativo = $form->get('ativo');
        $this->assertEquals('ativo', $ativo->getName());
        $this->assertEquals('Zend\Form\Element\Select', $ativo->getAttribute('type'));

        $padraoAnoEscolar = $form->get('padraoAnoEscolar');
        $this->assertEquals('padraoAnoEscolar', $padraoAnoEscolar->getName());
        $this->assertEquals('Zend\Form\Element\Checkbox', $padraoAnoEscolar->getAttribute('type'));

        $multiSeriado = $form->get('multiSeriado');
        $this->assertEquals('multiSeriado', $multiSeriado->getName());
        $this->assertEquals('Zend\Form\Element\Checkbox', $multiSeriado->getAttribute('type'));

    }

    /**
     * testa a tela de alteracoes de um registro
     */
    public function testCursoUpdateControllerTest()
    {
        $curso = $this->buildCurso();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($curso);
        $em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $curso->getId());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // testa se recebeu um ViewModel
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();

        // verifica se existe um form
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];

        // testa os itens do formulario
        $id = $form->get('id');
        $nome = $form->get('nome');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($curso->getId(), $id->getValue());
        $this->assertEquals($curso->getNome(), $nome->getValue());
    }

    /**
     * testa a inclusao de um novo registro
     */
    public function testCursoSaveActionPostRequest()
    {
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        //dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Nome do Curso');
        $this->request->getPost()->set('sigla', 'NC');
        $this->request->getPost()->set('quantidadeEtapa', '2');
        $this->request->getPost()->set('horaFalta', '10');
        $this->request->getPost()->set('cargaHoraria', '60');
        $this->request->getPost()->set('atoPoderPublico', '');

        $habilitacao = $this->buildHabilitacao();
        $instituicao = $this->buildInstituicao();
        $nivelEnsino = $this->buildNivelEnsino();
        $tipoEnsino = $this->buildTipoEnsino();
        $tipoRegime = $this->buildTipoRegime();
        $em->persist($habilitacao);
        $em->persist($instituicao);
        $em->persist($nivelEnsino);
        $em->persist($tipoEnsino);
        $em->persist($tipoRegime);
        $em->flush();

        $this->request->getPost()->set('habilitacoes[]', $habilitacao->getId());
        $this->request->getPost()->set('objetivo', 'Objetivo do Curso');
        $this->request->getPost()->set('publicoAlvo', 'publico alvo do curso');
        $this->request->getPost()->set('instituicao', $instituicao->getId());
        $this->request->getPost()->set('nivelEnsino', $nivelEnsino->getId());
        $this->request->getPost()->set('tipoEnsino', $tipoEnsino->getId());
        $this->request->getPost()->set('tipoRegime', $tipoRegime->getId());
        $this->request->getPost()->set('ativo', 1);
        $this->request->getPost()->set('padraoAnoEscolar', 1);
        $this->request->getPost()->set('multiSeriado', 1);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/curso', $headers->get('Location'));

    }

    /**
     * testa o update de um registro
     */
    public function testCursoUpdateAction()
    {
        $curso = $this->buildCurso();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($curso);
        $em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $curso->getId());
        $this->request->getPost()->set('nome', 'Nome do Curso');
        $this->request->getPost()->set('sigla', 'NC');
        $this->request->getPost()->set('quantidadeEtapa', '2');
        $this->request->getPost()->set('horaFalta', '10');
        $this->request->getPost()->set('cargaHoraria', '60');
        $this->request->getPost()->set('atoPoderPublico', '');
        $this->request->getPost()->set('habilitacoes[]', $curso->getHabilitacoes());
        $this->request->getPost()->set('objetivo', 'Objetivo do Curso');
        $this->request->getPost()->set('publicoAlvo', 'publico alvo do curso');
        $this->request->getPost()->set('instituicao', $curso->getInstituicao()->getId());
        $this->request->getPost()->set('nivelEnsino', $curso->getNivelEnsino()->getId());
        $this->request->getPost()->set('tipoEnsino', $curso->getTipoEnsino()->getId());
        $this->request->getPost()->set('tipoRegime', $curso->getTipoRegime()->getId());
        $this->request->getPost()->set('ativo', $curso->getAtivo());
        $this->request->getPost()->set('padraoAnoEscolar', $curso->getPadraoAnoEscolar());
        $this->request->getPost()->set('multiSeriado', $curso->getMultiSeriado());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/curso', $headers->get('Location'));

        $savedCurso = $em->find(get_class($curso), $curso->getId());
        $this->assertEquals('Nome do Curso', $savedCurso->getNome());
    }

    /**
     * Testa a inclusao, o formulario invalido e nome vazio
     */
    public function testCursoInvalidFormPostRequest()
    {
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        //dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('sigla', 'NC');
        $this->request->getPost()->set('quantidadeEtapa', '2');
        $this->request->getPost()->set('horaFalta', '10');
        $this->request->getPost()->set('cargaHoraria', '60');
        $this->request->getPost()->set('atoPoderPublico', '');

        $habilitacao = $this->buildHabilitacao();
        $instituicao = $this->buildInstituicao();
        $nivelEnsino = $this->buildNivelEnsino();
        $tipoEnsino = $this->buildTipoEnsino();
        $tipoRegime = $this->buildTipoRegime();
        $em->persist($habilitacao);
        $em->persist($instituicao);
        $em->persist($nivelEnsino);
        $em->persist($tipoEnsino);
        $em->persist($tipoRegime);
        $em->flush();

        $this->request->getPost()->set('habilitacoes[]', $habilitacao->getId());
        $this->request->getPost()->set('objetivo', 'Objetivo do Curso');
        $this->request->getPost()->set('publicoAlvo', 'publico alvo do curso');
        $this->request->getPost()->set('instituicao', $instituicao->getId());
        $this->request->getPost()->set('nivelEnsino', $nivelEnsino->getId());
        $this->request->getPost()->set('tipoEnsino', $tipoEnsino->getId());
        $this->request->getPost()->set('tipoRegime', $tipoRegime->getId());
        $this->request->getPost()->set('ativo', 1);
        $this->request->getPost()->set('padraoAnoEscolar', 1);
        $this->request->getPost()->set('multiSeriado', 1);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();
        //	Verify Filters Validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs["nome"]['isEmpty']);
    }

	/**
	 * Testa a busca com resultados
	 */
	public function testCursoBuscaPostRequest()
	{
		$cursoA = $this->buildCurso();
		$cursoB = $this->buildCurso();
		$cursoB->setNome('Curso A');
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($cursoA);
		$em->persist($cursoB);
		$em->flush();

		// invoca a rota index
		$this->routeMatch->setParam('action', 'busca');
		$this->request->getPost()->set('q', 'Curso A');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		// verifica o response
		$response = $this->controller->getResponse();
		$this->assertEquals(200, $response->getStatusCode());

		// testa os dados da View
		$variables = $result->getVariables();

		// faz a comparacao dos dados
		$dados = $variables['dados'];
		$this->assertEquals($cursoB->getNome(), $dados[0]->getNome());
	}

	/**
	 * Testa a exclusao sem passar o id
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testCursoInvalidDeleteAction()
	{
		// dispara a acao
		$this->routeMatch->setParam('action', 'delete');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		// verifica a resposta
		$response = $this->controller->getResponse();
	}

	/**
	 * Testa a exlusao
	 */
	public function testCursoDeleteAction()
	{
		$curso = $this->buildCurso();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($curso);
		$em->flush();

		// dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $curso->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		// verifica a resposta
		$response = $this->controller->getResponse();

		// a pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /escola/curso', $headers->get('Location'));

	}

	/**
	 * Testa a tela de detalhes
	 */
	public function testCursoDetalhesAction()
	{
		$curso = $this->buildCurso();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($curso);
		$em->flush();

		// dispara a acao
		$this->routeMatch->setParam('action', 'detalhes');
		$this->routeMatch->setParam('id', $curso->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		// verifica a resposta
		$response = $this->controller->getResponse();
		$this->assertEquals(200, $response->getStatusCode());

		// testa se um ViewModel foi retornado
		$this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

		// testa os dados da View
		$variables = $result->getVariables();
		$this->assertArrayHasKey('data', $variables);

		// faz a comparacao dos dados
		$data = $variables['data'];
		$this->assertEquals($curso->getNome(), $data->getNome());
	}

	/**
	 * Testa visualizacao de detalhes de um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testCursoDetalhesInvalidIdAction()
	{
		$this->routeMatch->setParam('action', 'detalhes');
		$this->routeMatch->setParam('id', -1);

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		// verifica a resposta
		$response = $this->controller->getResponse();
		$this->assertEquals(200, $response->getStatusCode());
	}

	/**
	 * Testa a exclusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testCursoInvalidIdDeleteAction()
	{
		$curso = $this->buildCurso();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($curso);
		$em->flush();

		// dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', 2);

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		// verifica a resposta
		$response = $this->controller->getResponse();

		// a pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /escola/curso', $headers->get('Location')
		);
	}

	private function buildCurso()
	{
		$curso = new Curso();
		$curso->setNome('Curso Teste');
		$curso->setSigla('CT');
		$curso->setQuantidadeEtapa(4);
		$curso->setCargaHoraria(60.0);
		$curso->setAtoPoderPublico('ato');
		$curso->setObjetivo('Objetivo do curso');
		$curso->setPublicoAlvo('Infantil');
		$curso->setAtivo(true);
		$curso->setPadraoAnoEscolar(false);
		$curso->setHoraFalta(50.0);
		$curso->setMultiSeriado(0);
		
		$instituicao = $this->buildInstituicao();
		$curso->setInstituicao($instituicao);
		
		$nivelEnsino = $this->buildNivelEnsino();
		$curso->setNivelEnsino($nivelEnsino);

		$tipoEnsino = $this->buildTipoEnsino();
		$curso->setTipoEnsino($tipoEnsino);

		$tipoRegime = $this->buildTipoRegime();
		$curso->setTipoRegime($tipoRegime);

		return $curso;
	}

    private function buildInstituicao()
    {
        $instituicao = new \Escola\Entity\Instituicao();
        $instituicao->setNome('Prefeitura Municipial Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
    }

	private function buildNivelEnsino()
	{
		$nivelEnsino = new \Escola\Entity\NivelEnsino();
		$nivelEnsino->setNome('Nivel 1');
		$nivelEnsino->setDescricao('Descricao nivel de ensino');

		return $nivelEnsino;
	}

	private function buildTipoEnsino()
	{
		$tipoEnsino = new \Escola\Entity\TipoEnsino();
		$tipoEnsino->setNome('Tipo Ensino');
		$tipoEnsino->setAtivo(true);

		return $tipoEnsino;
	}

	private function buildTipoRegime()
	{
		$tipoRegime = new \Escola\Entity\TipoRegime();
		$tipoRegime->setNome('Tipo Regime 1');
		$tipoRegime->setAtivo(true);

		return $tipoRegime;
	}

    private function buildHabilitacao()
    {
        $habilitacao = new \Escola\Entity\Habilitacao();
        $habilitacao->setNome('Habi 1');
        $habilitacao->setDescricao('Habilitacao 1');
        $habilitacao->setAtivo(true);

        return $habilitacao;
    }


}
