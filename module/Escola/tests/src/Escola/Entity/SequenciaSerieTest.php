<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 29/04/16
 * Time: 08:50
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class SequenciaSerieTest extends EntityTestCase
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
        $sequenciaSerie = new SequenciaSerie();
        $if = $sequenciaSerie->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(3, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('serieOrigem'));
        $this->assertTrue($if->has('serieDestino'));
    }

    /**
     * test insert data
     */
    public function testInsert()
    {
        $sequencia = $this->buildSequenciaSerie();
        $this->em->persist($sequencia);
        $this->em->flush();

        $this->assertNotNull($sequencia->getId());
        $this->assertEquals(1, $sequencia->getId());

        /**
         * get row from database
         */
        $savedSequencia = $this->em->find(get_class($sequencia), $sequencia->getId());
        $this->assertEquals(1, $savedSequencia->getId());
    }

    public function testUpdate()
    {
        $sequencia = $this->buildSequenciaSerie();
        $this->em->persist($sequencia);
        $this->em->flush();

        $savedSequenciaSerie = $this->em->find(get_class($sequencia), $sequencia->getId());
        $serieNova = $this->buildSerie();
        $serieNova->setNome('Outra super serie');
        $this->em->persist($serieNova);
        $this->em->flush();
        $sequencia->setSerieDestino($serieNova);
        $this->em->flush();
        $savedSequenciaSerie = $this->em->find(get_class($sequencia), $sequencia->getId());
        $this->assertEquals($serieNova, $savedSequenciaSerie->getSerieDestino());
    }

    public function testDelete()
    {
        $sequencia = $this->buildSequenciaSerie();
        $this->em->persist($sequencia);
        $this->em->flush();
        $id = $sequencia->getId();
        $savedSequencia = $this->em->find(get_class($sequencia), $id);
        $this->em->remove($savedSequencia);
        $this->em->flush();
        $savedSequencia = $this->em->find(get_class($sequencia), $id);
        $this->assertNull($savedSequencia);
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
        $nivelEnsino = new NivelEnsino();
        $nivelEnsino->setNome('Nivel 1');
        $nivelEnsino->setDescricao('Descricao nivel de ensino');

        return $nivelEnsino;
    }

    private function buildTipoEnsino()
    {
        $tipoEnsino = new TipoEnsino();
        $tipoEnsino->setNome('Tipo Ensino');
        $tipoEnsino->setAtivo(true);

        return $tipoEnsino;
    }

    private function buildTipoRegime()
    {
        $tipoRegime = new TipoRegime();
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