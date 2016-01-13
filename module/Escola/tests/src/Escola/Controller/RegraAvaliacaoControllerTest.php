<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 10/01/16
 * Time: 23:49
 */
use Escola\Entity\RegraAvaliacao;

/**
 * @group Controller
 */
class RegraAvaliacaoControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string RegraAvaliacaoController
     */
    protected $controllerFQDN = 'Escola\Controller\RegraAvaliacaoController';

    /**
     * Nome da rota. geralmente o nome do moulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inciial, listando os dados
     */
    public function testRegraAvaliacaoIndexAction()
    {
        $regraA = $this->buildRegraAvaliacao();
        $regraB = $this->buildRegraAvaliacao();
        $regraB->setNome('Outra Regra');
        $this->em->persist($regraA);
        $this->em->persist($regraB);
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
        $this->assertEquals($regraA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($regraB->getNome(), $paginator->getItem(2)->getNome());
    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testRegraAvaliacaoSaveActionNewRequest()
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
        $this->assertEquals('Zend\Form\Element\Radio', $tipoNota->getAttribute('type'));

        $tabelaArredondamento = $form->get('tabelaArredondamento');
        $this->assertEquals('tabelaArredondamento', $tabelaArredondamento->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $tabelaArredondamento->getAttribute('type'));

        $tipoProgressao = $form->get('tipoProgressao');
        $this->assertEquals('tipoProgressao', $tipoProgressao->getName());
        $this->assertEquals('Zend\Form\Element\Radio', $tipoProgressao->getAttribute('type'));

        $media = $form->get('media');
        $this->assertEquals('media', $media->getName());
        $this->assertEquals('text', $media->getAttribute('type'));

        $mediaRecuperacao = $form->get('mediaRecuperacao');
        $this->assertEquals('mediaRecuperacao', $mediaRecuperacao->getName());
        $this->assertEquals('text', $mediaRecuperacao->getAttribute('type'));

        $formulaMedia = $form->get('formulaMedia');
        $this->assertEquals('formulaMedia', $formulaMedia->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $formulaMedia->getAttribute('type'));

        $formulaRecuperacao = $form->get('formulaRecuperacao');
        $this->assertEquals('formulaRecuperacao', $formulaRecuperacao->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $formulaRecuperacao->getAttribute('type'));

        $porcentagemPresenca = $form->get('porcentagemPresenca');
        $this->assertEquals('porcentagemPresenca', $porcentagemPresenca->getName());
        $this->assertEquals('text', $porcentagemPresenca->getAttribute('type'));

        $parecerDescritivo = $form->get('parecerDescritivo');
        $this->assertEquals('parecerDescritivo', $parecerDescritivo->getName());
        $this->assertEquals('Zend\Form\Element\Radio', $parecerDescritivo->getAttribute('type'));

        $tipoPresenca = $form->get('tipoPresenca');
        $this->assertEquals('tipoPresenca', $tipoPresenca->getName());
        $this->assertEquals('Zend\Form\Element\Radio', $tipoPresenca->getAttribute('type'));

    }

    /**
     * testa a tela de alteracoes de um registro
     */
    public function testRegraAvaliacaoSaveActionUpdateFormRequest()
    {
        $regra = $this->buildRegraAvaliacao();
        $this->em->persist($regra);
        $this->em->flush();

        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $regra->getId());
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
        $this->assertEquals($regra->getId(), $id->getValue());
        $this->assertEquals($regra->getNome(), $nome->getValue());
        $this->assertEquals($regra->getTipoNota(), $tipoNota->getValue());
    }


    /**
     * Testa a inclusao de um novo registro
     */
    public function testRegraAvaliacaoSaveActionPostRequest()
    {
        $tabela = $this->buildTabelaArredondamento();
        $this->em->persist($tabela);
        $formulaMedia = $this->buildFormulaMedia();
        $this->em->persist($formulaMedia);

        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Nova Regra');
        $this->request->getPost()->set('tipoNota', 1);
        $this->request->getPost()->set('tabelaArredondamento', $tabela->getId());
        $this->request->getPost()->set('tipoProgressao', 1);
        $this->request->getPost()->set('media', 6);
        $this->request->getPost()->set('mediaRecuperacao', 4);
        $this->request->getPost()->set('formulaMedia', $formulaMedia);
        $this->request->getPost()->set('formulaRecuperacao', $formulaMedia);
        $this->request->getPost()->set('porcentagemPresenca', '50');
        $this->request->getPost()->set('parecerDescritivo', 1);
        $this->request->getPost()->set('tipoPresenca', 1);


        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/regra-avaliacao', $headers->get('Location'));
    }

    /**
     * testa o update de um registro
     */
    public function testRegraAvaliacaoUpdateAction()
    {
        $regra = $this->buildRegraAvaliacao();
        $this->em->persist($regra);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $regra->getId());
        $this->request->getPost()->set('nome', 'Outro nome');
        $this->request->getPost()->set('tipoNota', $regra->getTipoNota());
        $this->request->getPost()->set('tabelaArredondamento', $regra->getTabelaArredondamento());
        $this->request->getPost()->set('tipoProgressao', $regra->getTipoProgressao());
        $this->request->getPost()->set('media', $regra->getMedia());
        $this->request->getPost()->set('mediaRecuperacao', $regra->getMediaRecuperacao());
        $this->request->getPost()->set('formulaMedia', $regra->getFormulaMedia());
        $this->request->getPost()->set('formulaRecuperacao', $regra->getFormulaRecuperacao());
        $this->request->getPost()->set('porcentagemPresenca', $regra->getPorcentagemPresenca());
        $this->request->getPost()->set('parecerDescritivo', $regra->getParecerDescritivo());
        $this->request->getPost()->set('tipoPresenca', $regra->getTipoPresenca());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/regra-avaliacao', $headers->get('Location'));

        $savedRegra = $this->em->find(get_class($regra), $regra->getId());
        $this->assertEquals('Outro nome', $savedRegra->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testRegraAvaliacaoSaveActionInvalidFormPostRequest()
    {
        $tabela = $this->buildTabelaArredondamento();
        $this->em->persist($tabela);
        $formulaMedia = $this->buildFormulaMedia();
        $this->em->persist($formulaMedia);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('tipoNota', 1);
        $this->request->getPost()->set('tabelaArredondamento', $tabela->getId());
        $this->request->getPost()->set('tipoProgressao', 1);
        $this->request->getPost()->set('media', '6');
        $this->request->getPost()->set('mediaRecuperacao', '5');
        $this->request->getPost()->set('formulaMedia', $formulaMedia->getId());
        $this->request->getPost()->set('formulaRecuperacao', $formulaMedia->getId());
        $this->request->getPost()->set('porcentagemPresenca', '40');
        $this->request->getPost()->set('parecerDescritivo', 1);
        $this->request->getPost()->set('tipoPresenca', 1);

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
        $this->assertEquals('Value is required and can\'t be empty', $msgs['nome']['isEmpty']);
    }

    /**
     * testa a busca com resultados
     */
    public function testRegraAvaliacaoPostActionRequest()
    {
        $regraA = $this->buildRegraAvaliacao();
        $regraB = $this->buildRegraAvaliacao();
        $regraB->setNome('GOLD');
        $this->em->persist($regraA);
        $this->em->persist($regraB);
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
        $this->assertEquals($regraB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testRegraAvaliacaoInvalidDeleteAction()
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
    public function testRegraAvaliacaoDeleteAction()
    {
        $regra = $this->buildRegraAvaliacao();
        $this->em->persist($regra);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $regra->getId());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/regra-avaliacao', $headers->get('Location'));
    }

    /***
     * testa a tela de detalhes
     */
    public function testRegraAvaliacaoDetalhesAction()
    {
        $regra = $this->buildRegraAvaliacao();
        $this->em->persist($regra);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $regra->getId());

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

        //	Faz a comparação dos dados
        $data = $variables["data"];
        $this->assertEquals($regra->getNome(), $data->getNome());
    }

    /**
     * testa visualizacao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testRegraAvaliacaoDetalhesInvalidIdAction()
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
    public function testRegraAvaliacaoInvalidIdDeleteAction()
    {
        $regra = $this->buildRegraAvaliacao();
        $this->em->persist($regra);
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
            'Location: /escola/regra-avaliacao', $headers->get('Location')
        );
    }

    /**
     * @return RegraAvaliacao
     */
    private function buildRegraAvaliacao()
    {
        $regra = new RegraAvaliacao();
        $regra->setNome('Nome da Regra');
        $regra->setTipoNota(1);
        $regra->setTipoProgressao(1);
        $regra->setMedia('10');
        $regra->setPorcentagemPresenca(45);
        $regra->setParecerDescritivo(1);
        $regra->setTipoPresenca(1);
        $regra->setMediaRecuperacao(50);
        $formulaMedia = $this->buildFormulaMedia();
        $regra->setFormulaMedia($formulaMedia);
        $regra->setFormulaRecuperacao($formulaMedia);
        $tabela = $this->buildTabelaArredondamento();
        $regra->setTabelaArredondamento($tabela);

        return $regra;

    }

    private function buildFormulaMedia()
    {
        $formula = new \Escola\Entity\FormulaMedia();
        $formula->setNome('Nome da formula');
        $formula->setTipoFormula(1);
        $formula->setFormulaMedia('Se');

        return $formula;
    }

    private function buildTabelaArredondamento()
    {
        $tabela = new \Escola\Entity\TabelaArredondamento();

        $tabela->setNome('Tabela de arredondamento');
        $tabela->setTipoNota(1);

        return $tabela;
    }
}