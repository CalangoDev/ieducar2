# I-Educar v2.0

## Introdução  
  
	I-Educar v2.0 é um sistema baseado na Versão 1.0 do IEducar, porem totalmente reprogramado em Zend Framework 2.0 com Doctrine 2.0  

## Instalação

#### Usando o Postgresql como SGDB

Criar os schemas antes de rodar a Aplicação

**Schemas:**

* cadastro
* historico
* acesso


## Testes de Unidade


### Rodandos os testes

	Exemplo de uso para o modulo Usuario:

	phpunit -c module/Usuario/tests/phpunit.xml

	phpunit -c module/Usuario/tests/phpunit.xml --debug --group=Entity --filter=Pessoa

### Testes completados

#####Módulo Usuario#####

	phpunit -c module/Usuario/tests/phpunit.xml --filter=Escolaridade

	phpunit -c module/Usuario/tests/phpunit.xml --filter=EstadoCivil

	phpunit -c module/Usuario/tests/phpunit.xml --filter=Fisica

	phpunit -c module/Usuario/tests/phpunit.xml --filter=Juridica

	phpunit -c module/Usuario/tests/phpunit.xml --filter=Ocupacao

	phpunit -c module/Usuario/tests/phpunit.xml --filter=Pessoa

	phpunit -c module/Usuario/tests/phpunit.xml --filter=Religiao

	phpunit -c module/Usuario/tests/phpunit.xml --filter=Raca

#####Módulo Portal#####

	phpunit -c module/Portal/tests/phpunit.xml --filter=Funcionario

##### Módulo Drh#####

	phpunit -c module/Drh/tests/phpunit.xml --filter=Setor

##### Módulo Auth#####

	phpunit -c module/Auth/tests/phpunit.xml 

## Usando o doctrine


#####Create:#####

	**./vendor/bin/doctrine-module orm:schema-tool:create**

#####Update:#####

	**./vendor/bin/doctrine-module orm:schema-tool:update --force**

#####Drop:#####

	**./vender/bin/doctrine-module orm:schema-tool:drop --force**

## Doctrine Herança

	Usar a herança de Entidade

**Exemplo:**

	Classe Fisica extende a Classe Pessoa :D

## Padrão CamelCase ##

	Mudanças para uso de padrão CamelCase em todo o projeto, tava um mix com uso de underscore devido aos atributos das classes de persistencias