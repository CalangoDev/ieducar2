<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 18/02/16
 * Time: 20:41
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class EscolaSerieTest extends EntityTestCase
{
    public function setup()
    {
        parent::setup();
    }

    /***
     * Check if filters exists
     */
    public function testGetInputFilter()
    {
        $escolaSerie = new EscolaSerie();
        $if = $escolaSerie->getInputFilter();
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(9, $if->count());
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('horaInicial'));
        $this->assertTrue($if->has('horaFinal'));
        $this->assertTrue($if->has('inicioIntervalo'));
        $this->assertTrue($if->has('fimIntervalo'));
        $this->assertTrue($if->has('bloquearEnturmacao'));
        $this->assertTrue($if->has('bloquearCadastroTurma'));
        $this->assertTrue($if->has('escola'));
        $this->assertTrue($if->has('serie'));
    }

    /**
     * test insert data
     */
    public function testInsert()
    {
        $escolaSerie = $this->buildEscolaSerie();
        $this->em->persist($escolaSerie);
        $this->em->flush();

        $this->assertNotNull($escolaSerie->getId());
        $this->assertEquals(1, $escolaSerie->getId());

        /**
         * get row from database
         */
        $savedEscolaSerie = $this->em->find(get_class($escolaSerie), $escolaSerie->getId());
        $this->assertEquals(1, $savedEscolaSerie->getId());
    }

    public function testUpdate()
    {
        $escolaSerie = $this->buildEscolaSerie();
        $this->em->persist($escolaSerie);
        $this->em->flush();

        $savedEscolaSerie = $this->em->find(get_class($escolaSerie), $escolaSerie->getId());
        $horaInicial = new \DateTime();
        $horaInicial->setTime(10, 00, 00);
        $this->assertEquals($horaInicial, $savedEscolaSerie->getHoraInicial());
        $savedEscolaSerie->setHoraInicial($horaInicial->setTime(11, 00, 00));
        $this->em->flush();
        $savedEscolaSerie = $this->em->find(get_class($escolaSerie), $savedEscolaSerie->getId());
        $this->assertEquals($horaInicial, $savedEscolaSerie->getHoraInicial());
    }

    public function testDelete()
    {
        $escolaSerie = $this->buildEscolaSerie();
        $this->em->persist($escolaSerie);
        $this->em->flush();

        $id = $escolaSerie->getId();
        $savedEscolaSerie = $this->em->find(get_class($escolaSerie), $id);
        $this->em->remove($savedEscolaSerie);
        $this->em->flush();

        $savedEscolaSerie = $this->em->find(get_class($escolaSerie), $id);
        $this->assertNull($savedEscolaSerie);
    }

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