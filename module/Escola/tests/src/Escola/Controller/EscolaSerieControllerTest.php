<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 18/02/16
 * Time: 21:38
 */
use Escola\Entity\EscolaSerie;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Controller
 */
class EscolaSerieControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string EscolaSerieController
     */
    protected $controllerFQDN = 'Escola\Controller\EscolaSerieController';

    /**
     * nome da rota, geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina incial, listando os dados
     */
    public function testEscolaSerieIndexAction()
    {
        $escolaSerieA = $this->buildEscolaSerie();
        $escolaSerieB = $this->buildEscolaSerie();
        $horaInicial = new \DateTime();
        $horaInicial->setTime(11, 00, 00);
        $escolaSerieB->setHoraInicial($horaInicial);
        $this->em->persist($escolaSerieA);
        $this->em->persist($escolaSerieB);
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
        $this->assertEquals($escolaSerieA->getHoraInicial(), $paginator->getItem(1)->getHoraInicial());
        $this->assertEquals($escolaSerieB->getHoraInicial(), $paginator->getItem(2)->getHoraInicial());
    }

    /**
     * Testa a tela de inclusao de um novo registro
     */
    public function testEscolaSerieSaveActionNewRequest()
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

        $escola = $form->get('escola');
        $this->assertEquals('escola', $escola->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $escola->getAttribute('type'));

        $serie = $form->get('serie');
        $this->assertEquals('serie', $serie->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $serie->getAttribute('type'));

        $horaInicial = $form->get('horaInicial');
        $this->assertEquals('horaInicial', $horaInicial->getName());
        $this->assertEquals('text', $horaInicial->getAttribute('type'));

        $horaFinal = $form->get('horaFinal');
        $this->assertEquals('horaFinal', $horaFinal->getName());
        $this->assertEquals('text', $horaFinal->getAttribute('type'));

        $inicioIntervalo = $form->get('inicioIntervalo');
        $this->assertEquals('inicioIntervalo', $inicioIntervalo->getName());
        $this->assertEquals('text', $inicioIntervalo->getAttribute('type'));

        $fimIntervalo = $form->get('fimIntervalo');
        $this->assertEquals('fimIntervalo', $fimIntervalo->getName());
        $this->assertEquals('text', $fimIntervalo->getAttribute('type'));

        $bloquearEnturmacao = $form->get('bloquearEnturmacao');
        $this->assertEquals('bloquearEnturmacao', $bloquearEnturmacao->getName());
        $this->assertEquals('Zend\Form\Element\Checkbox', $bloquearEnturmacao->getAttribute('type'));

        $bloquearCadastroTurma = $form->get('bloquearCadastroTurma');
        $this->assertEquals('bloquearCadastroTurma', $bloquearCadastroTurma->getName());
        $this->assertEquals('Zend\Form\Element\Checkbox', $bloquearCadastroTurma->getAttribute('type'));
    }

    /**
     * testa a tela de alteracao de um registro
     */
    public function testEscolaSerieActionUpdateFormRequest()
    {
        $escolaSerie = $this->buildEscolaSerie();
        $this->em->persist($escolaSerie);
        $this->em->flush();

        // disparaca a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $escolaSerie->getId());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // verifica se recebeu um ViewModel
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        $variables = $result->getVariables();

        // verifica se existe um form
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];

        // testa os itens do formulario
        $id = $form->get('id');
        $escola = $form->get('escola');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($escolaSerie->getId(), $id->getValue());
        $this->assertEquals($escolaSerie->getEscola()->getId(), $escola->getValue());
    }

    /**
     * Testa a inclusao de um novo registro
     */
    public function testEscolaSerieActionPostRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush();
        $this->request->getPost()->set('escola', $escola->getId());
        $serie = $this->buildSerie();
        $this->em->persist($serie);
        $this->em->flush();
        $this->request->getPost()->set('serie', $serie->getId());
        $this->request->getPost()->set('horaInicial', '08:00:00');
        $this->request->getPost()->set('horaFinal', '12:00:00');
        $this->request->getPost()->set('inicioIntervalo', '10:00:00');
        $this->request->getPost()->set('fimIntervalo', '10:15:00');
        $this->request->getPost()->set('bloquearEnturmacao', '0');
        $this->request->getPost()->set('bloquearCadastroTurma', '0');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/escola-serie', $headers->get('Location'));
    }

    /**
     * Testa o update de um registro
     */
    public function testEscolaSerieUpdateAction()
    {
        $escolaSerie = $this->buildEscolaSerie();
        $this->em->persist($escolaSerie);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $escolaSerie->getId());
        $this->request->getPost()->set('escola', $escolaSerie->getId());
        $this->request->getPost()->set('serie', $escolaSerie->getId());
        $this->request->getPost()->set('horaInicial', '09:00:00');
        $this->request->getPost()->set('horaFinal', $escolaSerie->getHoraFinal());
        $this->request->getPost()->set('inicioIntervalo', $escolaSerie->getInicioIntervalo());
        $this->request->getPost()->set('fimIntervalo', $escolaSerie->getFimIntervalo());
        $this->request->getPost()->set('bloquearEnturmacao', $escolaSerie->getBloquearEnturmacao());
        $this->request->getPost()->set('bloquearCadastroTurma', $escolaSerie->getBloquearCadastroTurma());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireiona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/escola-serie', $headers->get('Location'));

        $savedEscolaSerie = $this->em->find(get_class($escolaSerie), $escolaSerie->getId());
        $horaInicial = new \DateTime();
        $horaInicial->setTime(9, 00, 00);
        $this->assertEquals($horaInicial, $savedEscolaSerie->getHoraInicial());
    }


    /**
     * Testa a inclusao, o formulario invalido
     */
    public function testEscolaSerieInvalidFormPostRequest()
    {
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('escola', '');
        $this->request->getPost()->set('serie', '');
        $this->request->getPost()->set('horaInicial', '');
        $this->request->getPost()->set('horaFinal', '');
        $this->request->getPost()->set('inicioIntervalo', '');
        $this->request->getPost()->set('fimIntervalo', '');
        $this->request->getPost()->set('bloquearEnturmacao', '');
        $this->request->getPost()->set('bloquearCadastroTurma', '');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina nao redireciona, entao o status = 200
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();
        //verify filters validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs['escola']['isEmpty']);
        $this->assertEquals('Value is required and can\'t be empty', $msgs['serie']['isEmpty']);
    }

    /**
     * testa a busca com resultados
     */
    public function testEscolaSerieBuscaPostRequest()
    {
        $escolaSerieA = $this->buildEscolaSerie();
        $escolaSerieB = $this->buildEscolaSerie();
        $serie = $this->buildSerie();
        $serie->setNome('2 ano');
        $escolaSerieB->getSerie()->setNome('2 ano');
        $this->em->persist($escolaSerieA);
        $this->em->persist($escolaSerieB);
        $this->em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', '2 ano');

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
        $this->assertEquals($escolaSerieB->getSerie()->getNome(), $dados[0]->getSerie()->getNome());
    }


    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testEscolaSerieInvalidDeleteAction()
    {
        //dispara a acao
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
    public function testEscolaSerieDeleteAction()
    {
        $escolaSerie = $this->buildEscolaSerie();
        $this->em->persist($escolaSerie);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $escolaSerie->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/escola-serie', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testEscolaSerieDetalhesAction()
    {
        $escolaSerie = $this->buildEscolaSerie();
        $this->em->persist($escolaSerie);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $escolaSerie->getId());

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
        $this->assertEquals($escolaSerie->getEscola()->getJuridica()->getNome(), $data->getEscola()->getJuridica()->getNome());
    }


    /**
     * Testa visualizacao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testEscolaSerieDetalhesInvalidIdAction()
    {
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', -1);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Testa a exclusao passando um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testEscolaSerieInvalidIdDeleteAction()
    {
        $escolaSerie = $this->buildEscolaSerie();
        $this->em->persist($escolaSerie);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', 2);

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals(
            'Location: /escola/escola-serie', $headers->get('Location')
        );
    }


    /**
     * @return \Escola\Entity\Instituicao
     */
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

    private function buildEscolaSerie()
    {
        $escolaSerie = new EscolaSerie();
        $horaInicial = new \DateTime();
        $horaInicial->setTime(10, 00, 00);
        $escolaSerie->setHoraInicial($horaInicial);
        $horaFinal = new \DateTime();
        $escolaSerie->setHoraFinal($horaFinal->setTime(12, 00, 00));
        $escolaSerie->setAtivo(true);
        $inicioIntervalo = new \DateTime();
        $escolaSerie->setInicioIntervalo($inicioIntervalo->setTime(10, 30, 00));
        $fimIntervalo = new \DateTime();
        $escolaSerie->setFimIntervalo($fimIntervalo->setTime(10, 45, 00));
        $escolaSerie->setBloquearCadastroTurma(false);
        $escola = $this->buildEscola();
        $escolaSerie->setEscola($escola);
        $serie = $this->buildSerie();
        $escolaSerie->setSerie($serie);

        return $escolaSerie;
    }
}