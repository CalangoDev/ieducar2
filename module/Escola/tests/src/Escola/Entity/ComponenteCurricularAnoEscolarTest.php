<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 04/04/16
 * Time: 09:26
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class ComponenteCurricularAnoEscolarTest extends EntityTestCase
{
    public function setup()
    {
        parent::setup();
    }

    /**
     * Check if filters exists
     */
    public function testGetInputFilter()
    {
        $componente = new ComponenteCurricularAnoEscolar();
        $if = $componente->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(4, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('cargaHoraria'));
        $this->assertTrue($if->has('serie'));
        $this->assertTrue($if->has('componenteCurricular'));
    }

    /**
     * Teste insert data
     */
    public function testInsert()
    {
        $componente = $this->buildComponenteCurricularAnoEscolar();
        $this->em->persist($componente);
        $this->em->flush();

        $this->assertNotNull($componente->getId());
        $this->assertEquals(1, $componente->getId());

        /**
         * get row from database
         */
        $savedComponente = $this->em->find(get_class($componente), $componente->getId());
        $this->assertEquals(1, $savedComponente->getId());

    }

    /**
     * @expectedException Core\Entity\EntityException
     */
    public function testInputFilterInvalidCargaHoraria()
    {
        $componente = $this->buildComponenteCurricularAnoEscolar();
        $componente->setCargaHoraria('teste');

        $this->em->persist($componente);
        $this->em->flush();
    }

    public function testUpdate()
    {

        $componente = $this->buildComponenteCurricularAnoEscolar();
        $this->em->persist($componente);
        $this->em->flush();

        $savedComponente = $this->em->find(get_class($componente), $componente->getId());
        $this->assertEquals('200', $savedComponente->getCargaHoraria());

        $savedComponente->setCargaHoraria('800');
        $this->em->flush();

        $savedComponente = $this->em->find(get_class($componente), $savedComponente->getId());
        $this->assertEquals('800', $savedComponente->getCargaHoraria());

    }

    public function testDelete()
    {
        $componente = $this->buildComponenteCurricularAnoEscolar();
        $this->em->persist($componente);
        $this->em->flush();

        $id = $componente->getId();
        $savedComponente = $this->em->find(get_class($componente), $id);
        $this->em->remove($savedComponente);
        $this->em->flush();
        $savedComponente = $this->em->find(get_class($componente), $id);
        $this->assertNull($savedComponente);
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