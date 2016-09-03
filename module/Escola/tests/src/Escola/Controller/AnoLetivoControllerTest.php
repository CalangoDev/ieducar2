<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 21/06/16
 * Time: 11:39
 */
use Escola\Entity\AnoLetivo;

/**
 * @group Controller
 */
class AnoLetivoControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string AreaConhecimentoController
     */
    protected $controllerFQDN = 'Escola\Controller\AnoLetivoController';

    /**
     * Nome da rota. geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testAnoLetivoIndexAction()
    {
        $rowA = $this->buildAnoLetivo();
        $rowB = $this->buildAnoLetivo();
        $rowB->setAno(2017);
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
        $this->assertEquals($rowA->getAno(), $paginator->getItem(1)->getAno());
        $this->assertEquals($rowB->getAno(), $paginator->getItem(2)->getAno());
    }

    /**
     * Testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testAnoLetivoSaveActionNewRequest()
    {

        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('escola', $escola->getId());
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

        $ano = $form->get('ano');
        $this->assertEquals('ano', $ano->getName());
        $this->assertEquals('Zend\Form\Element\Select', $ano->getAttribute('type'));

        $escola = $form->get('escola');
        $this->assertEquals('escola', $escola->getName());
        $this->assertEquals('hidden', $escola->getAttribute('type'));

        $anoLetivoModulos = $form->get('anoLetivoModulos');
        $this->assertEquals('anoLetivoModulos', $anoLetivoModulos->getName());
        $this->assertEquals('Zend\Form\Element\Collection', $anoLetivoModulos->getAttribute('type'));
    }

    /**
     * test aa tela de alteracoes de um registro
     * @return void
     */
    public function testAnoLetivoSaveActionUpdateFormRequest()
    {
        $entity = $this->buildAnoLetivo();
        $escola = $this->buildEscola();
        $entity->setEscola($escola);
        $this->em->persist($entity);
        $this->em->flush();
        
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $entity->getId());
        $this->routeMatch->setParam('escola', $entity->getEscola()->getId());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        
        // verifica a resposta
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();

        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        
        // testa os itens do formulario
        $id = $form->get('id');
        $ano = $form->get('ano');
        $escola = $form->get('escola');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($entity->getId(), $id->getValue());
        $this->assertEquals($entity->getAno(), $ano->getValue());
        $this->assertEquals($entity->getEscola()->getId(), $escola->getValue());
    }

    /**
     * testa a inclusao de um novo registro
     * @return void
     */
    public function testAnoLetivoSaveActionPostRequest()
    {
        // dispara a acao
        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush();
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('escola', $escola->getId());
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('ano', 2017);
        $this->request->getPost()->set('escola', $escola->getId());
        $modulo = $this->buildModulo();
        $anosLetivosModulos = array(array(
            'id' => '',
            'dataInicio' => '10/10/2016',
            'dataFim' => '10/12/2016',
            'modulo' => $modulo->getId(),
        ));
        $this->request->getPost()->set('anoLetivoModulos', $anosLetivosModulos);
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );


        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/escola', $headers->get('Location'));
    }

    public function testAnoLetivoUpdateAction()
    {
        $escola = $this->buildEscola();
        $entity = $this->buildAnoLetivo();
        $entity->setEscola($escola);
        $this->em->persist($entity);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('escola', $entity->getEscola()->getId());
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $entity->getId());
        $juridica = $this->buildJuridica();
        $juridica->setNome('Novo Nome');
        $escola->setJuridica($juridica);
        $this->request->getPost()->set('ano', 2018);
        $this->request->getPost()->set('escola', $entity->getEscola()->getId());
        $modulo = $this->buildModulo();
        $anosLetivosModulos = array(array(
            'id' => '',
            'dataInicio' => '10/10/2016',
            'dataFim' => '10/12/2016',
            'modulo' => $modulo->getId(),
        ));
        $this->request->getPost()->set('anoLetivoModulos', $anosLetivosModulos);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/escola', $headers->get('Location'));

        $savedEntity = $this->em->find(get_class($entity) ,$entity->getId());
        $this->assertEquals('Novo Nome', $savedEntity->getEscola()->getJuridica()->getNome());
        $this->assertEquals(2018, $savedEntity->getAno());

    }

    /**
     * testa a inclusao, formulario invalido e ano vazio
     */
    public function testAnoLetivoInvalidFormPostRequest()
    {
        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('escola', $escola->getId());
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('ano', '');
        $this->request->getPost()->set('escola', $escola->getId());
        $modulo = $this->buildModulo();
        $anosLetivosModulos = array(array(
            'id' => '',
            'dataInicio' => '10/10/2016',
            'dataFim' => '10/12/2016',
            'modulo' => $modulo->getId(),
        ));
        $this->request->getPost()->set('anoLetivoModulos', $anosLetivosModulos);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina nao redireciona por causa do erro, entao o status = 200
        $this->assertEquals(200, $response->getStatusCode());
        // verify filters validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('The input was not found in the haystack', $msgs['ano']['notInArray']);
    }

    /**
     * testa a busca com resultados
     */
    public function testAnoLetivoPostActionRequest()
    {
        $rowA = $this->buildAnoLetivo();
        $rowB = $this->buildAnoLetivo();
        $rowB->setAno(2018);
        $this->em->persist($rowA);
        $this->em->persist($rowB);
        $this->em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', '2018');

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
        $this->assertEquals($rowB->getAno(), $dados[0]->getAno());

    }


    /**
     * testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMesage Código Obrigatório
     */
    public function testAnoLetivoInvalidDeleteAction()
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
    public function testAnoLetivoDeleteAction()
    {
        $entity = $this->buildAnoLetivo();
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
        $this->assertEquals('Location: /escola/escola', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testAnoLetivoDetalhesAction()
    {
        $entity = $this->buildAnoLetivo();

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
        $this->assertEquals($entity->getAno(), $data->getAno());

    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testAnoLetivoDetalhesInvalidIdAction()
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
    public function testAnoLetivoInvalidIdDeleteAction()
    {
        $entity = $this->buildAnoLetivo();
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
            'Location: /escola/ano-letivo', $headers->get('Location')
        );
    }

    private function buildAnoLetivo()
    {
        $entity = new AnoLetivo();
        $entity->setAndamento(1);
        $entity->setAno(2016);
        $entity->setTurmasPorAno(1);
        $escola = $this->buildEscola();
        $entity->setEscola($escola);

        return $entity;

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
        $entity = new \Escola\Entity\Escola();
        $entity->setAtivo(true);
        $entity->setBloquearLancamento(false);
        $entity->setCodigoInep('12345678');
        $entity->setSigla('EM');
        $juridica = $this->buildJuridica();
        $entity->setJuridica($juridica);
        $localizacao = $this->buildLocalizacao();
        $entity->setLocalizacao($localizacao);
        $rede = $this->buildRedeEnsino();
        $entity->setRedeEnsino($rede);

        return $entity;
    }

    private function buildModulo()
    {
        $entity = new \Escola\Entity\Modulo();
        $entity->setNome('Modulo Bimestre');
        $entity->setDescricao('Modulo bimestral');
        $entity->setNumeroMeses(8);
        $entity->setNumeroSemanas(200);

        return $entity;
    }
}