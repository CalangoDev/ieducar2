# Criando novas funcionalidades no i-Educar 2.0

#### Passo a passo

* Criar ou Definir o módulo ao qual a funcionalidade vai pertencer
* Criar uma Entity (entidade) representando a tabela no banco de dados
* Criar um Form, responsável pela captação dos dados
* Criar o Controller Responsável para exibição do formulário e gravação dos dados
* Criar os Testes para Entidades e Controllers criados
* Criar as Views das Actions dos Controllers

## Visão Geral

Utilizaremos como exemplo a necessidade de criação dos Componentes Curriculares, que faz parte do processo da Escola, ou seja, deve ser associada ao módulo Escola que já existe no sistema.

## Criar uma Entity (entidade) representando a tabela no banco de dados

Criaremos o arquivo chamado ComponenteCurricular.php e salvaremos no diretório module/Escola/src/Escola/Entity podemos usar o comando abaixo se estiver usando um terminal bash no linux ou mac os

	$ touch module/Escola/src/Escola/Entity/ComponenteCurricular.php

E depois editar com seu editor favorito:

    $ vim module/Escola/src/Escola/Entity/ComponenteCurricular.php
    
    <?php
    /**
     * Created by PhpStorm.
     * User: eduardojunior
     * Date: 14/01/16
     * Time: 19:40
     */
    namespace Escola\Entity;
    
    use Core\Entity\Entity;
    use Doctrine\ORM\Mapping as ORM;
    use Zend\InputFilter\InputFilter;
    use Zend\InputFilter\Factory as InputFactory;
    
    /**
     * Entidade ComponenteCurricular
     *
     * @author Eduardo Junior <ej@calangodev.com.br>
     * @category Entidade
     * @package Escola
     * @subpackage ComponenteCurricular
     * @version 0.1
     * @example Class ComponenteCurricular
     * @copyright Copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
     *
     * @ORM\Entity
     * @ORM\Table(name="escola_componente_curricular")
     */
    class ComponenteCurricular extends Entity
    {
        /**
         * @var Int $id
         *
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;
    
        /**
         * @var String $nome
         *
         * @ORM\Column(type="string", length=200)
         */
        protected $nome;
    
        /**
         * @var String $abreviatura
         *
         * @ORM\Column(type="string", length=15)
         */
        protected $abreviatura;
    
        /**
         * @var smallint $tipoBase
         *
         * @ORM\Column(type="smallint", length=5)
         */
        protected $tipoBase;
    
        /**
         * @var int $areaConhecimento
         *
         * @ORM\ManyToOne(targetEntity="Escola\Entity\AreaConhecimento", cascade={"persist"})
         * @ORM\JoinColumn(nullable=false)
         */
        protected $areaConhecimento;
    
        /**
         * getters and setters
         */
        public function getId()
        {
            return $this->id;
        }
    
        public function getNome()
        {
            return $this->nome;
        }
    
        public function setNome($nome)
        {
            $this->nome = $this->valid('nome', $nome);
        }
    
        public function getAbreviatura()
        {
            return $this->abreviatura;
        }
    
        public function setAbreviatura($abreviatura)
        {
            $this->abreviatura = $this->valid('abreviatura', $abreviatura);
        }
    
        public function getTipoBase()
        {
            return $this->tipoBase;
        }
    
        public function setTipoBase($tipoBase)
        {
            $this->tipoBase = $this->valid('tipoBase', $tipoBase);
        }
    
        public function getAreaConhecimento()
        {
            return $this->areaConhecimento;
        }
    
        public function setAreaConhecimento(\Escola\Entity\AreaConhecimento $areaConhecimento)
        {
            $this->areaConhecimento = $areaConhecimento;
        }
    
        protected $inputFilter;
    
        /**
         * Configura os filtros dos campos da entidade
         *
         * @return Zend\InputFilter\InputFilter
         */
        public function getInputFilter()
        {
            if (!$this->inputFilter){
                $inputFilter = new InputFilter();
                $factory = new InputFactory();
    
                $inputFilter->add($factory->createInput(array(
                    'name' => 'id',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Int')
                    )
                )));
    
                $inputFilter->add($factory->createInput(array(
                    'name' => 'nome',
                    'required' => true,
                    'filters'	=>	array(
                        array('name'	=>	'StripTags'),
                        array('name'	=>	'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 200,
                            ),
                        ),
                    ),
                )));
    
                $inputFilter->add($factory->createInput(array(
                    'name' => 'abreviatura',
                    'required' => true,
                    'filters'	=>	array(
                        array('name'	=>	'StripTags'),
                        array('name'	=>	'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 15,
                            ),
                        ),
                    ),
                )));
    
                $inputFilter->add($factory->createInput(array(
                    'name' => 'tipoBase',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'Int')
                    )
                )));
    
                $inputFilter->add($factory->createInput(array(
                    'name' => 'areaConhecimento',
                    'required' => true,
                )));
    
                $this->inputFilter = $inputFilter;
    
            }
            return $this->inputFilter;
        }
    }
    
## Criar um Form

