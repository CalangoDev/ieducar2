<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/12/15
 * Time: 09:48
 */
use Core\Test\ControllerTestCase;
use Escola\Entity\Instituicao;

/**
 * @group Controller
 */
class InstituicaoControllerTest extends ControllerTestCase
{
    /**
     * Namespace completa do Controller
     * @var string InstituicaoController
     */
    protected $controllerFQDN = 'Escola\Controller\InstituicaoController';

    /**
     * Nome da rota. geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando as instituicoes
     */
    public function testInstituicaoIndexAction()
    {
        $instituicaoA = $this->buildInstituicao();
        $instituicaoB = $this->buildInstituicao();
        $instituicaoB->setNome('Prefeitura Municipal X');

        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($instituicaoA);
        $em->persist($instituicaoB);
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
        $this->assertEquals($instituicaoA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($instituicaoB->getNome(), $paginator->getItem(2)->getNome());
    }

    /**
     * Testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testInstituicaoSaveActionNewRequest()
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

        $responsavel = $form->get('responsavel');
        $this->assertEquals('responsavel', $responsavel->getName());
        $this->assertEquals('text', $responsavel->getAttribute('type'));

        $enderecoExterno = $form->get('enderecoExterno');
        $this->assertEquals('enderecoExterno', $enderecoExterno->getName());

        $telefones = $form->get('telefones');
        $this->assertEquals('telefones', $telefones->getName());
        $this->assertEquals('Zend\Form\Element\Collection', $telefones->getAttribute('type'));

        $ativo = $form->get('ativo');
        $this->assertEquals('ativo', $ativo->getName());
        $this->assertEquals('Zend\Form\Element\Select', $ativo->getAttribute('type'));

    }

    /**
     * testa a tela de alteracoes de um registro
     */
    public function testInstituicaoSaveActionUpdateFormRequest()
    {
        $instituicao = $this->buildInstituicao();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($instituicao);
        $em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $instituicao->getId());
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
        $this->assertEquals($instituicao->getId(), $id->getValue());
        $this->assertEquals($instituicao->getNome(), $nome->getValue());
    }

    /**
     * Testa a inclusao de uma nova instituicao
     */
    public function testInstituicaoSaveActionPostRequest()
    {
        //	Dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Garrincha');
        $this->request->getPost()->set('responsavel', 'Secretaria');
        $enderecoExterno = array(
            'id' => '0',
            'cep' => '',
            'tipoLogradouro' => '0',
            'logradouro' => '',
            'numero' => '',
            'cidade' => '',
            'bairro' => '',
            'siglaUf' => '',
            'letra' => '',
            'apartamento' =>'',
            'bloco' => '',
            'andar' => '',
            'zonaLocalizacao' => ''
        );

        $this->request->getPost()->set('enderecoExterno', $enderecoExterno);
        $telefones = array(array(
            'id' => '',
            'ddd' => '74',
            'numero' => '12345678'
        )
        );
        $this->request->getPost()->set('telefones', $telefones);
        $this->request->getPost()->set('ativo', true);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/instituicao', $headers->get('Location'));
    }

    /**
     * Testa o update de uma instituicao
     */
    public function testInstituicaoUpdateAction()
    {
        $instituicao = $this->buildInstituicao();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($instituicao);
        $em->flush();
        //	Dispara a acao
        $this->routeMatch->setParam('action', 'save');

        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $instituicao->getId());
        $this->request->getPost()->set('nome', 'Prefeitura Municipal');
        $this->request->getPost()->set('responsavel', 'Secretaria');
        $enderecoExterno = array(
            'id' => '0',
            'cep' => '',
            'tipoLogradouro' => '0',
            'logradouro' => '',
            'numero' => '',
            'cidade' => '',
            'bairro' => '',
            'siglaUf' => '',
            'letra' => '',
            'apartamento' =>'',
            'bloco' => '',
            'andar' => '',
            'zonaLocalizacao' => ''
        );

        $this->request->getPost()->set('enderecoExterno', $enderecoExterno);
        $telefones = array(array(
            'id' => '',
            'ddd' => '74',
            'numero' => '12345678'
        )
        );
        $this->request->getPost()->set('telefones', $telefones);
        $this->request->getPost()->set('ativo', true);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/instituicao', $headers->get('Location'));

        $savedInstituicao = $em->find(get_class($instituicao), $instituicao->getId());
        $this->assertEquals('Prefeitura Municipal', $savedInstituicao->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testInstituicaoSaveActionInvalidFormPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('responsavel', 'Secretaria');
        $enderecoExterno = array(
            'id' => '0',
            'cep' => '',
            'tipoLogradouro' => '0',
            'logradouro' => '',
            'numero' => '',
            'cidade' => '',
            'bairro' => '',
            'siglaUf' => '',
            'letra' => '',
            'apartamento' =>'',
            'bloco' => '',
            'andar' => '',
            'zonaLocalizacao' => ''
        );

        $this->request->getPost()->set('enderecoExterno', $enderecoExterno);
        $telefones = array(array(
            'id' => '',
            'ddd' => '74',
            'numero' => '12345678'
        )
        );
        $this->request->getPost()->set('telefones', $telefones);
        $this->request->getPost()->set('ativo', true);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        //	a pagina nao redireciona por causa do erro, entao o status = 200
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();

        //	Verify Filters Validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs["nome"]['isEmpty']);
    }


    /**
     * Testa a busca com resultados
     */
    public function testInstituicaoBuscaPostActionRequest()
    {
        //	cria pessoas fisicas para testar
        $instituicaoA = $this->buildInstituicao();
        $instituicaoB = $this->buildInstituicao();
        $instituicaoB->setNome("GOLD");
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($instituicaoA);
        $em->persist($instituicaoB);
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
        $this->assertEquals($instituicaoB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id da pessoa
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testInstituicaoInvalidDeleteAction()
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
    public function testInstituicaoDeleteAction()
    {
        $instituicao = $this->buildInstituicao();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($instituicao);
        $em->flush();

        //	Dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $instituicao->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        //	Verifica a reposta
        $response = $this->controller->getResponse();

        //	A pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals(
            'Location: /escola/instituicao', $headers->get('Location')
        );
    }

    /**
     * Testa a tela de detalhes
     */
    public function testInstituicaoDetalhesAction()
    {
        $instituicao = $this->buildInstituicao();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($instituicao);
        $em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $instituicao->getId());

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
        $this->assertEquals($instituicao->getNome(), $data->getNome());

    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testInstituicaoDetalhesInvalidIdAction()
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
    public function testInstituicaoInvalidIdDeleteAction()
    {
        $instituicao = $this->buildInstituicao();
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($instituicao);
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
            'Location: /escola/instituicao', $headers->get('Location')
        );
    }


    private function buildInstituicao()
    {
        $instituicao = new Instituicao();
        $instituicao->setNome('Prefeitura Municipal Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
    }

}