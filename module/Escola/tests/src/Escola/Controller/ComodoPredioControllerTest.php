<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/05/16
 * Time: 20:21
 */
use Escola\Entity\ComodoPredio;

/**
 * @group Controller
 */
class ComodoPredioControllerTest extends \Core\Test\ControllerTestCase
{

    /**
     * Namespace completa do controller
     * @var string PredioController
     */
    protected $controllerFQDN = 'Escola\Controller\ComodoPredioController';

    /**
     * Nome da rota, geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /***
     * testa a pagina inicial, listando os dados
     * @return void
     */
    public function testComodoPredioIndexAction()
    {
        $rowA = $this->buildComodoPredio();
        $rowB = $this->buildComodoPredio();
        $rowB->setNome('Outro Nome');
        $this->em->persist($rowA);
        $this->em->persist($rowB);
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
        $this->assertEquals($rowA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($rowB->getNome(), $paginator->getItem(2)->getNome());
    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testComodoPredioSaveActionNewRequest()
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

        $area = $form->get('area');
        $this->assertEquals('area', $area->getName());
        $this->assertEquals('text', $area->getAttribute('type'));

        $ativo = $form->get('ativo');
        $this->assertEquals('ativo', $ativo->getName());
        $this->assertEquals('Zend\Form\Element\Select', $ativo->getAttribute('type'));

        $comodoFuncao = $form->get('comodoFuncao');
        $this->assertEquals('comodoFuncao', $comodoFuncao->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $comodoFuncao->getAttribute('type'));

        $predio = $form->get('predio');
        $this->assertEquals('predio', $predio->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $predio->getAttribute('type'));
    }

    /**
     * testa a tela de alteracoes de um registro
     * @return void
     */
    public function testComodoPredioSaveActionUpdateFormRequest()
    {
        $entity = $this->buildComodoPredio();
        $this->em->persist($entity);
        $this->em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $entity->getId());
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
        $ativo = $form->get('ativo');
        $area = $form->get('area');
        $comodoFuncao = $form->get('comodoFuncao');
        $predio = $form->get('predio');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($entity->getId(), $id->getValue());
        $this->assertEquals($entity->getNome(), $nome->getValue());
        $this->assertEquals($entity->getDescricao(), $descricao->getValue());
        $this->assertEquals($entity->isAtivo(), $ativo->getValue());
        $this->assertEquals($entity->getArea(), $area->getValue());
        $this->assertEquals($entity->getComodoFuncao()->getId(), $comodoFuncao->getValue());
        $this->assertEquals($entity->getPredio()->getId(), $predio->getValue());
    }

