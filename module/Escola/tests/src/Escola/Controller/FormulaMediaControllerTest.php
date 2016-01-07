<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/01/16
 * Time: 15:25
 */
use Escola\Entity\FormulaMedia;

/**
 * @group Controller
 */
class FormulaMediaControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string FormulaMediaController
     */
    protected $controllerFQDN = 'Escola\Controller\FormulaMediaController';

    /**
     * Nome da rota, geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testFormulaMediaIndexAction()
    {
        $formulaMediaA = $this->buildFormulaMedia();
        $formulaMediaB = $this->buildFormulaMedia();
        $formulaMediaB->setNome('Outra Formula');
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($formulaMediaA);
        $em->persist($formulaMediaB);
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
        $this->assertEquals($formulaMediaA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($formulaMediaB->getNome(), $paginator->getItem(2)->getNome());
    }

    /**
     * Testa a tela de inclusao de um novo registro
     *
     * @return void
     */
    public function testFormulaMediaSaveActionNewRequest()
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

        $formulaMedia = $form->get('formulaMedia');
        $this->assertEquals('formulaMedia', $formulaMedia->getName());
        $this->assertEquals('text', $formulaMedia->getAttribute('type'));

        $tipoFormula = $form->get('tipoFormula');
        $this->assertEquals('tipoFormula', $tipoFormula->getName());
        $this->assertEquals('Zend\Form\Element\Select', $tipoFormula->getAttribute('type'));
    }

    /**
     * testa ta tela de alteracoes de um registro
     */
    public function testFormulaMediaControllerTest()
    {
        $formulaMedia = $this->buildFormulaMedia();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($formulaMedia);
        $em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $formulaMedia->getId());
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
        $this->assertEquals($formulaMedia->getId(), $id->getValue());
        $this->assertEquals($formulaMedia->getNome(), $nome->getValue());
    }

    /**
     * testa a inclusao de um novo registro
     */
    public function testFormulaMediaSaveActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Fórmula X');
        $this->request->getPost()->set('formulaMedia', 'Se / Et');
        $this->request->getPost()->set('tipoFormula', '1');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/formula-media', $headers->get('Location'));
    }

    /**
     * Testa o update de um registro
     */
    public function testFormulaMediaUpdateAction()
    {
        $formulaMedia = $this->buildFormulaMedia();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($formulaMedia);
        $em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $formulaMedia->getId());
        $this->request->getPost()->set('nome', 'Outra Fórmula');
        $this->request->getPost()->set('formulaMedia', $formulaMedia->getFormulaMedia());
        $this->request->getPost()->set('tipoFormula', $formulaMedia->getTipoFormula());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/formula-media', $headers->get('Location'));

        $savedFormulaMedia = $em->find(get_class($formulaMedia), $formulaMedia->getId());
        $this->assertEquals('Outra Fórmula', $savedFormulaMedia->getNome());

    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testFormulaMediaInvalidFormPostRequest()
    {
        //dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('formulaMedia', 'Se \ Et');
        $this->request->getPost()->set('tipoFormula', '1');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        //	a pagina nao redireciona por causa do erro, estao o status = 200
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();

        //	Verify Filters Validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs["nome"]['isEmpty']);
    }

    /**
     * Testa a busca com resultados
     */
    public function testFormulaMediaBuscaPostActionRequest()
    {
        $formulaMediaA = $this->buildFormulaMedia();
        $formulaMediaB = $this->buildFormulaMedia();
        $formulaMediaB->setNome('Outra Formula');
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($formulaMediaA);
        $em->persist($formulaMediaB);
        $em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', 'Outra Formula');

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
        $this->assertEquals($formulaMediaB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testFormulaMediaInvalidDeleteAction()
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
    public function testFormulaMediaDeleteAction()
    {
        $formulaMedia = $this->buildFormulaMedia();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($formulaMedia);
        $em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $formulaMedia->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/formula-media', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testFormulaMediaDetalhesAction()
    {
        $formulaMedia = $this->buildFormulaMedia();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($formulaMedia);
        $em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $formulaMedia->getId());

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
        $this->assertEquals($formulaMedia->getNome(), $data->getNome());
    }

    /**
     * Testa visualizacao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testFormulaMediaDetalhesInvalidIdAction()
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
     * @return FormulaMedia
     */
    private function buildFormulaMedia()
    {
        $formulaMedia = new FormulaMedia();
        $formulaMedia->setNome('nome da formula');
        $formulaMedia->setFormulaMedia('Se / Et');
        $formulaMedia->setTipoFormula(1);

        return $formulaMedia;
    }
}