<?php
namespace Core\Fixture;

use Core\Entity\Uf;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Core\Utils\Size;

class UfLoad implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $dados = array(
            
            array('AC', 'Acre', '69900', '69999'),
            array('AL', 'Alagoas', '57000', '57999'),
            array('AM', 'Amazonas', '69000', '69299'),
            array('AP', 'Amapá', '68900', '68999'),
            array('BA', 'Bahia', '40000', '48999'),
            array('CE', 'Ceará', '60000', '63999'),
            array('DF', 'Distrito Federal', '70000', '72799'),
            array('ES', 'Espírito Santo', '29000', '29999'),
            array('GO', 'Goiás', '72800', '72999'),
            array('MA', 'Maranhão', '65000', '65999'),
            array('MG', 'Minas Gerais', '30000', '39999'),
            array('MS', 'Mato Grosso do Sul', '79000', '79999'),
            array('MT', 'Mato Grosso', '78000', '78899'),
            array('PA', 'Pará', '66000', '68899'),
            array('PB', 'Paraíba', '58000', '58999'),
            array('PE', 'Pernambuco', '50000', '56999'),
            array('PI', 'Piauí', '64000', '64999'),
            array('PR', 'Paraná', '80000', '87999'),
            array('RJ', 'Rio de Janeiro', '20000', '28999'),
            array('RN', 'Rio Grande do Norte', '59000', '59999'),
            array('RO', 'Rondônia', '78900', '78999'),
            array('RR', 'Roraima', '69300', '69399'),
            array('RS', 'Rio Grande do Sul', '90000', '99999'),
            array('SC', 'Santa Catarina', '88000', '89999'),
            array('SE', 'Sergipe', '49000', '49999'),
            array('SP', 'São Paulo', '01000', '19999'),
            array('TO', 'Tocantins', '77000', '77999')

        );        
        
        /**
         * Se a codificacao do banco de dados for utf8, tirar a função utf8_decode
         * ficando $uf->setCidade($value);
         */ 
        $batchSize = 20;
        $i = 1;
        foreach ($dados as $key => $value):            
            
            $uf = new Uf(); 
            $uf->setUf($value[0]);
            $uf->setNome($value[1]);
            $uf->setCep1($value[2]);
            $uf->setCep2($value[3]);            
            
            $manager->persist($uf);
            if (($i % $batchSize) === 0) {
                $size = new Size();
                echo 'Flushing batch...' . "\n";
                echo 'Memory: ' . $size->getReadableSize(memory_get_usage()) . "\n";

                $manager->flush();
                $manager->clear();

                echo 'After batch...' . "\n";
                echo 'Memory: ' . $size->getReadableSize(memory_get_usage()) . "\n";  
            unset($size);
            }            
            $i++;
            unset($uf);
            
        endforeach;        

        $manager->flush(); 
        $manager->clear();   	        
    }    
}