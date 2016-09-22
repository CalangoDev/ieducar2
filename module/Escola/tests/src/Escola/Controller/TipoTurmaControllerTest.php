<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 10/09/16
 * Time: 11:37
 */
use Core\Test\ControllerTestCase;
use Escola\Entity\TipoTurma;

/**
 * @group Controller
 */
class TipoTurmaControllerTest extends ControllerTestCase
{
    /**
     * Namespace completa do Controller
     * @var string TipoTurmaController
     */
    protected $controllerFQDN = 'Escola\Controller\TipoTurmaController';

    /**
     * Nome da rota, geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    public function testTipoTurmaIndexAction()
    {
        $entityA = $this->buildTipoTurma();
        $entityB = $this->buildTipoTurma();
        $entityB->setNome('Outro Tipo de Turma');
        $this->em->persist($entityA);
        $this->em->persist($entityB);
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
        $this->assertEquals($entityA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($entityB->getNome(), $paginator->getItem(2)->getNome());
    }

    public function testTipoTurmaSaveActionNewRequest()
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

        $sigla = $form->get('sigla');
        $this->assertEquals('sigla', $sigla->getName());
        $this->assertEquals('text', $sigla->getAttribute('type'));

    }

    /**
     * testa a tela de alteracoes de um registro
     */
    public function testTipoTurmaSaveActionUpdateFormRequest()
    {
        $entity = $this->buildTipoTurma();
        $this->em->persist($entity);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $entity->getId());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // testa se recebeu um ViewModel
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();
        //verifica se existe um form
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];

        // testa os itens do formulario
        $id = $form->get('id');
        $nome = $form->get('nome');
        $sigla = $form->get('sigla');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($entity->getNome(), $nome->getValue());
        $this->assertEquals($entity->getSigla(), $sigla->getValue());
    }

    /**
     * testa a inclusao de um novo registro
     */
    public function testTipoTurmaSaveActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Tipo de Turma');
        $this->request->getPost()->set('sigla', 'TT');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tipo-turma', $headers->get('Location'));
    }

    /**
     * testa o update
     */
    public function testTipoTurmaUpdateAction()
    {
        $entity = $this->buildTipoTurma();
        $this->em->persist($entity);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $entity->getId());
        $this->request->getPost()->set('nome', 'Novo nome');
        $this->request->getPost()->set('sigla', 'NN');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tipo-turma', $headers->get('Location'));

        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('Novo nome', $savedEntity->getNome());
        $this->assertEquals('NN', $savedEntity->getSigla());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testTipoTurmaSaveActionInvalidFormPostRequest()
    {
        $entity = $this->buildTipoTurma();
        $this->em->persist($entity);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('sigla', '');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina nao redireciona por causa do erro entao o status = 200
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();

        // verify filters validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs['nome']['isEmpty']);
    }

    /**
     * testa a busca com resultados
     */
    public function testTipoTurmaBuscaPostActionRequest()
    {
        $entityA = $this->buildTipoTurma();
        $entityB = $this->buildTipoTurma();
        $entityB->setNome('Outra Turma');
        $this->em->persist($entityA);
        $this->em->persist($entityB);
        $this->em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', 'Outra Turma');

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

        // faz a comparação dos dados
        $dados = $variables["dados"];
        $this->assertEquals($entityB->getNome(), $dados[0]->getNome());
    }

    /**
     * testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testTipoTurmaInvalidDeleteAction()
    {
        // Dispara a acao
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
    public function testTipoTurmaDeleteAction()
    {
        $entity = $this->buildTipoTurma();
        $this->em->persist($entity);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $entity->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tipo-turma', $headers->get('Location'));
    }

    /**
     * testa a tela de detalhes
     */
    public function testTipoTurmaDetalhesAction()
    {
        $entity = $this->buildTipoTurma();
        $this->em->persist($entity);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $entity->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // testa os dados da View
        $variables = $result->getVariables();
        $this->assertArrayHasKey('data', $variables);

        // faz a comparacao dos dados
        $data = $variables["data"];
        $this->assertEquals($entity->getNome(), $data->getNome());
    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testTipoTurmaDetalhesInvalidIdAction()
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
    public function testTipoTurmaInvalidIdDeleteAction()
    {
        $entity = $this->buildTipoTurma();

        $this->em->persist($entity);
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
            'Location: /escola/tipo-turma', $headers->get('Location')
        );
    }

    private function buildTipoTurma()
    {
        $entity = new TipoTurma();
        $entity->setNome('Tipo de Turma');
        $entity->setSigla('TT');

        return $entity;
    }
}