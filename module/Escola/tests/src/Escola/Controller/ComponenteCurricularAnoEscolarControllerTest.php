<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 05/04/16
 * Time: 09:14
 */
use Escola\Entity\ComponenteCurricularAnoEscolar;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Controller
 */
class ComponenteCurricularAnoEscolarControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string ComponenteCurricularAnoEscolarController
     */
    protected $controllerFQDN = 'Escola\Controller\ComponenteCurricularAnoEscolarController';

    /**
     * Nome da rota. geralmente o nome do módulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os dados
     */
    public function testComponenteCurricularAnoEscolarIndexAction()
    {
        $componenteA = $this->buildComponenteCurricularAnoEscolar();
        $componenteB = $this->buildComponenteCurricularAnoEscolar();
        $componenteB->setCargaHoraria('300');
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

        // faz a comparação dos dados
        $paginator = $variables['dados'];
        $this->assertEquals($componenteA->getCargaHoraria(), $paginator->getItem(1)->getCargaHoraria());
        $this->assertEquals($componenteB->getCargaHoraria(), $paginator->getItem(2)->getCargaHoraria());

    }

    /**
     * testa a tela de inclusao de um novo registro
     * @return void
     */
    public function testComponenteCurricularAnoEscolarSaveActionNewRequest()
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

        $cargaHoraria = $form->get('cargaHoraria');
        $this->assertEquals('cargaHoraria', $cargaHoraria->getName());
        $this->assertEquals('text', $cargaHoraria->getAttribute('type'));

        $serie = $form->get('serie');
        $this->assertEquals('serie', $serie->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $serie->getAttribute('type'));

        $componenteCurricular = $form->get('componenteCurricular');
        $this->assertEquals('componenteCurricular', $componenteCurricular->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $serie->getAttribute('type'));

    }

    /**
     * testa a tela de alteracao de um registro
     */
    public function testComponenteCurricularAnoEscolarSaveActionUpdateFormRequest()
    {
        $componenteCurricular = $this->buildComponenteCurricularAnoEscolar();
        $this->em->persist($componenteCurricular);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $componenteCurricular->getId());
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
        $cargaHoraria = $form->get('cargaHoraria');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($componenteCurricular->getId(), $id->getValue());
        $this->assertEquals($componenteCurricular->getCargaHoraria(), $cargaHoraria->getValue());
    }

    /**
     * testa a inclusao de um novo registro
     */
    public function testComponenteCurricularAnoEscolarSaveActionPostRequest()
    {
        $serie = $this->buildSerie();
        $componenteCurricular = $this->buildComponenteCurricular();
        $this->em->persist($serie);
        $this->em->persist($componenteCurricular);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('cargaHoraria', '200');
        $this->request->getPost()->set('serie', $serie->getId());
        $this->request->getPost()->set('componenteCurricular', $componenteCurricular->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/componente-curricular-ano-escolar', $headers->get('Location'));

    }

    /**
     * Testa o update de um registro
     */
    public function testComponenteCurricularAnoEscolarUpdateAction()
    {
        $componente = $this->buildComponenteCurricularAnoEscolar();
        $this->em->persist($componente);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $componente->getId());
        $this->request->getPost()->set('cargaHoraria', '500');
        $this->request->getPost()->set('serie', $componente->getSerie()->getId());
        $this->request->getPost()->set('componenteCurricular', $componente->getComponenteCurricular()->getId());
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/componente-curricular-ano-escolar', $headers->get('Location'));

        $savedComponente = $this->em->find(get_class($componente), $componente->getId());
        $this->assertEquals('500', $savedComponente->getCargaHoraria());
    }

    /**
     * testa a inclusao, formulario invalido e serie vazia
     */
    public function testComponenteCurricularAnoEscolarSaveActionInvalidFormPostRequest()
    {
        $componenteCurricular = $this->buildComponenteCurricularAnoEscolar();
        $this->em->persist($componenteCurricular);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('cargaHoraria', 'abcdf');
        $this->request->getPost()->set('serie', '');
        $this->request->getPost()->set('componenteCurricular', $componenteCurricular->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();

        // a pagina nao redireciona por causa do erro, entao o status = 200
        $this->assertEquals(200, $response->getStatusCode());

        // verify filters validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs['serie']['isEmpty']);
        $this->assertEquals('The input does not appear to be a float', $msgs['cargaHoraria']['notFloat']);
    }

    /**
     * Testa a busca com resultados
     */
    public function testComponenteCurricularAnoEscolarBuscaPostActionRequest()
    {
        $componenteA = $this->buildComponenteCurricularAnoEscolar();
        $componenteB = $this->buildComponenteCurricularAnoEscolar();
        $componenteB->setCargaHoraria('500');
        $this->em->persist($componenteA);
        $this->em->persist($componenteB);
        $this->em->flush();

        // invoca a rota index
        $this->routeMatch->setParam('action', 'busca');
        $this->request->getPost()->set('q', '500');

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
        $this->assertEquals($componenteB->getCargaHoraria(), $dados[0]->getCargaHoraria());
    }

    /**
     * Testa a exclusao sem passar o id
     * @expectedException Exception
     * @expectedExceptionMessage Código Obrigatório
     */
    public function testComponenteCurricularAnoEscolarInvalidDeleteAction()
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
    public function testComponenteCurricularAnoEscolarDeleteAction()
    {
        $componente = $this->buildComponenteCurricularAnoEscolar();
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
        $this->assertEquals('Location: /escola/componente-curricular-ano-escolar', $headers->get('Location'));
    }

    /**
     * Testa a tela de detalhes
     */
    public function testComponenteCurricularAnoEscolarDetalhesAction()
    {
        $componente = $this->buildComponenteCurricularAnoEscolar();
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
        $this->assertEquals($componente->getCargaHoraria(), $data->getCargaHoraria());
    }

    /**
     * Testa visualizaçao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testComponenteCurricularAnoEscolarDetalhesInvalidIdAction()
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
    public function testComponenteCurricularAnoEscolarInvalidIdDeleteAction()
    {
        $componente = $this->buildComponenteCurricularAnoEscolar();
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
            'Location: /escola/componente-curricular-ano-escolar', $headers->get('Location')
        );
    }


    /**
     * @return AreaConhecimento
     */
    private function buildAreaConhecimento()
    {
        $area = new \Escola\Entity\AreaConhecimento();
        $area->setNome('Nome da Area');
        $area->setSecao('Seção da area');

        return $area;
    }

    private function buildComponenteCurricular()
    {
        $componente = new \Escola\Entity\ComponenteCurricular();
        $componente->setNome('Integral');
        $componente->setAbreviatura('Int');
        $componente->setTipoBase(1);
        $area = $this->buildAreaConhecimento();
        $componente->setAreaConhecimento($area);

        return $componente;
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

    private function buildComponenteCurricularAnoEscolar()
    {
        $componente = new ComponenteCurricularAnoEscolar();
        $componente->setCargaHoraria('200');
        $componenteCurricular = $this->buildComponenteCurricular();
        $componente->setComponenteCurricular($componenteCurricular);
        $serie = $this->buildSerie();
        $componente->setSerie($serie);

        return $componente;
    }
}