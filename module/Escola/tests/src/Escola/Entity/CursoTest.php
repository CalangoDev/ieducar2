<?php
/**
 * Created by Vim.
 * User: eduardojunior
 * Date: 30/12/15
 * Time: 22:00
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class CursoTest extends EntityTestCase
{
	public fuction setup()
	{
		parent::setup();
	}

	/**
	 * Check if filters exists
	 */
	public function testGetInputFilter()
	{
		$curso = new Curso();
		$if = $instituicao->getInputFilter();
		$this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

		return $if;
	}

	/**
	 * @depends testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		$this->assertEquals(17, $if->count());
		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('nome'));
		$this->assertTrue($if->has('sigla'));
		$this->assertTrue($if->has('quantidadeEtapa'));
		$this->assertTrue($if->has('cargaHoraria'));
		$this->assertTrue($if->has('atoPoderPublico'));
		$this->assertTrue($if->has('objetivo'));
		$this->assertTrue($if->has('publicoAlvo'));
		$this->assertTrue($if->has('ativo'));
		$this->assertTrue($if->has('padraoAnoEscolar'));
		$this->assertTrue($if->has('horaFalta'));
		$this->assertTrue($if->has('multiSeriado'));
		$this->assertTrue($if->has('instituicao'));
		$this->assertTrue($if->has('nivelEnsino'));
		$this->assertTrue($if->has('tipoEnsino'));
		$this->assertTrue($if->has('tipoRegime'));
		$this->assertTrue($if->has('cursoHabilitacoes'));
		// TODO: continue this
	}

	private function buildCurso()
	{
		$curso = new Curso();
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
		$curso->setMultiSeriado(false);
		
		$instituicao = $this->buildInstituicao();
		$curso->setInstituicao($curso);
		
		$nivelEnsino = $this->buildNivelEnsino();
		$curso->setNivelEnsino($nivelEnsino);

		$tipoEnsino = $this->buildTipoEnsino();
		$curso->setTipoEnsino($tipoEnsino);

		$tipoRegime = $this->buildTipoRegime();
		$curso->setTipoRegime($tipoRegime);

		// TODO: Inserir Habilitacoes do curso

		return $curso;
	}

	private function buildInstituicao()
    {
        $instituicao = new Instituicao();
        $instituicao->setNome('Prefeitura Municipal Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
	}

	private function buildNivelEnsino()
	{
		$nivelEnsino = new NivelEnsino();
		$nivelEnsino->setNome('Nivel 1');
		$nivelEnsino->setDescricao('Descricao nivel de ensino');
		$instituicao = $this->buildInstituicao();
		$nivelEnsino->setInstituicao($instituicao);

		return $nivelEnsino;
	}

	private function buildTipoEnsino()
	{
		$tipoEnsino = new TipoEnsino();
		$tipoEnsino->setNome('Tipo Ensino');
		$tipoEnsino->setAtivo(true);
		$instituicao = $this->buildInstituicao();
		$tipoEnsino->setInstituicao($instituicao);

		return $tipoEnsino;
	}

	private function buildTipoRegime()
	{
		$tipoRegime = new TipoRegime();
		$tipoRegime->setNome('Tipo Regime 1');
		$tipoRegime->setAtivo(true);
		$instituicao = $this->buildInstituicao();
		$tipoRegime->setInstituicao($instituicao);

		return $tipoRegime;
	}

}
