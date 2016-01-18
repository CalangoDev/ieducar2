<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 18/01/16
 * Time: 12:21
 */
use Escola\Entity\Localizacao;

/**
 * @group Controller
 */
class LocalizacaoControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string LocalizacaoController
     */
    protected $controllerFQDN = 'Escola\Controller\LocalizacaoController';

    /**
     * Nome da rota. geralmente o nome do moulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testLocalizacaoIndexAction()
    {
        $localizacaoA = $this->buildLocalizacao();
        $localizacaoB = $this->buildLocalizacao();
        $localizacaoB->setNome('Rural');
        $this->em->persist($localizacaoA);
        $this->em->persist($localizacaoB);
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

        // faz a comparacao dos dados
        $paginator = $variables['dados'];
        $this->assertEquals($localizacaoA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($localizacaoB->getNome(), $paginator->getItem(2)->getNome());
    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testLocalizacaoSaveActionNewRequest()
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

    }

    /**
     * testa a tela de alteracoes de um registro
     */
    public function testLocalizacaoSaveActionUpdateFormRequest()
    {
        $localizacao = $this->buildLocalizacao();
        $this->em->persist($localizacao);
        $this->em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $localizacao->getId());
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
        $this->assertEquals($localizacao->getId(), $id->getValue());
        $this->assertEquals($localizacao->getNome(), $nome->getValue());
    }

    /**
     * Testa a inclusao de um novo registro
     */
    public function testLocalizacaoActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Urbana');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/localizacao', $headers->get('Location'));
    }

    /**
     * Testa o update de um registro
     */
    public function testLocalizacaoUpdateAction()
    {
        $localizacao = $this->buildLocalizacao();
        $this->em->persist($localizacao);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $localizacao->getId());
        $this->request->getPost()->set('nome', 'Novo Nome');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/localizacao', $headers->get('Location'));

        $savedLocalizacao = $this->em->find(get_class($localizacao), $localizacao->getId());
        $this->assertEquals('Novo Nome', $savedLocalizacao->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testLocalizacaoSaveActionInvalidFormPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');

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
    public function testLocalizacaoBuscaPostActionRequest()
    {
        $localizacaoA = $this->buildLocalizacao();
        $localizacaoB = $this->buildLocalizacao();
        $localizacaoB->setNome('GOLD');
        $this->em->persist($localizacaoA);
        $this->em->persist($localizacaoB);
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
        $this->assertEquals($localizacaoB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testLocalizacaoInvalidDeleteAction()
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
    public function testLocalizacaoDeleteAction()
    {
        $localizacao = $this->buildLocalizacao();
        $this->em->persist($localizacao);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $localizacao->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/localizacao', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testLocalizacaoDetalhesAction()
    {
        $localizacao = $this->buildLocalizacao();
        $this->em->persist($localizacao);
        $this->em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $localizacao->getId());

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
        $this->assertEquals($localizacao->getNome(), $data->getNome());

    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testLocalizacaoDetalhesInvalidIdAction()
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
    public function testLocalizacaoInvalidIdDeleteAction()
    {
        $localizacao = $this->buildLocalizacao();
        $this->em->persist($localizacao);
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
            'Location: /escola/localizacao', $headers->get('Location')
        );
    }

    private function buildLocalizacao()
    {
        $localizacao = new Localizacao();
        $localizacao->setNome('Urbana');

        return $localizacao;
    }

}