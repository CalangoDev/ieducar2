<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 11/11/15
 * Time: 20:38
 */
namespace Usuario\Fixture;

use Usuario\Entity\OrgaoEmissorRg;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class OrgaoEmissorRgLoad implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $dados = array(
            array('SSP', 'SSP'),
            array('MMil', 'Ministérios Militares'),
            array('MAer', 'Ministério da Aeronáutica'),
            array('MExe', 'Ministério do Exército'),
            array('MMar', 'Ministério da Marinha'),
            array('PF', 'Polícia Federal'),
            array('CIC', 'Carteira de Identidade Classista'),
            array('CRA', 'Conselho Regional de Administração'),
            array('CRB', 'Conselho Regional de Biblioteconomia'),
            array('CRC', 'Conselho Regional de Contabilidade'),
            array('CRECI', 'Conselho Regional de Corretores Imóveis'),
            array('CREA', 'Conselho Regional de Engenharia, Arquitetura e Agronomia'),
            array('CRM', 'Conselho Regional de Medicina'),
            array('CRMV', 'Conselho Regional de Medicina Veterinária'),
            array('OMB', 'Ordem dos Músicos do Brasil'),
            array('CRO', 'Conselho Regional de Odontologia'),
            array('CRP', 'Conselho Regional de Psicologia'),
            array('OAB', 'Ordem dos Advogados do Brasil'),
            array('OEmi', 'Outros Emissores'),
            array('DExt', 'Documento Estrangeiro'),
            array('CRESS', 'Conselho Regional de Assistente Social'),
            array('COREN', 'Conselho Regional de Enfermagem'),
            array('CONRE', 'Conselho Regional de Estatística'),
            array('CRF', 'Conselho Regional de Farmácia'),
            array('CREFITO', 'Conselho Regional de Fisioterapia e Terapia Ocupacional'),
            array('CRN', 'Conselho Regional de Nutrição'),
            array('CONRERP', 'Conselho Regional de Profissionais de Relações Públicas'),
            array('CORE', 'Conselho Regional de Representantes Comerciais'),
        );

        foreach ($dados as $key => $value):

            $orgaoEmissor = new OrgaoEmissorRg();
            $orgaoEmissor->setSigla($value[0]);
            $orgaoEmissor->setDescricao($value[1]);

            $manager->persist($orgaoEmissor);

            unset($orgaoEmissor);

        endforeach;

        $manager->flush();
        $manager->clear();
    }
}