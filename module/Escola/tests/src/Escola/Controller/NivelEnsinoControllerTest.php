<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/12/15
 * Time: 23:29
 */
use Escola\Entity\Habilitacao;

/**
 * @group Controller
 */
class NivelEnsinoControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string HabilitacaoController
     */
    protected $controllerFQDN = 'Escola\Controller\NivelEnsinoController';

    /**
     * Nome da rota. geralmente o nome do moulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testNivelEnsinoIndexAction()
    {
        $nivelEnsinoA = $this->buildNivelEnsino();
        $nivelEnsinoB = $this->buildNivelEnsino();
        $nivelEnsinoB->setNome('Medio');
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($nivelEnsinoA);
        $em->persist($nivelEnsinoB);
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
        $this->assertEquals($nivelEnsinoA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($nivelEnsinoB->getNome(), $paginator->getItem(2)->getNome());

    }

    /**
     * Testa a busca com resultados
     */
    public function testNivelEnsinoBuscaPostActionRequest()
    {
        $nivelEnsinoA = $this->buildNivelEnsino();
        $nivelEnsinoB = $this->buildNivelEnsino();
        $nivelEnsinoB->setNome('GOLD');
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($nivelEnsinoA);
        $em->persist($nivelEnsinoB);
        $em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', 'GOLD');

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
        $this->assertEquals($nivelEnsinoB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testNivelEnsinoInvalidDeleteAction()
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
     * Testa a exclusao
     */
    public function testNivelEnsinoDeleteAction()
    {
        $nivelEnsino = $this->buildNivelEnsino();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($nivelEnsino);
        $em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $nivelEnsino->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/nivel-ensino', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testNivelEnsinoDetalhesAction()
    {
        $nivelEnsino = $this->buildNivelEnsino();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($nivelEnsino);
        $em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $nivelEnsino->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // Verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        //	Testa se um ViewModel foi retornado
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);


        //	Testa os dados da View
        $variables = $result->getVariables();
        $this->assertArrayHasKey('data', $variables);

        //	Faz a comparação dos dados
        $data = $variables["data"];
        $this->assertEquals($nivelEnsino->getNome(), $data->getNome());

    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testNivelEnsinoDetalhesInvalidIdAction()
    {
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', -1);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Testa a exlusao passando um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testNivelEnsinoInvalidIdDeleteAction()
    {
        $nivelEnsino = $this->buildNivelEnsino();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($nivelEnsino);
        $em->flush();

        //	Dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', 2);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        //	Verifica a resposta
        $response = $this->controller->getResponse();

        //	A pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals(
            'Location: /escola/nivel-ensino', $headers->get('Location')
        );
    }

    private function buildNivelEnsino()
    {
        $instituicao = $this->buildInstituicao();
        $nivelEnsino = new \Escola\Entity\NivelEnsino();
        $nivelEnsino->setNome('Habilitacao 1');
        $nivelEnsino->setDescricao('Descricao');
        $nivelEnsino->setInstituicao($instituicao);

        return $nivelEnsino;
    }

    private function buildInstituicao()
    {
        $instituicao = new \Escola\Entity\Instituicao();
        $instituicao->setNome('Prefeitura Municipial Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
    }
}