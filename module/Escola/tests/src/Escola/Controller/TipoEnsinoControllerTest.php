<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 23/12/15
 * Time: 16:04
 */
use Escola\Entity\TipoEnsino;

/**
 * @group Controller
 */
class TipoEnsinoControllerTest extends \Core\Test\ControllerTestCase
{
    /**
     * Namespace completa do controller
     * @var string TipoEnsinoController
     */
    protected $controllerFQDN = 'Escola\Controller\TipoEnsinoController';

    /**
     * Nome da rota. geralmente o nome do moulo
     * @var string escola
     */
    protected $controllerRoute = 'escola';

    /**
     * testa a pagina inicial, listando os tipos de ensino
     */
    public function testTipoEnsinoIndexAction()
    {
        $tipoEnsinoA = $this->buildTipoEnsino();
        $tipoEnsinoB = $this->buildTipoEnsino();
        $tipoEnsinoB->setNome('Medio');
        $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
        $em->persist($tipoEnsinoA);
        $em->persist($tipoEnsinoB);
        $em->flush();

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
        $this->assertEquals($tipoEnsinoA->getNome(), $paginator->getItem(1)->getNome());
        $this->assertEquals($tipoEnsinoB->getNome(), $paginator->getItem(1)->getNome());

    }

    private function buildTipoEnsino()
    {
        $instituicao = $this->buildInstituicao();
        $tipoEnsino = new TipoEnsino();
        $tipoEnsino->setNome('Integral');
        $tipoEnsino->setInstituicao($instituicao);
    }

    private function buildInstituicao()
    {
        $instituicao = new \Escola\Entity\Instituicao();
        $instituicao->setNome('Prefeitura Municipial Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
    }
}