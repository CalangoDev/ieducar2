# I-Educar v2.0


## Introdução  
  
I-Educar v2.0 é um sistema baseado na Versão 1.0 do IEducar, porem totalmente reprogramado em Zend Framework 2.0 com Doctrine 2.0  

## Instalação

### Usando o Postgresql como SGDB


**Criar os schemas antes de rodar a Aplicação**

Schemas:

* cadastro
* historico
* acesso


## Testes de Unidade


### Rodandos os testes

Exemplo de uso para o modulo Usuario:  

phpunit -c module/Usuario/tests/phpunit.xml

phpunit -c module/Usuario/tests.phpunit.xml --debug --group=Entity --filter=Pessoa

## Usando o doctrine

** Create:

./vendor/bin/doctrine-module orm:schema-tool:create 

** Update:

./vendor/bin/doctrine-module orm:schema-tool:update  --force

** Drop:
./vender/bin/doctrine-module orm:schema-tool:drop --force

## Doctrine Herança

Usar a herança de Entidade
exemplo: 

Classe Fisica extende a Classe Pessoa :D