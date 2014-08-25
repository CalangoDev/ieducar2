<?php
namespace Usuario\Fixture;

use Usuario\Entity\Raca;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class RacaLoad implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
    	
        $racas = array(
            'Brancos',
            'Negros',
            'Australóides',
            'Amarelos',
            'Indígenas',
            'Mestiços',
            'Mulato',
            'Caboclo',
            'Cafuzo',
            'Ainoco'
        );
        
        foreach ($racas as $key => $value):

            $raca = new Raca(); 
            $raca->setNome($value);
            $raca->setAtivo(true);
            $raca->setDataCadastro(new \DateTime());

            $manager->persist($raca);

        endforeach;

        $manager->flush();    	        
    }    
}