<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/01/16
 * Time: 11:19
 */
use Escola\Entity\Serie;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Controller
 */
class SerieControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string SerieController
     */
    protected $controllerFQDN = 'Escola\Controller\SerieController';

    /**
     * Nome da rota. geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testSerieIndexAction()
    {
        $serieA = $this->buildSerie();
        $serieB = $this->buildSerie();
        $serieB->setNome('Medio');
        $this->em->persist($serieA);
        $this->em->persist($serieB);
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
        $this->assertEquals($serieA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($serieB->getNome(), $paginator->getItem(2)->getNome());

    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testSerieSaveActionNewRequest()
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

        $curso = $form->get('curso');
        $this->assertEquals('curso', $curso->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $curso->getAttribute('type'));

        $nome = $form->get('nome');
        $this->assertEquals('nome', $nome->getName());
        $this->assertEquals('text', $nome->getAttribute('type'));

        $etapaCurso = $form->get('etapaCurso');
        $this->assertEquals('etapaCurso', $etapaCurso->getName());
        $this->assertEquals('Zend\Form\Element\Select', $etapaCurso->getAttribute('type'));

        $regraAvaliacao = $form->get('regraAvaliacao');
        $this->assertEquals('regraAvaliacao', $regraAvaliacao->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $regraAvaliacao->getAttribute('type'));

        $concluinte = $form->get('concluinte');
        $this->assertEquals('concluinte', $concluinte->getName());
        $this->assertEquals('Zend\Form\Element\Radio', $concluinte->getAttribute('type'));

        $cargaHoraria = $form->get('cargaHoraria');
        $this->assertEquals('cargaHoraria', $cargaHoraria->getName());
        $this->assertEquals('text', $cargaHoraria->getAttribute('type'));

        $diasLetivos = $form->get('diasLetivos');
        $this->assertEquals('diasLetivos', $diasLetivos->getName());
        $this->assertEquals('text', $diasLetivos->getAttribute('type'));

        $intervalo = $form->get('intervalo');
        $this->assertEquals('intervalo', $intervalo->getName());
        $this->assertEquals('text', $intervalo->getAttribute('type'));

        $idadeInicial = $form->get('idadeInicial');
        $this->assertEquals('idadeInicial', $idadeInicial->getName());
        $this->assertEquals('text', $idadeInicial->getAttribute('type'));

        $idadeFinal = $form->get('idadeFinal');
        $this->assertEquals('idadeFinal', $idadeFinal->getName());
        $this->assertEquals('text', $idadeFinal->getAttribute('type'));

        $observacao = $form->get('observacaoHistorico');
        $this->assertEquals('observacaoHistorico', $observacao->getName());
        $this->assertEquals('textarea', $observacao->getAttribute('type'));

    }

    /**
     * testa a tela de alteracao de um registro
     */
    public function testSerieSaveActionUpdateFormRequest()
    {
        $serie = $this->buildSerie();
        $this->em->persist($serie);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $serie->getId());
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
        $this->assertEquals($serie->getId(), $id->getValue());
        $this->assertEquals($serie->getNome(), $nome->getValue());
    }

    /**
     * Testa a inclusao de um novo registro
     */
    public function testSerieSaveActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '1 ano');
        $this->request->getPost()->set('etapaCurso', '1');
        $this->request->getPost()->set('concluinte', true);
        $this->request->getPost()->set('cargaHoraria', 60);
        $this->request->getPost()->set('intervalo', 15);
        $this->request->getPost()->set('idadeInicial', 5);
        $this->request->getPost()->set('idadeFinal', 7);
        $this->request->getPost()->set('observacaoHistorico', 'Apenas uma observação');
        $this->request->getPost()->set('diasLetivos', 200);
        $curso = $this->buildCurso();
        $regra = $this->buildRegraAvaliacao();
        $this->em->persist($curso);
        $this->em->persist($regra);
        $this->em->flush();
        $this->request->getPost()->set('curso', $curso->getId());
        $this->request->getPost()->set('regraAvaliacao', $regra->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/serie', $headers->get('Location'));
    }

    /**
     * Testa o update de um registro
     */
    public function testSerieUpdateAction()
    {
        $serie = $this->buildSerie();
        $this->em->persist($serie);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $serie->getId());
        $this->request->getPost()->set('nome', 'Outro Nome');
        $this->request->getPost()->set('etapaCurso', $serie->getEtapaCurso());
        $this->request->getPost()->set('concluinte', $serie->getConcluinte());
        $this->request->getPost()->set('cargaHoraria', $serie->getCargaHoraria());
        $this->request->getPost()->set('intervalo', $serie->getIntervalo());
        $this->request->getPost()->set('idadeInicial', $serie->getIdadeInicial());
        $this->request->getPost()->set('idadeFinal', $serie->getIdadeFinal());
        $this->request->getPost()->set('observacaoHistorico', $serie->getObservacaoHistorico());
        $this->request->getPost()->set('diasLetivos', $serie->getDiasLetivos());
        $this->request->getPost()->set('curso', $serie->getCurso()->getId());
        $this->request->getPost()->set('regraAvaliacao', $serie->getRegraAvaliacao()->getId());


        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        //	Verifica a resposta
        $response = $this->controller->getResponse();
        //	a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/serie', $headers->get('Location'));

        $savedSerie = $this->em->find(get_class($serie), $serie->getId());
        $this->assertEquals('Outro Nome', $savedSerie->getNome());
    }

    /**
     * testa a inclusao, formulario invalido e nome vazio
     */
    public function testSerieSaveActionInvalidFormPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');
        $this->request->getPost()->set('etapaCurso', '1');
        $this->request->getPost()->set('concluinte', true);
        $this->request->getPost()->set('cargaHoraria', 60);
        $this->request->getPost()->set('intervalo', 15);
        $this->request->getPost()->set('idadeInicial', 5);
        $this->request->getPost()->set('idadeFinal', 7);
        $this->request->getPost()->set('observacaoHistorico', 'Apenas uma observação');
        $this->request->getPost()->set('diasLetivos', 200);
        $curso = $this->buildCurso();
        $regra = $this->buildRegraAvaliacao();
        $this->em->persist($curso);
        $this->em->persist($regra);
        $this->em->flush();
        $this->request->getPost()->set('curso', $curso->getId());
        $this->request->getPost()->set('regraAvaliacao', $regra->getId());

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
    public function testSerieBuscaPostActionRequest()
    {
        $serieA = $this->buildSerie();
        $serieB = $this->buildSerie();
        $serieB->setNome('GOLD');
        $this->em->persist($serieA);
        $this->em->persist($serieB);
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
        $this->assertEquals($serieB->getNome(), $dados[0]->getNome());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testSerieInvalidDeleteAction()
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
    public function testSerieDeleteAction()
    {
        $serie = $this->buildSerie();
        $this->em->persist($serie);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $serie->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/serie', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testSerieDetalhesAction()
    {
        $serie = $this->buildSerie();
        $this->em->persist($serie);
        $this->em->flush();

        // Dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $serie->getId());

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
        $this->assertEquals($serie->getNome(), $data->getNome());
    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testSerieDetalhesInvalidIdAction()
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
    public function testSerieInvalidIdDeleteAction()
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
            'Location: /escola/serie', $headers->get('Location')
        );
    }

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
        $serie = new Serie();
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
}