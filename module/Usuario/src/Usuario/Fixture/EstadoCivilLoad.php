<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 02/11/15
 * Time: 15:44
 */
namespace Usuario\Fixture;

use Usuario\Entity\EstadoCivil;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class EstadoCivilLoad implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {

        $estadosCivis = array(
            'Solteiro(a)',
            'Casado(a)',
            'Divorciado(a)',
            'Separado(a)',
            'Viúvo(a)',
            'Companheiro(a)'
        );

        foreach ($estadosCivis as $key => $value):

            $estadoCivil = new EstadoCivil();
            $estadoCivil->setDescricao($value);

            $manager->persist($estadoCivil);

        endforeach;

        $manager->flush();

    }

}