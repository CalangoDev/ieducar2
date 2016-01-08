<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/12/15
 * Time: 09:48
 */
use Core\Test\ControllerTestCase;
use Escola\Entity\TipoRegime;

/**
 * @group Controller
 */
class TipoRegimeControllerTest extends ControllerTestCase
{
    /**
     * Namespace completa do Controller
     * @var string TipoRegimeController
     */
    protected $controllerFQDN = 'Escola\Controller\TipoRegimeController';

    /**
     * Nome da rota. geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial
     */
    public function testTipoRegimeIndexAction()
    {
        $tipoRegimeA = $this->buildTipoRegime();
        $tipoRegimeB = $this->buildTipoRegime();
        $tipoRegimeB->setNome('Medio');

        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoRegimeA);
        $em->persist($tipoRegimeB);
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

        //	Testa os dados da View
        $variables = $result->getVariables();

        $this->assertArrayHasKey('dados', $variables);

        //	Faz a comparacao dos dados
        $paginator = $variables['dados'];
        $this->assertEquals($tipoRegimeA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($tipoRegimeB->getNome(), $paginator->getItem(2)->getNome());
    }

    /**
     * Testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testTipoRegimeSaveActionNewRequest()
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

        $nome = $form->get('nome');
        $this->assertEquals('nome', $nome->getName());
        $this->assertEquals('text', $nome->getAttribute('type'));

        $ativo = $form->get('ativo');
        $this->assertEquals('ativo', $ativo->getName());
        $this->assertEquals('Zend\Form\Element\Select', $ativo->getAttribute('type'));

    }

    /**
     * testa a tela de alteracoes de um registro
     */
    public function testTipoRegimeSaveActionUpdateFormRequest()
    {
        $tipoRegime = $this->buildTipoRegime();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoRegime);
        $em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $tipoRegime->getId());
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
        $nome = $form->get('nome');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($tipoRegime->getId(), $id->getValue());
        $this->assertEquals($tipoRegime->getNome(), $nome->getValue());
    }

    /**
     * Testa a inclusao de um novo registro
     */
    public function testTipoRegimeSaveActionPostRequest()
    {
        //	Dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Garrincha');
        $this->request->getPost()->set('ativo', true);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, estao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tipo-regime', $headers->get('Location'));
    }

    /**
     * Testa o update
     */
    public function testTipoRegimeUpdateAction()
    {
        $tipoRegime = $this->buildTipoRegime();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoRegime);
        $em->flush();
        //	Dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $tipoRegime->getId());
        $this->request->getPost()->set('nome', 'Medio');
        $this->request->getPost()->set('ativo', true);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, estao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tipo-regime', $headers->get('Location'));

        $savedTipoRegime = $em->find(get_class($tipoRegime), $tipoRegime->getId());
        $this->assertEquals('Medio', $savedTipoRegime->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testTipoRegimeSaveActionInvalidFormPostRequest()
    {
        $tipoRegime = $this->buildTipoRegime();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoRegime);
        $em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('ativo', true);

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
    public function testTipoRegimeBuscaPostActionRequest()
    {
        $tipoRegimeA = $this->buildTipoRegime();
        $tipoRegimeB = $this->buildTipoRegime();
        $tipoRegimeB->setNome("GOLD");
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoRegimeA);
        $em->persist($tipoRegimeB);
        $em->flush();

        //	Invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', 'GOLD');

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
        $this->assertEquals($tipoRegimeB->getNome(), $dados[0]->getNome());
    }
    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testTipoRegimeInvalidDeleteAction()
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
     * Testa a exclusao
     */
    public function testTipoRegimeDeleteAction()
    {
        $tipoRegime = $this->buildTipoRegime();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoRegime);
        $em->flush();

        //	Dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $tipoRegime->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        //	Verifica a reposta
        $response = $this->controller->getResponse();

        //	A pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals(
            'Location: /escola/tipo-regime', $headers->get('Location')
        );
    }

    /**
     * Testa a tela de detalhes
     */
    public function testTipoRegimeDetalhesAction()
    {
        $tipoRegime = $this->buildTipoRegime();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoRegime);
        $em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $tipoRegime->getId());

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
        $this->assertEquals($tipoRegime->getNome(), $data->getNome());

    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testTipoRegimeDetalhesInvalidIdAction()
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
    public function testTipoRegimeInvalidIdDeleteAction()
    {
        $tipoRegime = $this->buildTipoRegime();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoRegime);
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
            'Location: /escola/tiporegime', $headers->get('Location')
        );
    }



    private function buildTipoRegime()
    {
        $tipoRegime = new TipoRegime();
        $tipoRegime->setNome('Integral');

        return $tipoRegime;
    }

}