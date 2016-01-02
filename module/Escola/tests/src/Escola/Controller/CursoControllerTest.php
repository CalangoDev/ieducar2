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


}
