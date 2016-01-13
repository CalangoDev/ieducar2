<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 27/12/15
 * Time: 23:29
 */
use Escola\Entity\NivelEnsino;

/**
 * @group Controller
 */
class NivelEnsinoControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string NivelEnsinoController
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
        //$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $this->em->persist($nivelEnsinoA);
        $this->em->persist($nivelEnsinoB);
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
        $this->assertEquals($nivelEnsinoA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($nivelEnsinoB->getNome(), $paginator->getItem(2)->getNome());
    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testNivelEnsinoSaveActionNewRequest()
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

        $ativo = $form->get('ativo');
        $this->assertEquals('ativo', $ativo->getName());
        $this->assertEquals('Zend\Form\Element\Select', $ativo->getAttribute('type'));
    }

    /**
     * testa a tela de alteracoes de um registro
     */
    public function testNivelEnsinoSaveActionUpdateFormRequest()
    {
        $nivelEnsino = $this->buildNivelEnsino();
        $this->em->persist($nivelEnsino);
        $this->em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $nivelEnsino->getId());
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
        $descricao = $form->get('descricao');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($nivelEnsino->getId(), $id->getValue());
        $this->assertEquals($nivelEnsino->getNome(), $nome->getValue());
        $this->assertEquals($nivelEnsino->getDescricao(), $descricao->getValue());
    }

    /**
     * Testa a inclusao de um novo registro
     */
    public function testNivelEnsinoSaveActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Nivel de Ensino');
        $this->request->getPost()->set('descricao', 'Descrição');
        $this->request->getPost()->set('ativo', true);

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
     * Testa o update de um registro
     */
    public function testNivelEnsinoUpdateAction()
    {
        $nivelEnsino = $this->buildNivelEnsino();
        $this->em->persist($nivelEnsino);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $nivelEnsino->getId());
        $this->request->getPost()->set('nome', 'Novo Nome');
        $this->request->getPost()->set('descricao', $nivelEnsino->getDescricao());
        $this->request->getPost()->set('ativo', $nivelEnsino->getAtivo());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/nivel-ensino', $headers->get('Location'));

        $savedNivelEnsino = $this->em->find(get_class($nivelEnsino), $nivelEnsino->getId());
        $this->assertEquals('Novo Nome', $savedNivelEnsino->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testNivelEnsinoSaveActionInvalidFormPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('descricao', 'Descricao');
        $this->request->getPost()->set('ativo', true);

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
        $nivelEnsino = new NivelEnsino();
        $nivelEnsino->setNome('Habilitacao 1');
        $nivelEnsino->setDescricao('Descricao');

        return $nivelEnsino;
    }

}