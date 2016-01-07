<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/12/15
 * Time: 14:27
 */
use Escola\Entity\Habilitacao;

/**
 * @group Controller
 */
class HabilitacaoControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string HabilitacaoController
     */
    protected $controllerFQDN = 'Escola\Controller\HabilitacaoController';

    /**
     * Nome da rota. geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando as habilitacoes
     */
    public function testHabilitacaoIndexAction()
    {
        $habilitacaoA = $this->buildHabilitacao();
        $habilitacaoB = $this->buildHabilitacao();
        $habilitacaoB->setNome('Medio');
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($habilitacaoA);
        $em->persist($habilitacaoB);
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
        $this->assertEquals($habilitacaoA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($habilitacaoB->getNome(), $paginator->getItem(2)->getNome());

    }

    /**
     * Testa a busca com resultados
     */
    public function testHabilitacaoBuscaPostActionRequest()
    {
        $habilitacaoA = $this->buildHabilitacao();
        $habilitacaoB = $this->buildHabilitacao();
        $habilitacaoB->setNome('GOLD');
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($habilitacaoA);
        $em->persist($habilitacaoB);
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
        $this->assertEquals($habilitacaoB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testHabilitacaoInvalidDeleteAction()
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
    public function testHabilitacaoDeleteAction()
    {
        $habilitacao = $this->buildHabilitacao();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($habilitacao);
        $em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $habilitacao->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/habilitacao', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testHabilitacaoDetalhesAction()
    {
        $habilitacao = $this->buildHabilitacao();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($habilitacao);
        $em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $habilitacao->getId());

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
        $this->assertEquals($habilitacao->getNome(), $data->getNome());

    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testHabilitacaoDetalhesInvalidIdAction()
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
    public function testHabilitacaoInvalidIdDeleteAction()
    {
        $habilitacao = $this->buildHabilitacao();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($habilitacao);
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
            'Location: /escola/habilitacao', $headers->get('Location')
        );
    }

    // TODO: ta faltando algo aqui

    private function buildHabilitacao()
    {
        $habilitacao = new Habilitacao();
        $habilitacao->setNome('Habilitacao 1');
        $habilitacao->setDescricao('Descricao');

        return $habilitacao;
    }

}