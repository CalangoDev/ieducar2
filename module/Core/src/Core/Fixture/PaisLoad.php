<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 0//5
 * Time: 07:45
 */
namespace Core\Fixture;

use Core\Entity\Pais;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class PaisLoad implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $dados = array(
                'Abissínia',
                'Açores',
                'Afar Francês',
                'Afeganistão',
                'Albânia',
                'Alemanha',
                'Alto Volta',
                'Andorra', 
                'Angola', 
                'Antártica Francesa', 
                'Antártico Argentino', 
                'Antártico Britânico, Território', 
                'Antártico Chileno', 
                'Antártico Noruegues', 
                'Antígua E. Dep. Barbuda', 
                'Antilhas Holandesas', 
                'Apátrida', 
                'Arábia Saudita', 
                'Argélia', 
                'Argentina', 
                'Armênia', 
                'Arquipélago de Bismark', 
                'Arquipélago Manahiki', 
                'Arquipélago Midway', 
                'Aruba', 
                'Ascensão e Tristão da Cunha',
                'Ashmore e Cartier', 
                'Austrália', 
                'Áustria', 
                'Azerbaijão', 
                'Bahrein', 
                'Bangladesh', 
                'Barbados', 
                'Bashkista', 
                'Bechuanalândia', 
                'Bélgica', 
                'Belize', 
                'Benin', 
                'Bermudas', 
                'Bhutan', 
                'Birmânia', 
                'Bolívia', 
                'Bósnia Herzegovina', 
                'Botsuana', 
                'Brasil', 
                'Brunei', 
                'Bulgária', 
                'Burkina Fasso', 
                'Burundi', 
                'Buryat', 
                'Cabo Verde', 
                'Camarões', 
                'Canadá', 
                'Carélia', 
                'Catar', 
                'Cazaquistão', 
                'Ceilão', 
                'Ceuta e Melilla', 
                'Chade', 
                'Chechen Ingusth', 
                'Chile', 
                'China', 
                'China (taiwan)', 
                'Chipre', 
                'Chuvash', 
                'Cingapura', 
                'Colômbia', 
                'Comunidade das Bahamas', 
                'Comunidade Dominicana', 
                'Congo', 
                'Coréia', 
                'Costa do Marfim', 
                'Costa Rica', 
                'Coveite', 
                'Croácia', 
                'Cuba', 
                'Curaçao', 
                'Dagesta', 
                'Daomé', 
                'Dependência de Ross', 
                'Dinamarca', 
                'Djibuti', 
                'Eire', 
                'Emirados árabes Unidos', 
                'Equador', 
                'Escócia', 
                'Eslováquia', 
                'Eslovênia', 
                'Espanha', 
                'Estado da Cidade do Vaticano', 
                'Estados Assoc. das Antilhas', 
                'Estados Unidos da América (eua)', 
                'Estônia', 
                'Etiópia', 
                'Filipinas', 
                'Finlândia', 
                'França', 
                'Gâmbia', 
                'Gana',
                'Gaza',
                'Geórgia',
                'Gibraltar',
                'Gorno Altai',
                'Grã-bretanha',
                'Granada',
                'Grécia',
                'Groenlândia',
                'Guam',
                'Guatemala',
                'Guiana Francesa',
                'Guiné',
                'Guiné Bissau',
                'Guiné Equatorial',
                'Holanda',
                'Honduras',
                'Honduras Britânicas',
                'Hong-kong',
                'Hungria',
                'Iemen',
                'Iemen do Sul',
                'Ifni',
                'Ilha Johnston e Sand',
                'Ilha Milhos',
                'Ilhas Baker',
                'Ilhas Baleares',
                'Ilhas Canárias',
                'Ilhas Cantão e Enderburg',
                'Ilhas Carolinas',
                'Ilhas Christmas',
                'Ilhas Comores',
                'Ilhas Cook',
                'Ilhas Cosmoledo (lomores)',
                'Ilhas de Man',
                'Ilhas do Canal',
                'Ilhas do Pacífico',
                'Ilhas Falklands',
                'Ilhas Faroes',
                'Ilhas Gilbert',
                'Ilhas Guadalupe',
                'Ilhas Howland e Jarvis',
                'Ilhas Kingman Reef',
                'Ilhas Linha',
                'Ilhas Macdonal e Heard',
                'Ilhas Macquaire',
                'Ilhas Malvinas',
                'Ilhas Marianas',
                'Ilhas Marshall',
                'Ilhas Niue',
                'Ilhas Norfolk',
                'Ilhas Nova Caledônia',
                'Ilhas Novas Hebridas',
                'Ilhas Palau',
                'Ilhas Páscoa',
                'Ilhas Pitcairin',
                'Ilhas Salomão',
                'Ilhas Santa Cruz',
                'Ilhas Serranas',
                'Ilhas Tokelau',
                'Ilhas Turca',
                'Ilhas Turks e Caicos',
                'Ilhas Virgens Americanas',
                'Ilhas Virgens Britânicas',
                'Ilhas Wake',
                'Ilhas Wallis e Futuna',
                'Índia',
                'Indonésia',
                'Inglaterra',
                'Irã',
                'Iraque',
                'Irlanda',
                'Irlanda do Norte',
                'Islândia',
                'Israel',
                'Itália',
                'Iugoslávia',
                'Jamaica',
                'Japão',
                'Jordânia',
                'Kabardino Balkar',
                'Kalimatan',
                'Kalmir',
                'Kara Kalpak',
                'Karachaevocherkess',
                'Khakass',
                'Kmer/camboja',
                'Komi',
                'Kuwait',
                'Laos',
                'Lesoto',
                'Letônia',
                'Líbano',
                'Libéria',
                'Líbia',
                'Liechtenstein',
                'Lituânia',
                'Luxemburgo',
                'Macau',
                'Madagascar',
                'Madeira',
                'Malásia',
                'Malawi',
                'Maldivas',
                'Mali',
                'Mari',
                'Marrocos',
                'Martinica',
                'Mascate',
                'Maurício',
                'Mauritânia',
                'México',
                'Mianma',
                'Moçambique',
                'Moldávia',
                'Mônaco',
                'Mongólia',
                'Monte Serrat',
                'Montenegro',
                'Namíbia',
                'Nauru',
                'Nepal',
                'Nguane',
                'Nicarágua',
                'Nigéria',
                'Noruega',
                'Nova Guiné',
                'Nova Zelândia',
                'Oman',
                'Ossetia Setentrional',
                'País de Gales',
                'Países Baixos',
                'Palestina',
                'Panamá',
                'Panamá - Zona do Canal',
                'Papua Nova Guiné',
                'Paquistão',
                'Paraguai',
                'Peru',
                'Polinésia Francesa',
                'Polônia',
                'Porto Rico',
                'Portugal',
                'Praças Norte Africanas',
                'Protetor do Sudoeste Africano',
                'Quênia',
                'Quirguistão',
                'Quitasueno',
                'República Árabe do Egito',
                'República Centro Africana',
                'República da África do Sul',
                'República da Bielorrússia',
                'República da Macedônia',
                'República de El Salvador',
                'República de Fiji',
                'República de Malta',
                'República do Gabão',
                'República do Haiti',
                'República do Níger',
                'República Dominicana',
                'República Guiana',
                'República Tcheca',
                'Reunião',
                'Rodésia (zimbábwe)',
                'Romênia',
                'Roncador',
                'Ruanda',
                'Ruiquiu',
                'Rússia',
                'Saara Espanhol',
                'Sabah',
                'Samoa Americana',
                'Samoa Ocidental',
                'San Marino',
                'Santa Helena',
                'Santa Lúcia',
                'São Cristóvão',
                'São Tomé e Príncipe',
                'São Vicente',
                'Sarawak',
                'Senegal',
                'Sequin',
                'Serra Leoa',
                'Sérvia',
                'Seychelles',
                'Síria',
                'Somália, República',
                'Sri-lanka',
                'St. Pierre et Miquelon',
                'Suazilândia',
                'Sudão',
                'Suécia',
                'Suíça',
                'Suriname',
                'Svalbard e Jan Mayer',
                'Tadjiquistão',
                'Tailândia',
                'Tanganica',
                'Tanzânia',
                'Tartaria',
                'Tchecoslováquia',
                'Terr. Antártico da Austrália',
                'Terras Austrais',
                'Territ. Britânico do Oceano índico',
                'Território de Cocos',
                'Território de Papua',
                'Timor',
                'Togo',
                'Tonga',
                'Transkei',
                'Trégua, Estado',
                'Trinidad e Tobago',
                'Tunísia',
                'Turcomenistão',
                'Turquia',
                'Tuvalu',
                'Tuvin',
                'Ucrânia',
                'Udmurt',
                'Uganda',
                'União Soviética',
                'Uruguai',
                'Uzbequistão',
                'Venezuela',
                'Vietnã do Norte',
                'Vietnã do Sul',
                'Yakut',
                'Zaire',
                'Zâmbia',
                'Zimbábwe'
        );

        foreach ($dados as $dado) {
            $pais = new Pais();
            $pais->setNome($dado);
            $manager->persist($pais);
            $manager->flush();
            unset($pais);
        }

        $manager->clear();
    }
}