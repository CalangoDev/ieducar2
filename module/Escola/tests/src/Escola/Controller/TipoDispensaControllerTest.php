<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 16:26
 */
use Escola\Entity\TipoDispensa;

/**
 * @group Controller
 */
class TipoDispensaControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string TipoDispensaController
     */
    protected $controllerFQDN = 'Escola\Controller\TipoDispensaController';

    /**
     * Nome da rota. geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testTipoDispensaIndexAction()
    {
        $tipoA = $this->buildTipoDispensa();
        $tipoB = $this->buildTipoDispensa();
        $tipoB->setNome('Medio');
        $this->em->persist($tipoA);
        $this->em->persist($tipoB);
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
        $this->assertEquals($tipoA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($tipoB->getNome(), $paginator->getItem(2)->getNome());

    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testTipoDispensaSaveActionNewRequest()
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
    public function testTipoDispensaSaveActionUpdateFormRequest()
    {
        $tipo = $this->buildTipoDispensa();
        $this->em->persist($tipo);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $tipo->getId());
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
        $this->assertEquals($tipo->getId(), $id->getValue());
        $this->assertEquals($tipo->getNome(), $nome->getValue());
    }

    /**
     * Testa a inclusao de um novo registro
     */
    public function testTipoDispensaSaveActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Tipo de dispensa');
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
        $this->assertEquals('Location: /escola/tipo-dispensa', $headers->get('Location'));
    }

    /**
     * Testa o update de um registro
     */
    public function testTipoDispensaUpdateAction()
    {
        $tipo = $this->buildTipoDispensa();
        $this->em->persist($tipo);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $tipo->getId());
        $this->request->getPost()->set('nome', 'Outro Nome');
        $this->request->getPost()->set('descricao', $tipo->getDescricao());
        $this->request->getPost()->set('ativo', $tipo->getAtivo());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, estao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tipo-dispensa', $headers->get('Location'));

        $savedTipo = $this->em->find(get_class($tipo), $tipo->getId());
        $this->assertEquals('Outro Nome', $savedTipo->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testTipoDispensaSaveActionInvalidFormPostRequest()
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
    public function testTipoDispensaBuscaPostActionRequest()
    {
        $tipoA = $this->buildTipoDispensa();
        $tipoB = $this->buildTipoDispensa();
        $tipoB->setNome('GOLD');
        $this->em->persist($tipoA);
        $this->em->persist($tipoB);
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
        $this->assertEquals($tipoB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testTipoDispensaInvalidDeleteAction()
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
    public function testTipoDispensaDeleteAction()
    {
        $tipo = $this->buildTipoDispensa();
        $this->em->persist($tipo);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $tipo->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tipo-dispensa', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testTipoDispensaDetalhesAction()
    {
        $tipo = $this->buildTipoDispensa();
        $this->em->persist($tipo);
        $this->em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $tipo->getId());

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
        $this->assertEquals($tipo->getNome(), $data->getNome());
    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testTipoDispensaDetalhesInvalidIdAction()
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
    public function testTipoDispensaInvalidIdDeleteAction()
    {
        $tipo = $this->buildTipoDispensa();
        $this->em->persist($tipo);
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
            'Location: /escola/tipo-dispensa', $headers->get('Location')
        );
    }

    private function buildTipoDispensa()
    {
        $tipo = new TipoDispensa();
        $tipo->setNome('Integral');
        $tipo->setDescricao('Descrição da Dispensa');
        $tipo->setAtivo(true);

        return $tipo;
    }

}