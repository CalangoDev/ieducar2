<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 30/04/16
 * Time: 14:42
 */
use Escola\Entity\SequenciaSerie;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Controller
 */
class SequenciaSerieControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string SequenciaSerieController
     */
    protected $controllerFQDN = 'Escola\Controller\SequenciaSerieController';

    /**
     * Nome da rota. geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testSequenciaSerieIndexAction()
    {
        $sequenciaA = $this->buildSequenciaSerie();
        $sequenciaB = $this->buildSequenciaSerie();
        $novaSerie = $this->buildSerie();
        $novaSerie->setNome('Agora Nova Serie');
        $this->em->persist($novaSerie);
        $this->em->flush($novaSerie);
        $sequenciaB->setSerieDestino($novaSerie);
        $this->em->persist($sequenciaA);
        $this->em->persist($sequenciaB);
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
        $this->assertEquals($sequenciaA->getSerieDestino(), $paginator->getItem(1)->getSerieDestino());
        $this->assertEquals($sequenciaB->getSerieDestino(), $paginator->getItem(2)->getSerieDestino());
    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testSequenciaSerieSaveActionNewRequest()
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

        $serieOrigem = $form->get('serieOrigem');
        $this->assertEquals('serieOrigem', $serieOrigem->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $serieOrigem->getAttribute('type'));

        $serieDestino = $form->get('serieDestino');
        $this->assertEquals('serieDestino', $serieDestino->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $serieDestino->getAttribute('type'));
    }

    /**
     * testa a tela de alteracao de um registro
     */
    public function testSequenciaSerieSaveActionUpdateFormRequest()
    {
        $sequencia = $this->buildSequenciaSerie();
        $this->em->persist($sequencia);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $sequencia->getId());
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
        $serieOrigem = $form->get('serieOrigem');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($sequencia->getId(), $id->getValue());
        $this->assertEquals($sequencia->getSerieOrigem()->getId(), $serieOrigem->getValue());
    }

    /**
     * Testa a incliusao de um novo registro
     */
    public function testSequenciaSerieActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $serieA = $this->buildSerie();
        $serieB = $this->buildSerie();
        $serieB->setNome('Outra serie');
        $this->em->persist($serieA);
        $this->em->persist($serieB);
        $this->em->flush();
        $this->request->getPost()->set('serieOrigem', $serieA->getId());
        $this->request->getPost()->set('serieDestino', $serieB->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 303
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/sequencia-serie', $headers->get('Location'));
    }

    /**
     * testa o update de um registro
     */
    public function testSequenciaSerieUpdateAction()
    {
        $sequencia = $this->buildSequenciaSerie();
        $this->em->persist($sequencia);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $sequencia->getId());
        $serieNova = $this->buildSerie();
        $serieNova->setNome('Outra Serie');
        $this->em->persist($serieNova);
        $this->em->flush();
        $this->request->getPost()->set('serieOrigem', $serieNova->getId());
        $this->request->getPost()->set('serieDestino', $sequencia->getSerieDestino()->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/sequencia-serie', $headers->get('Location'));

        $savedSequencia = $this->em->find(get_class($sequencia), $sequencia->getId());
        $this->assertEquals('Outra Serie', $savedSequencia->getSerieOrigem()->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testSequenciaSerieInvalidFormPostRequest()
    {
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('serieOrigem', '');
        $serie = $this->buildSerie();
        $this->em->persist($serie);
        $this->em->flush();
        $this->request->getPost()->set('serieDestino', 'bbb');
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina nao redireciona por causa do erro entao o status = 200
        $this->assertEquals(200, $response->getStatusCode());

        // verficy filters validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs['serieOrigem']['isEmpty']);
    }

    /**
     * testa a busca com resultados
     */
    public function testSequenciaSerieBuscaPostActionRequest()
    {
        $sequenciaA = $this->buildSequenciaSerie();
        $sequenciaB = $this->buildSequenciaSerie();
        $serie = $this->buildSerie();
        $serie->setNome('GOLD');
        $this->em->persist($serie);
        $this->em->flush();
        $sequenciaB->setSerieOrigem($serie);
        $this->em->persist($sequenciaA);
        $this->em->persist($sequenciaB);
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
        $this->assertEquals($sequenciaB->getSerieOrigem()->getNome(), $dados[0]->getSerieOrigem()->getNome());
    }

    /**
     * testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testSequenciaSerieInvalidDeleteAction()
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
    public function testSequenciaSerieDeleteAction()
    {
        $sequencia = $this->buildSequenciaSerie();
        $this->em->persist($sequencia);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $sequencia->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/sequencia-serie', $headers->get('Location'));
    }

    /**
     * testa a tela de detalhes
     */
    public function testSequenciaSerieDetalhesAction()
    {
        $sequencia = $this->buildSequenciaSerie();
        $this->em->persist($sequencia);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $sequencia->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        //	Testa se um ViewModel foi retornado
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);


        //	Testa os dados da View
        $variables = $result->getVariables();
        $this->assertArrayHasKey('data', $variables);

        //	Faz a comparação dos dados
        $data = $variables["data"];
        $this->assertEquals($sequencia->getSerieOrigem()->getNome(), $data->getSerieOrigem()->getNome());
    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testSequenciaSerieDetalhesInvalidIdAction()
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
    public function testSequenciaSerieInvalidIdDeleteAction()
    {
        $serie = $this->buildSerie();
        $this->em->persist($serie);
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
            'Location: /escola/sequencia-serie', $headers->get('Location')
        );
    }


    /**
     * @return Curso
     */
    private function buildCurso()
    {
        $curso = new \Escola\Entity\Curso();
        $curso->setNome('Curso Teste');
        $curso->setSigla('CT');
        $curso->setQuantidadeEtapa(4);
        $curso->setCargaHoraria(60.0);
        $curso->setAtoPoderPublico('ato');
        $curso->setObjetivo('Objetivo do curso');
        $curso->setPublicoAlvo('Infantil');
        $curso->setAtivo(true);
        $curso->setPadraoAnoEscolar(false);
        $curso->setHoraFalta(50.0);
        $curso->setMultiSeriado(0);

        $nivelEnsino = $this->buildNivelEnsino();
        $curso->setNivelEnsino($nivelEnsino);

        $tipoEnsino = $this->buildTipoEnsino();
        $curso->setTipoEnsino($tipoEnsino);

        $tipoRegime = $this->buildTipoRegime();
        $curso->setTipoRegime($tipoRegime);

        $cursoHabilitacoes = $this->buildCursosHabilitacoes();
        //$habilitacao = $this->buildHabilitacao();
        $curso->addHabilitacoes($cursoHabilitacoes);

        return $curso;
    }


    private function buildNivelEnsino()
    {
        $nivelEnsino = new \Escola\Entity\NivelEnsino();
        $nivelEnsino->setNome('Nivel 1');
        $nivelEnsino->setDescricao('Descricao nivel de ensino');

        return $nivelEnsino;
    }

    private function buildTipoEnsino()
    {
        $tipoEnsino = new \Escola\Entity\TipoEnsino();
        $tipoEnsino->setNome('Tipo Ensino');
        $tipoEnsino->setAtivo(true);

        return $tipoEnsino;
    }

    private function buildTipoRegime()
    {
        $tipoRegime = new \Escola\Entity\TipoRegime();
        $tipoRegime->setNome('Tipo Regime 1');
        $tipoRegime->setAtivo(true);

        return $tipoRegime;
    }

    private function buildCursosHabilitacoes()
    {
        $cursoHabilitacoes = new ArrayCollection();
        $habilitacao = new \Escola\Entity\Habilitacao();
        $habilitacao->setNome('Habilitacao Nome');
        $habilitacao->setDescricao('Desc Habilitacao');
        $habilitacao->setAtivo(true);

        $cursoHabilitacoes->add($habilitacao);

        return $cursoHabilitacoes;
    }

    private function buildRegraAvaliacao()
    {
        $regra = new \Escola\Entity\RegraAvaliacao();
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

    private function buildSerie()
    {
        $serie = new \Escola\Entity\Serie();
        $serie->setNome('1 ano');
        $serie->setEtapaCurso('1');
        $serie->setConcluinte(true);
        $serie->setCargaHoraria(60);
        $serie->setAtivo(true);
        $serie->setIntervalo(15);
        $serie->setIdadeInicial(5);
        $serie->setIdadeFinal(7);
        $serie->setObservacaoHistorico('Apenas uma observação');
        $serie->setDiasLetivos('200');
        $curso = $this->buildCurso();
        $serie->setCurso($curso);
        $regra = $this->buildRegraAvaliacao();
        $serie->setRegraAvaliacao($regra);

        return $serie;
    }

    private function buildSequenciaSerie()
    {
        $sequencia = new \Escola\Entity\SequenciaSerie();
        $serie = $this->buildSerie();
        $sequencia->setSerieOrigem($serie);
        $serie_outra = $this->buildSerie();
        $serie_outra->setNome('2 ano');
        $sequencia->setSerieDestino($serie_outra);

        return $sequencia;
    }

}