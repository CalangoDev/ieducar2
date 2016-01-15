<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 21:39
 */
use Escola\Entity\ComponenteCurricular;

/**
 * @group Controller
 */
class ComponenteCurricularControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string ComponenteCurricularController
     */
    protected $controllerFQDN = 'Escola\Controller\ComponenteCurricularController';

    /**
     * Nome da rota. geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testComponenteCurricularIndexAction()
    {
        $componenteA = $this->buildComponenteCurricular();
        $componenteB = $this->buildComponenteCurricular();
        $componenteB->setNome('Medio');
        $this->em->persist($componenteA);
        $this->em->persist($componenteB);
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
        $this->assertEquals($componenteA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($componenteB->getNome(), $paginator->getItem(2)->getNome());

    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testComponenteCurricularSaveActionNewRequest()
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

        $abreviatura = $form->get('abreviatura');
        $this->assertEquals('abreviatura', $abreviatura->getName());
        $this->assertEquals('text', $abreviatura->getAttribute('type'));

        $tipoBase = $form->get('tipoBase');
        $this->assertEquals('tipoBase', $tipoBase->getName());
        $this->assertEquals('Zend\Form\Element\Radio', $tipoBase->getAttribute('type'));

        $areaConhecimento = $form->get('areaConhecimento');
        $this->assertEquals('areaConhecimento', $areaConhecimento->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $areaConhecimento->getAttribute('type'));

    }

    /**
     * testa a tela de alteracao de um registro
     */
    public function testComponenteCurricularSaveActionUpdateFormRequest()
    {
        $componente = $this->buildComponenteCurricular();
        $this->em->persist($componente);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $componente->getId());
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
        $this->assertEquals($componente->getId(), $id->getValue());
        $this->assertEquals($componente->getNome(), $nome->getValue());
    }

    /**
     * Testa a inclusao de um novo registro
     */
    public function testComponenteCurricularSaveActionPostRequest()
    {

        $areaConhecimento = $this->buildAreaConhecimento();
        $this->em->persist($areaConhecimento);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Tipo de dispensa');
        $this->request->getPost()->set('abreviatura', 'Td');
        $this->request->getPost()->set('tipoBase', 1);
        $this->request->getPost()->set('areaConhecimento', $areaConhecimento->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/componente-curricular', $headers->get('Location'));
    }

    /**
     * Testa o update de um registro
     */
    public function testComponenteCurricularUpdateAction()
    {
        $componente = $this->buildComponenteCurricular();
        $this->em->persist($componente);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $componente->getId());
        $this->request->getPost()->set('nome', 'Outro Nome');
        $this->request->getPost()->set('abreviatura', $componente->getAbreviatura());
        $this->request->getPost()->set('tipoBase', $componente->getTipoBase());
        $this->request->getPost()->set('areaConhecimento', $componente->getAreaConhecimento()->getId());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, estao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/componente-curricular', $headers->get('Location'));

        $savedComponente = $this->em->find(get_class($componente), $componente->getId());
        $this->assertEquals('Outro Nome', $savedComponente->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testComponenteCurricularSaveActionInvalidFormPostRequest()
    {
        $areaConhecimento = $this->buildAreaConhecimento();
        $this->em->persist($areaConhecimento);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('abreviatura', 'Abre');
        $this->request->getPost()->set('tipoBase', '1');
        $this->request->getPost()->set('areaConhecimento', $areaConhecimento->getId());

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
    public function testComponenteCurricularBuscaPostActionRequest()
    {
        $componenteA = $this->buildComponenteCurricular();
        $componenteB = $this->buildComponenteCurricular();
        $componenteB->setNome('GOLD');
        $this->em->persist($componenteA);
        $this->em->persist($componenteB);
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
        $this->assertEquals($componenteB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testComponenteCurricularInvalidDeleteAction()
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
    public function testComponenteCurricularDeleteAction()
    {
        $componente = $this->buildComponenteCurricular();
        $this->em->persist($componente);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $componente->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/componente-curricular', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testComponenteCurricularDetalhesAction()
    {
        $componente = $this->buildComponenteCurricular();
        $this->em->persist($componente);
        $this->em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $componente->getId());

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
        $this->assertEquals($componente->getNome(), $data->getNome());
    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testComponenteCurricularDetalhesInvalidIdAction()
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
    public function testComponenteCurricularInvalidIdDeleteAction()
    {
        $componente = $this->buildComponenteCurricular();
        $this->em->persist($componente);
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
            'Location: /escola/componente-curricular', $headers->get('Location')
        );
    }

    private function buildAreaConhecimento()
    {
        $area = new \Escola\Entity\AreaConhecimento();
        $area->setNome('Nome da Area');
        $area->setSecao('Seção da area');

        return $area;
    }

    private function buildComponenteCurricular()
    {
        $componente = new ComponenteCurricular();
        $componente->setNome('Integral');
        $componente->setAbreviatura('Int');
        $componente->setTipoBase(1);
        $area = $this->buildAreaConhecimento();
        $componente->setAreaConhecimento($area);

        return $componente;
    }

}