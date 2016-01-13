<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 13/01/16
 * Time: 19:25
 */
use Escola\Entity\AreaConhecimento;

/**
 * @group Controller
 */
class AreaConhecimentoControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string AreaConhecimentoController
     */
    protected $controllerFQDN = 'Escola\Controller\AreaConhecimentoController';

    /**
     * Nome da rota. geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testAreaConhecimentoIndexAction()
    {
        $areaA = $this->buildAreaConhecimento();
        $areaB = $this->buildAreaConhecimento();
        $areaB->setNome('Ciências B');
        $this->em->persist($areaA);
        $this->em->persist($areaB);
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
        $this->assertEquals($areaA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($areaB->getNome(), $paginator->getItem(2)->getNome());

    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testAreaConhecimentoSaveActionNewRequest()
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

        $secao = $form->get('secao');
        $this->assertEquals('secao', $secao->getName());
        $this->assertEquals('text', $secao->getAttribute('type'));
    }

    /**
     * testa a tela de alteracao de um registro
     */
    public function testAreaConhecimentoSaveActionUpdateFormRequest()
    {
        $area = $this->buildAreaConhecimento();
        $this->em->persist($area);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $area->getId());
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
        $this->assertEquals($area->getId(), $id->getValue());
        $this->assertEquals($area->getNome(), $nome->getValue());
    }

    /**
     * Testa a inclusao de um novo registro
     */
    public function testAreaConhecimentoSaveActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Ciências');
        $this->request->getPost()->set('secao', 'Matemática');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/area-conhecimento', $headers->get('Location'));
    }

    /**
     * Testa o update de um registro
     */
    public function testAreaConhecimentoUpdateAction()
    {
        $area = $this->buildAreaConhecimento();
        $this->em->persist($area);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $area->getId());
        $this->request->getPost()->set('nome', 'Outro Nome');
        $this->request->getPost()->set('secao', $area->getSecao());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, estao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/area-conhecimento', $headers->get('Location'));

        $savedArea = $this->em->find(get_class($area), $area->getId());
        $this->assertEquals('Outro Nome', $savedArea->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testAreaConhecimentoSaveActionInvalidFormPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('secao', 'seçao');

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
    public function testAreaConhecimentoBuscaPostActionRequest()
    {
        $areaA = $this->buildAreaConhecimento();
        $areaB = $this->buildAreaConhecimento();
        $areaB->setNome('GOLD');
        $this->em->persist($areaA);
        $this->em->persist($areaB);
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
        $this->assertEquals($areaB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testAreaConhecimentoInvalidDeleteAction()
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
    public function testAreaConhecimentoDeleteAction()
    {
        $area = $this->buildAreaConhecimento();
        $this->em->persist($area);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $area->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/area-conhecimento', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testAreaConhecimentoDetalhesAction()
    {
        $area = $this->buildAreaConhecimento();
        $this->em->persist($area);
        $this->em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $area->getId());

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
        $this->assertEquals($area->getNome(), $data->getNome());
    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testAreaConhecimentoDetalhesInvalidIdAction()
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
    public function testAreaConhecimentoInvalidIdDeleteAction()
    {
        $area = $this->buildAreaConhecimento();
        $this->em->persist($area);
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
            'Location: /escola/area-conhecimento', $headers->get('Location')
        );
    }

    private function buildAreaConhecimento()
    {
        $area = new AreaConhecimento();
        $area->setNome('Ciências da Natureza');
        $area->setSecao('Lógico Matemático');

        return $area;
    }

}