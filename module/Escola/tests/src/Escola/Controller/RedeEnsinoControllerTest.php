<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 17/01/16
 * Time: 14:06
 */
use Escola\Entity\RedeEnsino;
use Escola\Entity\Instituicao;

/**
 * @group Controller
 */
class RedeEnsinoControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string RedeEnsinoController
     */
    protected $controllerFQDN = 'Escola\Controller\RedeEnsinoController';

    /**
     * Nome da rota, geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * Testa a pagina inicial, listando os dados
     */
    public function testRedeEnsinoIndexAction()
    {
        $redeA = $this->buildRedeEnsino();
        $redeB = $this->buildRedeEnsino();
        $redeB->setNome('Outra Rede');
        $this->em->persist($redeA);
        $this->em->persist($redeB);
        $this->em->flush();

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

        // fas a comparacao dos dados
        $paginator = $variables['dados'];
        $this->assertEquals($redeA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($redeB->getNome(), $paginator->getItem(2)->getNome());
    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testRedeEnsinoSaveActionNewRequest()
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
        // testa os itens do formulario
        $id = $form->get('id');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals('hidden', $id->getAttribute('type'));

        $nome = $form->get('nome');
        $this->assertEquals('nome', $nome->getName());
        $this->assertEquals('text', $nome->getAttribute('type'));

        $instituicao = $form->get('instituicao');
        $this->assertEquals('instituicao', $instituicao->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $instituicao->getAttribute('type'));

    }

    /**
     * testa a tela de alteracoes de um registro
     */
    public function testRedeEnsinoSaveActionUpdateFormRequest()
    {
        $redeEnsino = $this->buildRedeEnsino();
        $this->em->persist($redeEnsino);
        $this->em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $redeEnsino->getId());
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
        $this->assertEquals($redeEnsino->getId(), $id->getValue());
        $this->assertEquals($redeEnsino->getNome(), $nome->getValue());
    }


    /**
     * Testa a inclusao de um novo registro
     */
    public function testRedeEnsinoSaveActionPostRequest()
    {
        $instituicao = $this->buildInstituicao();
        $this->em->persist($instituicao);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Tipo de Ensino');
        $this->request->getPost()->set('instituicao', $instituicao->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/rede-ensino', $headers->get('Location'));
    }

    /**
     * Testa o update de um registro
     */
    public function testRedeEnsinoUpdateAction()
    {
        $redeEnsino = $this->buildRedeEnsino();
        $this->em->persist($redeEnsino);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $redeEnsino->getId());
        $this->request->getPost()->set('nome', 'Novo Nome');
        $this->request->getPost()->set('instituicao', $redeEnsino->getInstituicao()->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/rede-ensino', $headers->get('Location'));

        $savedRedeEnsino = $this->em->find(get_class($redeEnsino), $redeEnsino->getId());
        $this->assertEquals('Novo Nome', $savedRedeEnsino->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testRedeEnsinoSaveActionInvalidFormPostRequest()
    {
        $instituicao = $this->buildInstituicao();
        $this->em->persist($instituicao);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('instituicao', $instituicao->getId());

        $result= $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina nao redireciona por causa do erro, entao o status = 200
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();

        // verify filters validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs["nome"]['isEmpty']);
    }


    /**
     * Testa a busca com resultados
     */
    public function testRedeEnsinoBuscaPostActionRequest()
    {
        $redeEnsinoA = $this->buildRedeEnsino();
        $redeEnsinoB = $this->buildRedeEnsino();
        $redeEnsinoB->setNome('GOLD');
        $this->em->persist($redeEnsinoA);
        $this->em->persist($redeEnsinoB);
        $this->em->flush();

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
        $this->assertEquals($redeEnsinoB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testRedeEnsinoInvalidDeleteAction()
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
    public function testRedeEnsinoDeleteAction()
    {
        $redeEnsino = $this->buildRedeEnsino();
        $this->em->persist($redeEnsino);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $redeEnsino->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/rede-ensino', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testRedeEnsinoDetalhesAction()
    {
        $redeEnsino = $this->buildRedeEnsino();
        $this->em->persist($redeEnsino);
        $this->em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $redeEnsino->getId());

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
        $this->assertEquals($redeEnsino->getNome(), $data->getNome());

    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testRedeEnsinoDetalhesInvalidIdAction()
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
    public function testRedeEnsinoInvalidIdDeleteAction()
    {
        $redeEnsino = $this->buildRedeEnsino();
        $this->em->persist($redeEnsino);
        $this->em->flush();

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
            'Location: /escola/rede-ensino', $headers->get('Location')
        );
    }

    private function buildInstituicao()
    {
        $instituicao = new Instituicao();
        $instituicao->setNome('Prefeitura Municipal Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
    }

    private function buildRedeEnsino()
    {
        $redeEnsino = new RedeEnsino();
        $redeEnsino->setNome('Municipal');
        $instituicao = $this->buildInstituicao();
        $redeEnsino->setInstituicao($instituicao);
        $redeEnsino->setAtivo(true);

        return $redeEnsino;
    }
}