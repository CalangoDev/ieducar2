<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 13/09/16
 * Time: 09:34
 */
namespace Escola\Fixture;

use Escola\Entity\TurmaTurno;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class TurmaTurnoLoad implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $dados = [
            'Integral',
            'Matutino',
            'Vespertino',
            'Noturno'
        ];


        foreach ($dados as $turno){
            $entity = new TurmaTurno();
            $entity->setNome($turno);
            $manager->persist($entity);
            unset($entity);
        }

        $manager->flush();
        $manager->clear();
        
    }
}