<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 19/01/16
 * Time: 08:46
 */
use Escola\Entity\Escola;

/**
 * @group Controller
 */
class EscolaControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string EscolaController
     */
    protected $controllerFQDN = 'Escola\Controller\EscolaController';

    /**
     * Nome da rota. geralmente o nome do modulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando as habilitacoes
     */
    public function testEscolaIndexAction()
    {
        $escolaA = $this->buildEscola();
        $escolaB = $this->buildEscola();
        $juridica = $this->buildJuridica();
        $juridica->setNome('Escola X');
        $escolaB->setJuridica($juridica);
        $this->em->persist($escolaA);
        $this->em->persist($escolaB);
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
        $this->assertEquals($escolaA->getJuridica()->getNome(), $paginator->getItem(1)->getJuridica()->getNome());
        $this->assertEquals($escolaB->getJuridica()->getNome(), $paginator->getItem(2)->getJuridica()->getNome());

    }

    /**
     * testa a tela de inclusao de um novo registro
     */
    public function testEscolaSaveActionNewRequest()
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

        $codigoInep = $form->get('codigoInep');
        $this->assertEquals('codigoInep', $codigoInep->getName());
        $this->assertEquals('text', $codigoInep->getAttribute('type'));

        $sigla = $form->get('sigla');
        $this->assertEquals('sigla', $sigla->getName());
        $this->assertEquals('text', $sigla->getAttribute('type'));

        $redeEnsino = $form->get('redeEnsino');
        $this->assertEquals('redeEnsino', $redeEnsino->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $redeEnsino->getAttribute('type'));

        $localizacao = $form->get('localizacao');
        $this->assertEquals('localizacao', $localizacao->getName());
        $this->assertEquals('DoctrineModule\Form\Element\ObjectSelect', $localizacao->getAttribute('type'));

        $bloquearLancamento = $form->get('bloquearLancamento');
        $this->assertEquals('bloquearLancamento', $bloquearLancamento->getName());
        $this->assertEquals('Zend\Form\Element\Checkbox', $bloquearLancamento->getAttribute('type'));

        $juridica = $form->get('juridica');
        $idjuridica = $juridica->get('id');
        $this->assertEquals('id', $idjuridica->getName());
        $this->assertEquals('hidden', $idjuridica->getAttribute('type'));

        $situacao = $juridica->get('situacao');
        $this->assertEquals('situacao', $situacao->getName());
        $this->assertEquals('hidden', $situacao->getAttribute('type'));

        $nome = $juridica->get('nome');
        $this->assertEquals('nome', $nome->getName());
        $this->assertEquals('text', $nome->getAttribute('type'));

        $cnpj = $juridica->get('cnpj');
        $this->assertEquals('cnpj', $cnpj->getName());
        $this->assertEquals('text', $cnpj->getAttribute('type'));

        $fantasia = $juridica->get('fantasia');
        $this->assertEquals('fantasia', $fantasia->getName());
        $this->assertEquals('text', $fantasia->getAttribute('type'));

        $url = $juridica->get('url');
        $this->assertEquals('url', $url->getName());
        $this->assertEquals('text', $url->getAttribute('type'));

        $email = $juridica->get('email');
        $this->assertEquals('email', $email->getName());
        $this->assertEquals('email', $email->getAttribute('type'));

        $telefones = $form->get('telefones');
        $this->assertEquals('telefones', $telefones->getName());
        $this->assertEquals('Zend\Form\Element\Collection', $telefones->getAttribute('type'));
    }

    /**
     * testa a tela de alteracoes de um registro
     */
    public function testEscolaSaveActionUpdateFormRequest()
    {
        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->routeMatch->setParam('id', $escola->getId());
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
        $juridica = $form->get('juridica');
        $nome = $juridica->get('nome');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($escola->getId(), $id->getValue());
        $this->assertEquals($escola->getJuridica()->getNome(), $nome->getValue());
    }

    /**
     * testa a inclusao de um novo registro
     */
    public function testEscolaSaveActionPostRequest()
    {
        $rede = $this->buildRedeEnsino();
        $localizacao = $this->buildLocalizacao();
        $curso = $this->buildCurso();
        $this->em->persist($rede);
        $this->em->persist($localizacao);
        $this->em->persist($curso);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('codigoInep', '12345678');
        $this->request->getPost()->set('sigla', 'EM');
        $this->request->getPost()->set('redeEnsino', $rede->getId());
        $this->request->getPost()->set('localizacao', $localizacao->getId());
        $this->request->getPost()->set('bloquearLancamento', '0');
        $juridica = array(
            'id' => '0',
            'situacao' => 'A',
            'nome' => 'Escola Modelo',
            'cnpj' => '',
            'fantasia' => '',
            'url' => '',
            'email' => ''
        );
        $this->request->getPost()->set('juridica', $juridica);

        $telefones = array(array(
            'id' => '',
            'ddd' => '74',
            'numero' => '12345678'
        ));
        $this->request->getPost()->set('telefones', $telefones);
        $this->request->getPost()->set('cursos[]', $curso->getId());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/escola', $headers->get('Location'));
    }

    /**
     * testa o update de um registro
     */
    public function testEscolaUpdateAction()
    {
        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush();
        // dispara a acao
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', $escola->getId());
        $this->request->getPost()->set('codigoInep', '12345678');
        $this->request->getPost()->set('sigla', 'EM');
        $this->request->getPost()->set('redeEnsino', $escola->getRedeEnsino()->getId());
        $this->request->getPost()->set('localizacao', $escola->getLocalizacao()->getId());
        $this->request->getPost()->set('bloquearLancamento', '0');
        $juridica = array(
            'id' => '0',
            'situacao' => 'A',
            'nome' => 'Escola Nome diferente',
            'cnpj' => '',
            'fantasia' => '',
            'url' => '',
            'email' => ''
        );
        $this->request->getPost()->set('juridica', $juridica);
        $this->request->getPost()->set('telefones', array());
        $this->request->getPost()->set('cursos[]', array());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina redireciona, entao o status = 302
        $this->assertEquals(302, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals('Location: /escola/escola', $headers->get('Location'));

        $savedEscola = $this->em->find(get_class($escola), $escola->getId());
        $this->assertEquals('Escola Nome diferente', $savedEscola->getJuridica()->getNome());
    }

    /**
     * testa a inclusao, o formulario invalido e sigla vazio
     */
    public function testEscolaInvalidFormPostRequest()
    {
        $this->routeMatch->setParam('action', 'save');
        $this->request->setMethod('post');
        $this->request->getPost()->set('id', '');
        $this->request->getPost()->set('codigoInep', '');
        $this->request->getPost()->set('sigla', '');
        $this->request->getPost()->set('redeEnsino', '');
        $this->request->getPost()->set('localizacao', '');
        $this->request->getPost()->set('bloquearLancamento', '0');
        $juridica = array(
            'id' => '0',
            'situacao' => 'A',
            'nome' => '',
            'cnpj' => '',
            'fantasia' => '',
            'url' => '',
            'email' => ''
        );
        $this->request->getPost()->set('juridica', $juridica);
        $this->request->getPost()->set('telefones', array());
        $this->request->getPost()->set('cursos[]', array());

        $result = $this->controller->dispatch(
            $this->request, $this->response
        );

        // verifica a resposta
        $response = $this->controller->getResponse();
        // a pagina nao redireciona, entao o status = 200
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();
        // verify filters validators
        $msgs = $result->getVariables()['form']->getMessages();
        $this->assertEquals('Value is required and can\'t be empty', $msgs["codigoInep"]['isEmpty']);
    }

    /**
     * Testa a busca com resultados
     */
    public function testEscolaBuscaPostRequest()
    {
        $escolaA = $this->buildEscola();
        $escolaB = $this->buildEscola();
        $juridica = $this->buildJuridica();
        $juridica->setNome('GOLD');
        $escolaB->setJuridica($juridica);
        $this->em->persist($escolaA);
        $this->em->persist($escolaB);
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
        $this->assertEquals($escolaB->getJuridica()->getNome(), $dados[0]->getJuridica()->getNome());
    }

    /**
    * Testa a exclusao sem passar o id
    * @expectedException Exception
    * @expectedExceptionMessage Código Obrigatório
    */
    public function testEscolaInvalidDeleteAction()
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
     * Testa a exlusao
     */
    public function testEscolaDeleteAction()
    {
        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'delete');
        $this->routeMatch->setParam('id', $escola->getId());

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
    public function testEscolaDetalhesAction()
    {
        $escola = $this->buildEscola();
        $this->em->persist($escola);
        $this->em->flush();

        // dispara a acao
        $this->routeMatch->setParam('action', 'detalhes');
        $this->routeMatch->setParam('id', $escola->getId());

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
        $this->assertEquals($escola->getJuridica()->getNome(), $data->getJuridica()->getNome());
    }

    /**
     * Testa visualizacao de detalhes de um id inexistente
     * @expectedException Exception
     * @expectedExceptionMessage Registro não encontrado
     */
    public function testEscolaDetalhesInvalidIdAction()
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
    public function testEscolaInvalidIdDeleteAction()
    {
        $escola = $this->buildEscola();
        $this->em->persist($escola);
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
            'Location: /escola/escola', $headers->get('Location')
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
        $escola = new Escola();
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
}