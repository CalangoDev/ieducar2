<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 04/05/16
 * Time: 10:00
 */
use Escola\Entity\Modulo;

/**
 * @group Controller
 */
class ModuloControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string ModuloController
     */
    protected $controllerFQDN = 'Escola\Controller\ModuloController';

    /**
     * Nome da rota, geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testModuloIndexAction()
    {
        $moduloA = $this->buildModulo();
        $moduloB = $this->buildModulo();
        $moduloB->setNome('Outro Modulo');
        $this->em->persist($moduloA);
        $this->em->persist($moduloB);
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
        $this->assertEquals($moduloA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($moduloB->getNome(), $paginator->getItem(2)->getNome());
    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testModuloSaveActionNewRequest()
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

        $descricao = $form->get('descricao');
        $this->assertEquals('descricao', $descricao->getName());
        $this->assertEquals('textarea', $descricao->getAttribute('type'));

        $numeroMeses = $form->get('numeroMeses');
        $this->assertEquals('numeroMeses', $numeroMeses->getName());
        $this->assertEquals('text', $numeroMeses->getAttribute('type'));

        $numeroSemanas = $form->get('numeroSemanas');
        $this->assertEquals('numeroSemanas', $numeroSemanas->getName());
        $this->assertEquals('text', $numeroSemanas->getAttribute('type'));

        $ativo = $form->get('ativo');
        $this->assertEquals('ativo', $ativo->getName());
        $this->assertEquals('Zend\Form\Element\Select', $ativo->getAttribute('type'));
    }

    /**
     * testa a tela de alteracoes de um registro
     * @return void
     */
    public function testModuloSaveActionUpdateFormRequest()
    {
        $modulo = $this->buildModulo();
        $this->em->persist($modulo);
        $this->em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $modulo->getId());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();

        // verifica se existe um form
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];

        // testa os itens do formulario
        $id = $form->get('id');
        $nome = $form->get('nome');
        $descricao = $form->get('descricao');
        $numeroMeses = $form->get('numeroMeses');
        $numeroSemanas = $form->get('numeroSemanas');
        $ativo = $form->get('ativo');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($modulo->getId(), $id->getValue());
        $this->assertEquals($modulo->getNome(), $nome->getValue());
        $this->assertEquals($modulo->getDescricao(), $descricao->getValue());
        $this->assertEquals($modulo->getNumeroMeses(), $numeroMeses->getValue());
        $this->assertEquals($modulo->getNumeroSemanas(), $numeroSemanas->getValue());
        $this->assertEquals($modulo->isAtivo(), $ativo->getValue());
    }

    /**
     * testa a inclusao de um novo registro
     * @return void
     */
    public function testModuloSaveActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Modulo X');
        $this->request->getPost()->set('descricao', 'Descrição do Modulo');
        $this->request->getPost()->set('numeroMeses', '10');
        $this->request->getPost()->set('numeroSemanas', '30');
        $this->request->getPost()->set('ativo', true);
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/modulo', $headers->get('Location'));
    }

    /**
     * testa o update de um registro
     */
    public function testModuloUpdateAction()
    {
        $modulo = $this->buildModulo();
        $this->em->persist($modulo);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $modulo->getId());
        $this->request->getPost()->set('nome', 'Outro Nome');
        $this->request->getPost()->set('descricao', $modulo->getDescricao());
        $this->request->getPost()->set('numeroMeses', $modulo->getNumeroMeses());
        $this->request->getPost()->set('numeroSemanas', $modulo->getNumeroSemanas());
        $this->request->getPost()->set('ativo', $modulo->isAtivo());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/modulo', $headers->get('Location'));

        $savedModulo = $this->em->find(get_class($modulo), $modulo->getId());
        $this->assertEquals('Outro Nome', $savedModulo->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testModuloSaveActionInvalidFormPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('descricao', '');
        $this->request->getPost()->set('numeroMeses', '');
        $this->request->getPost()->set('numeroSemanas', '');
        $this->request->getPost()->set('ativo', true);

        $result = $this->controller->dispatch(
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
     * testa a busca com resultados
     */
    public function testModuloPostActionRequest()
    {
        $moduloA = $this->buildModulo();
        $moduloB = $this->buildModulo();
        $moduloB->setNome('Modulo B');
        $this->em->persist($moduloA);
        $this->em->persist($moduloB);
        $this->em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', 'Modulo B');

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
        $this->assertEquals($moduloB->getNome(), $dados[0]->getNome());
    }

    /**
     * testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMesage Código Obrigatório
     */
    public function testModuloInvalidDeleteAction()
    {
        // dispara aa acao
        $this->routeMatch->setParam('action', 'delete');
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
    }

    /**
     * testa a exclusao
     */
    public function testModuloDeleteAction()
    {
        $modulo = $this->buildModulo();
        $this->em->persist($modulo);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $modulo->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/modulo', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testModuloDetalhesAction()
    {
        $modulo = $this->buildModulo();

        $this->em->persist($modulo);
        $this->em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $modulo->getId());

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
        $this->assertEquals($modulo->getNome(), $data->getNome());

    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testModuloDetalhesInvalidIdAction()
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
    public function testModuloInvalidIdDeleteAction()
    {
        $modulo = $this->buildModulo();
        $this->em->persist($modulo);
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
            'Location: /escola/modulo', $headers->get('Location')
        );
    }


    /**
     * @return Modulo
     */
    private function buildModulo()
    {
        $modulo = new Modulo();
        $modulo->setNome('Modulo X');
        $modulo->setDescricao('Descrição');
        $modulo->setNumeroMeses(10);
        $modulo->setNumeroSemanas(30);
        $modulo->setAtivo(true);

        return $modulo;
    }
}