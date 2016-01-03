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
		$this->asserInstanceOf('Zend\View\Model\ViewModel', $result);

		// testa os dados da View
		$variables = $result->getVariables();
		$this->assertArrayHasKey('dados', $variables);

		// faz a comparacao dos dados
		$paginator = $variables['dados'];
		$this->assertEquals($cursoA->getNome(), $paginator->getItem(1)->getNome());
		$this->assertEquals($cursoB->getNome(), $paginator->getItem(2)->getNome());

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
			'Location: /escola/curso', $headers->get('Location');
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
		$curso->setMultiSeriado(false);
		
		$instituicao = $this->buildInstituicao();
		$curso->setInstituicao($curso);
		
		$nivelEnsino = $this->buildNivelEnsino();
		$curso->setNivelEnsino($nivelEnsino);

		$tipoEnsino = $this->buildTipoEnsino();
		$curso->setTipoEnsino($tipoEnsino);

		$tipoRegime = $this->buildTipoRegime();
		$curso->setTipoRegime($tipoRegime);

		$cursoHabilitacaoes = $this->buildCursoHabilitacoes();
		$curso->addCursoHabilitacao($cursoHabilitacoes);

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
		$nivelEnsino = new NivelEnsino();
		$nivelEnsino->setNome('Nivel 1');
		$nivelEnsino->setDescricao('Descricao nivel de ensino');
		$instituicao = $this->buildInstituicao();
		$nivelEnsino->setInstituicao($instituicao);

		return $nivelEnsino;
	}

	private function buildTipoEnsino()
	{
		$tipoEnsino = new TipoEnsino();
		$tipoEnsino->setNome('Tipo Ensino');
		$tipoEnsino->setAtivo(true);
		$instituicao = $this->buildInstituicao();
		$tipoEnsino->setInstituicao($instituicao);

		return $tipoEnsino;
	}

	private function buildTipoRegime()
	{
		$tipoRegime = new TipoRegime();
		$tipoRegime->setNome('Tipo Regime 1');
		$tipoRegime->setAtivo(true);
		$instituicao = $this->buildInstituicao();
		$tipoRegime->setInstituicao($instituicao);

		return $tipoRegime;
	}

	private function buildCursosHabilitacoes()
	{
		$cursoHabilitacoes = new ArrayCollection(); 
		$habilitacao = new \Escola\Entity\Habilitacao();
        $habilitacao->setNome('Habilitacao Nome');
		$habilitacao->setDescricao('Desc Habilitacao');
		$habilitacao->setAtivo(true);
		$instituicao = $this->buildInstituicao();
		$habilitacao->setInstituicao($instituicao);

        $cursoHabilitacoes->add($habilitacao);

        return $cursoHabilitacoes;
	}


}