    /**
     * testa a inclusao de um novo registro
     * @return void
     */
    public function testComodoPredioSaveActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Nome');
        $this->request->getPost()->set('area', '1000');
        $this->request->getPost()->set('descricao', 'Descrição');
        $this->request->getPost()->set('ativo', true);
        $comodoFuncao = $this->buildComodoFuncao();
        $predio = $this->buildComodoPredio();
        $this->em->persist($comodoFuncao);
        $this->em->persist($predio);
        $this->em->flush();
        $this->request->getPost()->set('comodoFuncao', $comodoFuncao->getId());
        $this->request->getPost()->set('predio', $predio->getId());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/comodo-predio', $headers->get('Location'));
    }


    /**
     * testa o update de um registro
     */
    public function testComodoPredioUpdateAction()
    {
        $entity = $this->buildComodoPredio();
        $this->em->persist($entity);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $entity->getId());
        $this->request->getPost()->set('nome', 'Outro Nome');
        $this->request->getPost()->set('area', $entity->getArea());
        $this->request->getPost()->set('descricao', $entity->getDescricao());
        $this->request->getPost()->set('ativo', $entity->isAtivo());
        $this->request->getPost()->set('comodoFuncao', $entity->getComodoFuncao()->getId());
        $this->request->getPost()->set('predio', $entity->getPredio()->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/comodo-predio', $headers->get('Location'));

        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('Outro Nome', $savedEntity->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testComodoPredioSaveActionInvalidFormPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('descricao', '');
        $this->request->getPost()->set('area', '');
        $this->request->getPost()->set('comodoFuncao', '');
        $this->request->getPost()->set('predio', '');
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
    public function testComodoPredioPostActionRequest()
    {
        $rowA = $this->buildComodoPredio();
        $rowB = $this->buildComodoPredio();
        $rowB->setNome('Outro Nome');
        $this->em->persist($rowA);
        $this->em->persist($rowB);
        $this->em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', 'Outro Nome');

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
        $this->assertEquals($rowB->getNome(), $dados[0]->getNome());
    }

    /**
     * testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMesage Código Obrigatório
     */
    public function testComodoPredioInvalidDeleteAction()
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
    public function testComodoPredioDeleteAction()
    {
        $entity = $this->buildComodoPredio();
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
        $this->assertEquals('Location: /escola/comodo-predio', $headers->get('Location'));
    }


    /**
     * Testa a tela de detalhes
     */
    public function testComodoPredioDetalhesAction()
    {
        $entity = $this->buildComodoPredio();

        $this->em->persist($entity);
        $this->em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $entity->getId());

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
        $this->assertEquals($entity->getNome(), $data->getNome());

    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testComodoPredioDetalhesInvalidIdAction()
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
    public function testComodoPredioInvalidIdDeleteAction()
    {
        $entity = $this->buildComodoPredio();
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
            'Location: /escola/comodo-predio', $headers->get('Location')
        );
    }

    private function buildInstituicao()
    {
        $instituicao = new \Escola\Entity\Instituicao();
        $instituicao->setNome('Prefeitura Municipal de Irecê');
        $instituicao->setResponsavel('Secretaria de Educação');

        return $instituicao;
    }

    private function buildRedeEnsino()
    {
        $rede = new \Escola\Entity\RedeEnsino();
        $rede->setNome('Muincipal');
        $instituicao = $this->buildInstituicao();
        $rede->setInstituicao($instituicao);

        return $rede;
    }

    private function buildLocalizacao()
    {
        $localizacao = new \Escola\Entity\Localizacao();
        $localizacao->setNome('Urbana');

        return $localizacao;
    }

    private function buildJuridica()
    {
        $juridica = new \Usuario\Entity\Juridica();
        $juridica->setNome('Escola Modelo');
        $juridica->setFantasia('Escola Modelo');
        $juridica->setSituacao('A');

        return $juridica;
    }

    private function buildEscola()
    {
        $escola = new \Escola\Entity\Escola();
        $escola->setAtivo(true);
        $escola->setBloquearLancamento(false);
        $escola->setCodigoInep('12345678');
        $escola->setSigla('EM');
        $juridica = $this->buildJuridica();
        $escola->setJuridica($juridica);
        $localizacao = $this->buildLocalizacao();
        $escola->setLocalizacao($localizacao);
        $rede = $this->buildRedeEnsino();
        $escola->setRedeEnsino($rede);

        return $escola;
    }

    private function buildPredio()
    {
        $entity = new \Escola\Entity\Predio();
        $entity->setNome('Nome Predio');
        $entity->setDescricao('Descrição');
        $entity->setEndereco('End');
        $entity->setAtivo(true);
        $escola = $this->buildEscola();
        $entity->setEscola($escola);

        return $entity;
    }

    /**
     * @return ComodoFuncao
     */
    private function buildComodoFuncao()
    {
        $entity = new \Escola\Entity\ComodoFuncao();
        $entity->setNome('Funcao');
        $entity->setDescricao('Descrição');
        $entity->setAtivo(true);

        return $entity;
    }

    /**
     * @return ComodoPredio
     */
    private function buildComodoPredio()
    {
        $entity = new \Escola\Entity\ComodoPredio();
        $entity->setNome('Nome');
        $entity->setDescricao('Descrição');
        $entity->setAtivo(true);
        $entity->setArea('100');
        $comodoFuncao = $this->buildComodoFuncao();
        $entity->setComodoFuncao($comodoFuncao);
        $predio = $this->buildPredio();
        $entity->setPredio($predio);

        return $entity;
    }
}