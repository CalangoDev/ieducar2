<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/09/16
 * Time: 17:43
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Usuario\Entity\Juridica;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @group Entity
 */
class TurmaTest extends EntityTestCase
{
    public function setup()
    {
        parent::setup();
    }

    /**
     * Check if filters exists
     *
     * @return Zend\InputFilter\InputFilter
     */
    public function testGetInputFilter()
    {
        $entity = new Turma();
        $if = $entity->getInputFilter();
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
        $this->assertTrue($if->has('maximoAluno'));
        $this->assertTrue($if->has('multiSeriada'));
        $this->assertTrue($if->has('ativo'));
        $this->assertTrue($if->has('horaInicial'));
        $this->assertTrue($if->has('horaFinal'));
        $this->assertTrue($if->has('horaInicioIntervalo'));
        $this->assertTrue($if->has('horaFimIntervalo'));
        $this->assertTrue($if->has('visivel'));
        $this->assertTrue($if->has('tipoBoletim'));
        $this->assertTrue($if->has('anoLetivo'));
        $this->assertTrue($if->has('dataFechamento'));
        $this->assertTrue($if->has('comodoPredio'));
        $this->assertTrue($if->has('turmaTurno'));
        $this->assertTrue($if->has('escolaSerie'));
    }

    public function testInsert()
    {
        $entity = $this->buildTurma();
        $this->em->persist($entity);
        $this->em->flush();

        $this->assertNotNull($entity->getId());
        $this->assertEquals(1, $entity->getId());

        /**
         * get row from database
         */
        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals(1, $savedEntity->getId());
    }

    public function testUpdate()
    {
        $entity = $this->buildTurma();
        $this->em->persist($entity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('4 ano', $savedEntity->getNome());
        $savedEntity->setNome('5 ano');
        $this->em->flush();
        $savedEntity = $this->em->find(get_class($entity), $entity->getId());
        $this->assertEquals('5 ano', $savedEntity->getNome());
    }

    public function testDelete()
    {
        $entity = $this->buildTurma();
        $this->em->persist($entity);
        $this->em->flush();

        $id = $entity->getId();
        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->em->remove($savedEntity);
        $this->em->flush();

        $savedEntity = $this->em->find(get_class($entity), $id);
        $this->assertNull($savedEntity);
        // TODO PAREI AQUI
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