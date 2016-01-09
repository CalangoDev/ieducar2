<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/01/16
 * Time: 23:03
 */
use Escola\Entity\TabelaArredondamento;

/**
 * @group Controller
 */
class TabelaArredondamentoTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string TabelaArredondamentoController
     */
    protected $controllerFQDN = 'Escola\Controller\TabelaArredondamentoController';

    /**
     * Nome da rota, geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * Testa a pagina inicial, listando os dados
     */
    public function testTabelaArredondamentoIndexAction()
    {
        $tabelaA = $this->buildTabelaArredondamento();
        $tabelaB = $this->buildTabelaArredondamento();
        $tabelaB->setNome('Outra Tabela');
        $this->em->persist($tabelaA);
        $this->em->persist($tabelaB);
        $this->em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'index');
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

        $this->assertArrayHasKey('dados', $variables);

        // faz a comparacao dos dados
        $paginator = $variables['dados'];
        $this->assertEquals($tabelaA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($tabelaB->getNome(), $paginator->getItem(2)->getNome());
    }

    /**
     * Testa a tela de inclusao de um novo registro
     *
     * @return void
     */
    public function testTabelaArredondamentoSaveActionNewRequest()
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

        $tipoNota = $form->get('tipoNota');
        $this->assertEquals('tipoNota', $tipoNota->getName());
        $this->assertEquals('Zend\Form\Element\Select', $tipoNota->getAttribute('type'));

        $notas = $form->get('notas');
        $this->assertEquals('notas', $notas->getName());
        $this->assertEquals('Zend\Form\Element\Collection', $notas->getAttribute('type'));
    }

    /**
     * testa a tela de alteracoes de um registro
     */
    public function testTabelaArredondamentoSaveActionUpdateFormRequest()
    {
        $tabela = $this->buildTabelaArredondamento();
        $this->em->persist($tabela);
        $this->em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $tabela->getId());
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
        $tipoNota = $form->get('tipoNota');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($tabela->getId(), $id->getValue());
        $this->assertEquals($tabela->getNome(), $nome->getValue());
        $this->assertEquals($tabela->getTipoNota(), $tipoNota->getValue());
    }

    /**
     * Testa a inclusao de um novo registro
     */
    public function testTabelaArredondamentoSaveActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Nova Tabela');
        $this->request->getPost()->set('tipoNota', 1);
        $notas = array(array(
            'id' => '',
            'nome' => 'A',
            'descricao' => 'Descricao',
            'valorMaximo' => '10',
            'valorMinimo' => '8'
        ));
        $this->request->getPost()->set('notas', $notas);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tabela-arredondamento', $headers->get('Location'));
    }

    /**
     * Testa o update de um registro
     */
    public function testTabelaArredondamentoUpdateAction()
    {
        $tabela = $this->buildTabelaArredondamento();
        $this->em->persist($tabela);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $tabela->getId());
        $this->request->getPost()->set('nome', 'Outro Nome');
        $this->request->getPost()->set('tipoNota', $tabela->getTipoNota());
        $notas = array(array(
            'id' => '',
            'nome' => 'A',
            'descricao' => 'Descricao',
            'valorMaximo' => '10',
            'valorMinimo' => '8'
        ));
        $this->request->getPost()->set('notas', $notas);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tabela-arredondamento', $headers->get('Location'));

        $savedTabela = $this->em->find(get_class($tabela), $tabela->getId());
        $this->assertEquals('Outro Nome', $savedTabela->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testTabelaArredondamentoSaveActionInvalidFormPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('tipoNota', 1);
        $notas = array(array(
            'id' => '',
            'nome' => '',
            'descricao' => '',
            'valorMaximo' => '',
            'valorMinimo' => ''
        ));
        $this->request->getPost()->set('notas', $notas);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();

        //	Verify Filters Validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs["nome"]['isEmpty']);
    }

    /**
     * testa a busca com resultados
     */
    public function testTabelaArredondamentoBuscaPostActionRequest()
    {
        // cria as tabelas
        $tabelaA = $this->buildTabelaArredondamento();
        $tabelaB = $this->buildTabelaArredondamento();
        $tabelaB->setNome('Outra Tabela');
        $this->em->persist($tabelaA);
        $this->em->persist($tabelaB);
        $this->em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', 'Outra Tabela');

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
        $dados = $variables['dados'];
        $this->assertEquals($tabelaB->getNome(), $dados[0]->getNome());
    }

    /**
     * testa a exclusao sem passar o id do registro
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testTabelaArredondamentoInvalidDeleteAction()
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
     * testa a exclusao
     */
    public function testTabelaArredondamentoDeleteAction()
    {
        $tabela = $this->buildTabelaArredondamento();
        $this->em->persist($tabela);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $tabela->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/tabela-arredondamento', $headers->get('Location'));
    }

    /**
     * testa a tela de detalhes
     */
    public function testTabelaArredondamentoDetalhesAction()
    {
        $tabela = $this->buildTabelaArredondamento();
        $this->em->persist($tabela);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $tabela->getId());

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
        $this->assertEquals($tabela->getNome(), $data->getNome());
    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testTabelaArredondamentoDetalhesInvalidIdAction()
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
    public function testTabelaArredondamentoInvalidIdDeleteAction()
    {
        $tabela = $this->buildTabelaArredondamento();
        $this->em->persist($tabela);
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
            'Location: /escola/tabela-arredondamento', $headers->get('Location')
        );
    }

    /**
     * @return TabelaArredondamento
     */
    private function buildTabelaArredondamento()
    {
        $tabelaArredondamento = new TabelaArredondamento();
        $tabelaArredondamento->setNome('Tabela X');
        $tabelaArredondamento->setTipoNota(1);

        return $tabelaArredondamento;
    }
}