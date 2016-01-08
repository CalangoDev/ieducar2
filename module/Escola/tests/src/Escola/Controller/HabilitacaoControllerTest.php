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
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testHabilitacaoSaveActionNewRequest()
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

        // verifica se exite um form
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
     * testa a tela de alteracao de um registro
     */
    public function testHabilitacaoSaveActionUpdateFormRequest()
    {
        $habilitacao = $this->buildHabilitacao();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($habilitacao);
        $em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $habilitacao->getId());
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
        $this->assertEquals($habilitacao->getId(), $id->getValue());
        $this->assertEquals($habilitacao->getNome(), $nome->getValue());
    }

    /**
     * Testa a inclusao de um novo registro
     */
    public function testHabilitacaoSaveActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Habilitação');
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
        $this->assertEquals('Location: /escola/habilitacao', $headers->get('Location'));
    }

    /**
     * Testa o update de um registro
     */
    public function testHabilitacaoUpdateAction()
    {
        $habilitacao = $this->buildHabilitacao();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($habilitacao);
        $em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $habilitacao->getId());
        $this->request->getPost()->set('nome', 'Outro Nome');
        $this->request->getPost()->set('descricao', $habilitacao->getDescricao());
        $this->request->getPost()->set('ativo', $habilitacao->getAtivo());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, estao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/habilitacao', $headers->get('Location'));

        $savedHabilitacao = $em->find(get_class($habilitacao), $habilitacao->getId());
        $this->assertEquals('Outro Nome', $savedHabilitacao->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testHabilitacaoSaveActionInvalidFormPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('descricao', 'Descrição');
        $this->request->getPost()->set('ativo', true);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina nao redirecionao por causa do erro, entao o status = 200
        $this->assertEquals(200, $response->getStatusCode());

        // verify filters validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs['nome']['isEmpty']);
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

    private function buildHabilitacao()
    {
        $habilitacao = new Habilitacao();
        $habilitacao->setNome('Habilitacao 1');
        $habilitacao->setDescricao('Descricao');

        return $habilitacao;
    }

}