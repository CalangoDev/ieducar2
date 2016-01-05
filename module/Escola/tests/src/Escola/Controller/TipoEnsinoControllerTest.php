<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 23/12/15
 * Time: 16:04
 */
use Escola\Entity\TipoEnsino;

/**
 * @group Controller
 */
class TipoEnsinoControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string TipoEnsinoController
     */
    protected $controllerFQDN = 'Escola\Controller\TipoEnsinoController';

    /**
     * Nome da rota. geralmente o nome do moulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os tipos de ensino
     */
    public function testTipoEnsinoIndexAction()
    {
        $tipoEnsinoA = $this->buildTipoEnsino();
        $tipoEnsinoB = $this->buildTipoEnsino();
        $tipoEnsinoB->setNome('Medio');
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoEnsinoA);
        $em->persist($tipoEnsinoB);
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
        $this->assertEquals($tipoEnsinoA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($tipoEnsinoB->getNome(), $paginator->getItem(2)->getNome());

    }

    /**
     * Testa a busca com resultados
     */
    public function testTipoEnsinoBuscaPostActionRequest()
    {
        $tipoEnsinoA = $this->buildTipoEnsino();
        $tipoEnsinoB = $this->buildTipoEnsino();
        $tipoEnsinoB->setNome('GOLD');
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoEnsinoA);
        $em->persist($tipoEnsinoB);
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
        $this->assertEquals($tipoEnsinoB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testTipoEnsinoInvalidDeleteAction()
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
    public function testTipoEnsinoDeleteAction()
    {
        $tipoEnsino = $this->buildTipoEnsino();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoEnsino);
        $em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $tipoEnsino->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tipo-ensino', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testTipoEnsinoDetalhesAction()
    {
        $tipoEnsino = $this->buildTipoEnsino();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoEnsino);
        $em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $tipoEnsino->getId());

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
        $this->assertEquals($tipoEnsino->getNome(), $data->getNome());

    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testTipoEnsinoDetalhesInvalidIdAction()
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
    public function testTipoEnsinoInvalidIdDeleteAction()
    {
        $tipoEnsino = $this->buildTipoEnsino();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoEnsino);
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
            'Location: /escola/tipo-ensino', $headers->get('Location')
        );
    }

    private function buildTipoEnsino()
    {
        $instituicao = $this->buildInstituicao();
        $tipoEnsino = new TipoEnsino();
        $tipoEnsino->setNome('Integral');

        return $tipoEnsino;
    }

}