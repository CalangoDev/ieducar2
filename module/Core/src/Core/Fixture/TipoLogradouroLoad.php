<?php
namespace Core\Fixture;

use Core\Entity\TipoLogradouro;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class TipoLogradouroLoad implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $dados = array(
        	array('Corredor'),
			array('Descida'),
			array('Aeroporto'),
			array('Desvio'),
			array('Elevada'),
			array('Entrada Particular'),
			array('Escada'),
			array('Escadaria'),
			array('Estacionamento'),
			array('Esplanada'),
			array('Galeria'),
			array('Ilha'),
			array('Jardinete'),
			array('Lago'),
			array('Lagoa'),
			array('Monte'),
			array('Marina'),
			array('Vereda'),
			array('Córrego'),
			array('Passeio'),
			array('Bulevar'),
			array('Praça Esportes'),
			array('Calçada'),
			array('Colônia'),
			array('Pátio'),
			array('Túnel'),
			array('Rótula'),
			array('Estádio'),
			array('Módulo'),
			array('Servidão'),
			array('Alameda'),
			array('Alto'),
			array('Avenida'),
			array('Beco'),
			array('Chácara'),
			array('Condomínio'),
			array('Conjunto'),
			array('Divisa'),
			array('Estrada'),
			array('Feira'),
			array('Jardim'),
			array('Ladeira'),
			array('Loteamento'),
			array('Largo'),
			array('Margem'),
			array('PÇA', 'Praça'),
			array('Porto'),
			array('Parque'),
			array('Praia'),
			array('Quadra'),
			array('Recanto'),
			array('Residencial'),
			array('Retorno'),
			array('Rodovia'),
			array('Rotatória'),
			array('Rua'),
			array('SÍTIO', 'Sítio'),
			array('Terminal'),
			array('Travessa'),
			array('Unidade'),
			array('Via'),
			array('Vila'),
			array('Acampamento'),
			array('Acesso'),
			array('Via de Acesso'),
			array('Adro'),
			array('Antiga Estrada'),
			array('Área'),
			array('Área Especial'),
			array('Atalho'),
			array('Avenida Contorno'),
			array('Avenida Marginal'),
			array('Avenida Velha'),
			array('Paralela'),
			array('Entre Quadra'),
			array('Parque Municipal'),
			array('Parque Residencial'),
			array('Baixa'),
			array('Passagem'),
			array('Passagem de Pedestres'),
			array('Passagem Subterrânea'),
			array('Belvedere'),
			array('Passarela'),
			array('Bloco'),
			array('Bosque'),
			array('Ponta'),
			array('Boulevard'),
			array('Zigue-Zague'),
			array('Viela'),
			array('Viaduto'),
			array('Via Local'),
			array('Via de Pedestre'),
			array('Via Litorânea'),
			array('Via Elevado'),
			array('Via Expressa'),
			array('Via Coletora'),
			array('Caminho'),
			array('Vale'),
			array('Vala'),
			array('Travessa Particular'),
			array('Travessa Velha'),
			array('Trecho'),
			array('Trevo'),
			array('Rua de Pedestre'),
			array('Rua Particular'),
			array('Rua Velha'),
			array('Subida'),
			array('Ponte'),
			array('Rampa'),
			array('Buraco'),
			array('Quinta'),
			array('Retiro'),
			array('Cais'),
			array('Fonte'),
			array('Prolongamento'),
			array('Parada'),
			array('Setor'),
			array('Reta'),
			array('Morro'),
			array('Rodo Anel'),
			array('Ramal'),
			array('Ferrovia'),
			array('Canal'),
			array('Forte'),
			array('Distrito'),
			array('Estrada Estadual'),
			array('Estrada Municipal'),
			array('Estrada Particular'),
			array('Estrada Velha'),
			array('Estrada Vicinal'),
			array('Ciclovia'),
			array('Granja'),
			array('Fazenda'),
			array('Campo'),
			array('Favela'),
			array('Circular'),
			array('Contorno'),
			array('Rua de Ligação'),
			array('Rotatória'),
			array('Núcleo'),
			array('Estrada de Ligação'),
			array('Artéria'),
			array('Complexo Viário'),
			array('Conjunto Mutirão'),
			array('Estação'),
			array('Balão'),           
        );        
                
        $batchSize = 20;
        $i = 1;
        foreach ($dados as $key => $value):            
            
            $tp = new TipoLogradouro();             
            $tp->setDescricao($value[0]);
            
            $manager->persist($tp);

            if (($i % $batchSize) === 0) {
                
                $manager->flush();
                $manager->clear();
                
            }

            $i++;
            unset($tp);
            
        endforeach;        

        $manager->flush(); 
        $manager->clear();   	        
    }    
}