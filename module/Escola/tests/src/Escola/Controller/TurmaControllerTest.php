<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/09/16
 * Time: 15:20
 */
use Core\Test\ControllerTestCase;
use Escola\Entity\Turma;
use Escola\Entity\Instituicao;
use Escola\Entity\RedeEnsino;
use Escola\Entity\Localizacao;
use Usuario\Entity\Juridica;
use Escola\Entity\Escola;
use Escola\Entity\AnoLetivo;
use Escola\Entity\ComodoFuncao;
use Escola\Entity\Predio;
use Escola\Entity\ComodoPredio;
use Escola\Entity\TurmaTurno;
use Escola\Entity\NivelEnsino;
use Escola\Entity\TipoEnsino;
use Escola\Entity\TipoRegime;
use Escola\Entity\RegraAvaliacao;
use Escola\Entity\Curso;
use Escola\Entity\Serie;
use Escola\Entity\EscolaSerie;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Controller
 */
class TurmaControllerTest extends ControllerTestCase
{
    /**
     * Namespace completa do Controller
     * @var string Turma
     */
    protected $controllerFQDN = 'Escola\Controller\TurmaController';

    /**
     * Nome da rota, geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    public function testTurmaIndexAction()
    {
        $entityA = $this->buildTurma();
        $entityB = $this->buildTurma();
        $entityB->setNome('Nova Turma');
        $this->em->persist($entityA);
        $this->em->persist($entityB);
        $this->em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica o response
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // testa os dados da View
        $variables = $result->getVariables();

        $this->assertArrayHasKey('dados', $variables);

        // faz a comparacao dos dados
        $paginator = $variables['dados'];
        $this->assertEquals($entityA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($entityB->getNome(), $paginator->getItem(2)->getNome());
    }

    public function testTurmaSaveActionNewRequest()
    {
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

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

        $sigla = $form->get('sigla');
        $this->assertEquals('sigla', $sigla->getName());
        $this->assertEquals('text', $sigla->getAttribute('type'));

        $maximoAluno = $form->get('maximoAluno');
        $this->assertEquals('maximoAluno', $maximoAluno->getName());
        $this->assertEquals('text', $maximoAluno->getAttribute('type'));

        $multiSeriada = $form->get('multiSeriada');
        $this->assertEquals('multiSeriada', $multiSeriada->getName());
        $this->assertEquals('Zend\Form\Element\Checkbox', $multiSeriada->getAttribute('type'));

        $ativo = $form->get('ativo');
        $this->assertEquals('ativo', $ativo->getName());
        $this->assertEquals('Zend\Form\Element\Checkbox', $ativo->getAttribute('type'));

        $horaInicial = $form->get('horaInicial');
        $this->assertEquals('horaInicial', $horaInicial->getName());
        $this->assertEquals('text', $horaInicial->getAttribute('type'));

        $horaFinal = $form->get('horaFinal');
        $this->assertEquals('horaFinal', $horaFinal->getName());
        $this->assertEquals('text', $horaFinal->getAttribute('type'));

        $horaInicioIntervalo = $form->get('horaInicioIntervalo');
        $this->assertEquals('horaInicioIntervalo', $horaInicioIntervalo->getName());
        $this->assertEquals('text', $horaInicioIntervalo->getAttribute('type'));

        $horaFimIntervalo = $form->get('horaFimIntervalo');
        $this->assertEquals('horaFimIntervalo', $horaFimIntervalo->getName());
        $this->assertEquals('text', $horaFimIntervalo->getAttribute('type'));

        $tipoBoletim = $form->get('tipoBoletim');
        $this->assertEquals('tipoBoletim', $tipoBoletim->getName());
        $this->assertEquals('Zend\Form\Element\Select', $tipoBoletim->getAttribute('type'));

        $anoLetivo = $form->get('anoLetivo');
        $this->assertEquals('anoLetivo', $anoLetivo->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $anoLetivo->getAttribute('type'));

        $dataFechamento = $form->get('dataFechamento');
        $this->assertEquals('dataFechamento', $dataFechamento->getName());
        $this->assertEquals('text', $dataFechamento->getAttribute('type'));

        $comodoPredio = $form->get('comodoPredio');
        $this->assertEquals('comodoPredio', $comodoPredio->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $comodoPredio->getAttribute('type'));

        $turmaTurno = $form->get('turmaTurno');
        $this->assertEquals('turmaTurno', $turmaTurno->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $turmaTurno->getAttribute('type'));

        $escolaSerie = $form->get('escolaSerie');
        $this->assertEquals('escolaSerie', $escolaSerie->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $escolaSerie->getAttribute('type'));
    }

    /**
     * update screen test
     */
    public function testTurmaSaveActionUpdateFormRequest()
    {
        $entity = $this->buildTurma();
        $this->em->persist($entity);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $entity->getId());
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
        $this->assertEquals($entity->getNome(), $nome->getValue());
    }

    /**
     * new record screen test
     */
    public function testTurmaSaveActionPostRequest()
    {
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', 'Nome da turma');
        $this->request->getPost()->set('sigla', 'NT');
        $this->request->getPost()->set('maximoAluno', '10');
        $this->request->getPost()->set('multiSeriada', 1);
        $this->request->getPost()->set('ativo', '1');
        $this->request->getPost()->set('horaInicial', '07:00:00');
        $this->request->getPost()->set('horaFinal', '12:00:00');
        $this->request->getPost()->set('horaInicioIntervalo', '10:00:00');
        $this->request->getPost()->set('horaFimIntervalo', '10:15:00');
        $this->request->getPost()->set('tipoBoletim', '1');
        $anoLetivo = $this->buildAnoLetivo();
        $this->em->persist($anoLetivo);
        $this->em->flush();
        $this->request->getPost()->set('anoLetivo', $anoLetivo->getId());
        $this->request->getPost()->set('dataFechamento', '10/10/2016');
        $comodoPredio = $this->buildComodoPredio();
        $this->em->persist($comodoPredio);
        $this->em->flush();
        $this->request->getPost()->set('comodoPredio', $comodoPredio->getId());
        $turmaTurno = $this->buildTurmaTurno();
        $this->em->persist($turmaTurno);
        $this->em->flush();
        $this->request->getPost()->set('turmaTurno', $turmaTurno->getId());
        $escolaSerie = $this->buildEscolaSerie();
        $this->em->persist($escolaSerie);
        $this->em->flush();
        $this->request->getPost()->set('escolaSerie', $escolaSerie->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/turma', $headers->get('Location'));
    }

    /**
     * update test
     */
    public function testTurmaUpdateAction()
    {
        $entity = $this->buildTurma();
        $this->em->persist($entity);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $entity->getId());
        $this->request->getPost()->set('nome', 'Turma nova');
        $this->request->getPost()->set('sigla', $entity->getSigla());
        $this->request->getPost()->set('maximoAluno', $entity->getMaximoAluno());
        $this->request->getPost()->set('multiSeriada', $entity->getMultiSeriada());
        $this->request->getPost()->set('ativo', $entity->isAtivo());
        $this->request->getPost()->set('horaInicial', '07:00:00');
        $this->request->getPost()->set('horaFinal', '12:00:00');
        $this->request->getPost()->set('horaInicioIntervalo', '10:00:00');
        $this->request->getPost()->set('horaFimIntervalo', '10:15:00');
        $this->request->getPost()->set('tipoBoletim', $entity->getTipoBoletim());
        $this->request->getPost()->set('anoLetivo', $entity->getAnoLetivo()->getId());
        $this->request->getPost()->set('dataFechamento', '10/10/2016');
        $this->request->getPost()->set('comodoPredio', $entity->getComodoPredio()->getId());
        $this->request->getPost()->set('turmaTurno', $entity->getTurmaTurno()->getId());
        $this->request->getPost()->set('escolaSerie', $entity->getEscolaSerie()->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/turma', $headers->get('Location'));

        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('Turma nova', $savedEntity->getNome());
    }

    /**
     * test new insert, form is invalid with inputs empty
     */
    public function testTurmaSaveActonInvalidFormPostRequest()
    {
        $entity = $this->buildTurma();
        $this->em->persist($entity);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('nome', '');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifiy the response
        $response = $this->controller->getResponse();

        // the page doesn't redirect because error. So status = 200
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();

        // verify validators of filters
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs['nome']['isEmpty']);
    }

    /**
     * test search with results
     */
    public function testTurmaBuscaPostActionRequest()
    {
        $entityA = $this->buildTurma();
        $entityB = $this->buildTurma();
        $entityB->setNome('Nova Turma');
        $this->em->persist($entityA);
        $this->em->persist($entityB);
        $this->em->flush();

        // calls the busca route
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', 'Nova Turma');

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // veritfy the response
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // test if return ViewModel
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

        // test the data returned by ViewModel
        $variables = $result->getVariables();

        // making comparation beetween data
        $dados = $variables["dados"];
        $this->assertEquals($entityB->getNome(), $dados[0]->getNome());

    }

    /**
     * test insert without id
     *
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testTurmaInvalidDeleteAction()
    {
        // call the action
        $this->routeMatch->setParam('action', 'delete');
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verify the response
        $response = $this->controller->getResponse();
    }

    /**
     * test delete action
     */
    public function testTurmaDeleteAction()
    {
        $entity = $this->buildTurma();
        $this->em->persist($entity);
        $this->em->flush();

        // call the action
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $entity->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verify the response
        $response = $this->controller->getResponse();

        // the page redirect. So status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/turma', $headers->get('Location'));
    }

    /**
     * test details action
     */
    public function testTurmaDetalhesAction()
    {
        $entity = $this->buildTurma();
        $this->em->persist($entity);
        $this->em->flush();

        // call the action
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $entity->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verify the response
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        // test the date returned by ViewModel
        $variables = $result->getVariables();
        $this->assertArrayHasKey('data', $variables);

        // making comparation beetween data
        $data = $variables['data'];
        $this->assertEquals($entity->getNome(), $data->getNome());
    }


    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testTurmaDetalhesInvalidIdAction()
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
    public function testTurmaInvalidIdDeleteAction()
    {
        $entity = $this->buildTurma();

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
            'Location: /escola/turma', $headers->get('Location')
        );
    }

    private function buildInstituicao()
    {
        $instituicao = new Instituicao();
        $instituicao->setNome('Prefeitura Municipal de Irecê');
        $instituicao->setResponsavel('Secretaria de Educação');

        return $instituicao;
    }

    private function buildRedeEnsino()
    {
        $rede = new RedeEnsino();
        $rede->setNome('Muincipal');
        $instituicao = $this->buildInstituicao();
        $rede->setInstituicao($instituicao);

        return $rede;
    }

    private function buildLocalizacao()
    {
        $localizacao = new Localizacao();
        $localizacao->setNome('Urbana');

        return $localizacao;
    }

    private function buildJuridica()
    {
        $juridica = new Juridica();
        $juridica->setNome('Escola Modelo');
        $juridica->setFantasia('Escola Modelo');
        $juridica->setSituacao('A');

        return $juridica;
    }

    private function buildEscola()
    {
        $entity = new Escola();
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

    private function buildComodoFuncao()
    {
        $entity = new ComodoFuncao();
        $entity->setNome('Sala de Aula');
        $entity->setDescricao('Sala de Aula ');
        $entity->setAtivo(true);

        return $entity;
    }

    private function buildPredio()
    {
        $entity = new Predio();
        $entity->setNome('Predio de Aulas 1');
        $entity->setDescricao('Predio de Aula 1');
        $entity->setEndereco('Rua X');
        $escola = $this->buildEscola();
        $entity->setEscola($escola);

        return $entity;
    }

    private function buildComodoPredio()
    {
        $entity = new ComodoPredio();
        $entity->setNome('Sala 1');
        $entity->setDescricao('Sala 1');
        $entity->setArea('10');
        $comodoFuncao = $this->buildComodoFuncao();
        $entity->setComodoFuncao($comodoFuncao);
        $predio = $this->buildPredio();
        $entity->setPredio($predio);

        return $entity;
    }

    private function buildTurmaTurno()
    {
        $entity = new TurmaTurno();
        $entity->setNome('Matutino');

        return $entity;
    }

    private function buildNivelEnsino()
    {
        $entity = new NivelEnsino();
        $entity->setNome('Nivel 1');
        $entity->setDescricao('Descricao nivel de ensino');

        return $entity;
    }

    private function buildTipoEnsino()
    {
        $entity = new TipoEnsino();
        $entity->setNome('Tipo Ensino');
        $entity->setAtivo(true);

        return $entity;
    }

    private function buildTipoRegime()
    {
        $entity = new TipoRegime();
        $entity->setNome('Tipo Regime 1');
        $entity->setAtivo(true);

        return $entity;
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
        $entity = new RegraAvaliacao();
        $entity->setNome('Nome da Regra');
        $entity->setTipoNota(1);
        $entity->setTipoProgressao(1);
        $entity->setMedia('10');
        $entity->setPorcentagemPresenca(45);
        $entity->setParecerDescritivo(1);
        $entity->setTipoPresenca(1);
        $entity->setMediaRecuperacao(50);
        $formulaMedia = $this->buildFormulaMedia();
        $entity->setFormulaMedia($formulaMedia);
        $entity->setFormulaRecuperacao($formulaMedia);
        $tabela = $this->buildTabelaArredondamento();
        $entity->setTabelaArredondamento($tabela);

        return $entity;

    }

    private function buildCurso()
    {
        $entity = new Curso();
        $entity->setNome('Curso Teste');
        $entity->setSigla('CT');
        $entity->setQuantidadeEtapa(4);
        $entity->setCargaHoraria(60.0);
        $entity->setAtoPoderPublico('ato');
        $entity->setObjetivo('Objetivo do curso');
        $entity->setPublicoAlvo('Infantil');
        $entity->setAtivo(true);
        $entity->setPadraoAnoEscolar(false);
        $entity->setHoraFalta(50.0);
        $entity->setMultiSeriado(0);

        $nivelEnsino = $this->buildNivelEnsino();
        $entity->setNivelEnsino($nivelEnsino);

        $tipoEnsino = $this->buildTipoEnsino();
        $entity->setTipoEnsino($tipoEnsino);

        $tipoRegime = $this->buildTipoRegime();
        $entity->setTipoRegime($tipoRegime);

        $cursoHabilitacoes = $this->buildCursosHabilitacoes();
        $entity->addHabilitacoes($cursoHabilitacoes);

        return $entity;
    }

    private function buildFormulaMedia()
    {
        $entity = new \Escola\Entity\FormulaMedia();
        $entity->setNome('Nome da formula');
        $entity->setTipoFormula(1);
        $entity->setFormulaMedia('Se');

        return $entity;
    }

    private function buildTabelaArredondamento()
    {
        $entity = new \Escola\Entity\TabelaArredondamento();

        $entity->setNome('Tabela de arredondamento');
        $entity->setTipoNota(1);

        return $entity;
    }

    private function buildSerie()
    {
        $entity = new Serie();
        $entity->setNome('1 ano');
        $entity->setEtapaCurso('1');
        $entity->setConcluinte(true);
        $entity->setCargaHoraria(60);
        $entity->setAtivo(true);
        $entity->setIntervalo(15);
        $entity->setIdadeInicial(5);
        $entity->setIdadeFinal(7);
        $entity->setObservacaoHistorico('Apenas uma observação');
        $entity->setDiasLetivos('200');
        $curso = $this->buildCurso();
        $entity->setCurso($curso);
        $regra = $this->buildRegraAvaliacao();
        $entity->setRegraAvaliacao($regra);

        return $entity;
    }

    private function buildEscolaSerie()
    {
        $entity = new EscolaSerie();
        $horaInicial = new \DateTime();
        $horaInicial->setTime(10, 00, 00);
        $entity->setHoraInicial($horaInicial);
        $horaFinal = new \DateTime();
        $entity->setHoraFinal($horaFinal->setTime(12, 00, 00));
        $entity->setAtivo(true);
        $inicioIntervalo = new \DateTime();
        $entity->setInicioIntervalo($inicioIntervalo->setTime(10, 30, 00));
        $fimIntervalo = new \DateTime();
        $entity->setFimIntervalo($fimIntervalo->setTime(10, 45, 00));
        $entity->setBloquearCadastroTurma(false);
        $escola = $this->buildEscola();
        $entity->setEscola($escola);
        $serie = $this->buildSerie();
        $entity->setSerie($serie);

        return $entity;

    }

    private function buildTurma()
    {
        $entity = new Turma();
        $entity->setNome('4 ano');
        $entity->setSigla('4ano');
        $entity->setMaximoAluno(40);
        $entity->setMultiSeriada(true);
        $entity->setAtivo(true);
        $horaInicial = new \DateTime();
        $entity->setHoraFinal($horaInicial->setTime(07, 00, 00));
        $horaFinal = new \DateTime();
        $entity->setHoraFinal($horaFinal->setTime(12, 00, 00));
        $inicioIntervalo = new \DateTime();
        $entity->setHoraInicioIntervalo($inicioIntervalo->setTime(10, 00, 00));
        $fimIntervalo = new \DateTime();
        $entity->setHoraFimIntervalo($fimIntervalo->setTime(10, 30, 00));
        $entity->setVisivel(true);
        $entity->setTipoBoletim(1);
        $anoLetivo = $this->buildAnoLetivo();
        $entity->setAnoLetivo($anoLetivo);
        $entity->setDataFechamento(new \DateTime("17-06-2016", new \DateTimeZone('America/Sao_Paulo')));
        $comodoPredio = $this->buildComodoPredio();
        $entity->setComodoPredio($comodoPredio);
        $turmaTurno = $this->buildTurmaTurno();
        $entity->setTurmaTurno($turmaTurno);
        $escolaSerie = $this->buildEscolaSerie();
        $entity->setEscolaSerie($escolaSerie);

        return $entity;
    }
}