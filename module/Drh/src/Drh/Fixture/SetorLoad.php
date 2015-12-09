<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 09/12/15
 * Time: 14:19
 */
namespace Drh\Fixture;

use Drh\Entity\Setor;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class SetorLoad extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $setor = new Setor();
        $setor->setNome('Educação');
        $setor->setSiglaSetor('EDUC');

        $manager->persist($setor);
        $manager->flush();
    }
}