<?php
namespace Usuario\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\ORM\Mapping\PrePersist;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Usuario\Controller\IndexController;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Id\SequenceGenerator as SeqGen;


/**
 * Entidade Pessoa 
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Usuario
 * @subpackage  Pessoa
 * @version  0.1
 * @example  Classe Pessoa
 * @copyright  Copyright (c) 2013 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""cadastro"".""pessoa""")
 * @ORM\HasLifecycleCallbacks
 * 
 */
class Pessoa extends Entity implements EventSubscriber {	
	
	protected $inputFilter;    

	public function getSubscribedEvents ()
    {
        return array(
            'onFlush',
            'postFlush'
        );
    }
	
	/**
	 * @var  Int $id Identificador da entidade pessoa
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="""idpes""", type="integer", nullable=false);
	 * @ORM\GeneratedValue(strategy="SEQUENCE") 
	 * @SequenceGenerator(sequenceName="cadastro.seq_pessoa", initialValue=1, allocationSize=1)
	 */
	protected $id;

	/**	 
	 * @var  String $nome Nome da Pessoa
	 * 
	 * @ORM\Column(type="string", nullable=false, length=150)
	 */
	protected $nome;

	/**
	 * @var  DateTime $data_cad Data de cadastro da pessoa
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	protected $data_cad;

	/**
	 * @var  String $url Website
	 * @ORM\Column(type="string", length=60, nullable=true)
	 */
	protected $url;

	/**
	 * @var  String $tipo F(Fisica) ou J(Juridica)
	 * @ORM\Column(type="string", length=1)
	 */
	protected $tipo;

	/**
	 * @var  DateTime $data_rev Data de revisao do cadastro
	 * @todo  Verificar no antigo sistema a logica de quando esse dado é informado
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $data_rev;

	/**
	 * @var  String $email Email da pessoa
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $email;

	/**
	 * @var  String $situacao A(Ativo) ou P(Provisorio) ou I(Inativo)
	 * 
	 * Na versao anterior do sistema não achei em nenhum codigo alguma instrução que altere a situação para Inativo nessa tabela
	 * 
	 * @ORM\Column(type="string", length=1, nullable=false)
	 */
	protected $situacao;

	/**
	 * @var  String $origem_gravacao M(Migração) ou U(Usuário) ou C(Rotina de Confrontação) ou O(Usuário do Oscar?) Origem dos dados 
	 * 
	 * @ORM\Column(type="string", length=1, nullable=false)
	 */
	protected $origem_gravacao;

	/**
	 * @var  String $operacao I(?) ou A(?) ou E(?) - Não consegui identificar os significados, porem todos os registros são salvos 
	 * como I
	 * 
	 * @ORM\Column(type="string", length=1, nullable=false)
	 */
	protected $operacao;

	/**
	 * @var  Integer $idsis_rev - Id do Sistema porem o rev não consegui encontrar sentido
	 * 
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $idsis_rev;

	/**
	 * @var  Integer $idsis_cad - Id do sistema que cadastrou o usuario ver tabela acesso.sistema 
	 * 
	 * Na versão 1.0 não tem uma chave estrangeira nessa coluna. Algo que pode ser colocado
	 * 
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $idsis_cad;

	/**
	 * @var  Integer $idpes_cad Id da pessoa que efetuou o cadastro
	 * 
	 * @ORM\OneToMany(targetEntity="pessoa")
	 * @ORM\JoinColumn(name="idpes_cad", referencedColumnName="idpes", onDelete="SET NULL")
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $idpes_cad;

	/**
	 * @var Integer $idpes_rev Id da pessoa 
	 * 
	 * @ORM\OneToMany(targetEntity="pessoa")
	 * @ORM\JoinColumn(name="idpes_rev", referencedColumnName="idpes", onDelete="SET NULL")
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $idpes_rev;


	/**
	 * Função para gerar o timestamp para o atributo data_cad, é executada antes de salvar os dados no banco
	 * @access  public
	 * @return  void 
	 * @ORM\PrePersist
	 */
	public function timestamp()
	{
		if (is_null($this->data_cad)) {
			$this->data_cad = new \DateTime();
		}		
	}

	/**
	 * Funcao para checar se a string de operacao é diferente de I, A ou E se sim remove a variavel $this->operacao
	 * @access  public
	 * @return  Exception 
	 * @ORM\PrePersist
	 */
	public function checkOperacao()
	{
		if (($this->operacao != "I") && ($this->operacao != "A") && ($this->operacao != "E"))
			//throw new \Exception("O atributo operacao recebeu um valor inválido: \"" . $this->operacao. "\"", 1);
			throw new EntityException("O atributo operacao recebeu um valor inválido: \"" . $this->operacao. "\"", 1);
	}
	
	/**
	 * Funcao para checar se origem de gravacao é diferente de M, U, C ou O
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkOrigemGravacao()
	{
		if(($this->origem_gravacao != "M") && ($this->origem_gravacao != "U") && ($this->origem_gravacao != "C") && ($this->origem_gravacao != "O"))
			throw new EntityException("O atributo origem_gravacao recebeu um valor inválido: \"" . $this->origem_gravacao. "\"", 1);
	}

	/**
	 * Funcao para checar se o tipo é diferente de F ou J
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkTipo()
	{
		if(($this->tipo != "F") && ($this->tipo != "J"))
			throw new EntityException("O atributo tipo recebeu um valor inválido: \"" . $this->tipo. "\"", 1);
	}

	/**
	 * Funcao para checar se a situacao é diferente de A, P ou I
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkSituacao()
	{
		if(($this->situacao != "A") && ($this->situacao != "P") && ($this->situacao != "I"))
			throw new EntityException("O atributo situacao recebeu um valor inválido: \"" . $this->situacao. "\"", 1);
	}

	/**	 
	 * @var Usuario $usuario Armazena entidade usuario para usar no postFlush no insert do historico
	 */
	protected $usuario;

	/**
	 * @var Integer $oldId grava o id antes de remover ou fazer update para usar no postFlush
	 */
	protected $oldId;

	/**
	 * Funcao para gravar o historico da pessoa, depois de deletar o registro.
	 * @param OnFlushEventArgs $args Argumentos do tipo OnFlushEventArgs
	 */
	public function onFlush(OnFlushEventArgs $args)	
	{						
		$em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        /**
         * Buscando por updates
         */
        foreach ($uow->getScheduledEntityUpdates() AS $entity) {            	
        	$data = $entity->data_cad;
        	$data_formatada = $data->format('Y-m-d H:i:s');
        	$entity->data_cad = $data_formatada;
        	$this->usuario = $entity;
        	$this->oldId = $entity->id;
        }

        /**
         * Buscando por remoções de entidade
         */        
        foreach ($uow->getScheduledEntityDeletions() AS $entity) {
        	// $add = '';
         //    if (method_exists($entity, '__toString')) {
         //        $add = ' '. $entity->__toString();
         //    } elseif (method_exists($entity, 'getId')) {
         //        $add = ' com id '. $entity->getId();
         //    }
            //var_dump('Removendo entidade ' . get_class($entity) . $add . '.');  
            //die('Removendo entidade ' . get_class($entity) . $add . '.');            
    	    $data = $entity->data_cad;
	    	$data_formatada = $data->format('Y-m-d H:i:s');
	    	$entity->data_cad = $data_formatada;
	    	$this->usuario = $entity;
	    	$this->oldId = $entity->id;            
        }         
	}

	public function postFlush(PostFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork(); 				
		if (!empty($this->usuario)) {
			$historicoPessoa = new \Historico\Entity\Pessoa();
			$sequenceName = 'historico.seq_pessoa';
			$sequenceGenerator = new SeqGen($sequenceName, 1);
			$newId = $sequenceGenerator->generate($em, $historicoPessoa);
			$historicoPessoa->id = $newId;			
			$historicoPessoa->idpes = $this->oldId;
			$historicoPessoa->nome = $this->usuario->nome;
			$historicoPessoa->data_cad = new \DateTime($this->usuario->data_cad);
			$historicoPessoa->url = $this->usuario->url;			
			$historicoPessoa->tipo = $this->usuario->tipo;
			if (is_null($this->usuario->data_rev)){
				$historicoPessoa->data_rev = new \DateTime();
			} 
			// else {
			// 	$historicoPessoa->data_rev = $this->usuario->data_rev;	
			// }			
			$historicoPessoa->email = $this->usuario->email;
			$historicoPessoa->situacao = $this->usuario->situacao;
	    	$historicoPessoa->origem_gravacao = $this->usuario->origem_gravacao;
	    	$historicoPessoa->operacao = $this->usuario->operacao;
	    	$historicoPessoa->idsis_cad = $this->usuario->idsis_cad;
	    	$historicoPessoa->idsis_rev = $this->usuario->idsis_rev;
	    	$historicoPessoa->idpes_cad = $this->usuario->idpes_cad;
	    	$historicoPessoa->idpes_rev = $this->usuario->idpes_rev;
	    	$logMetadata = $em->getClassMetadata('Historico\Entity\Pessoa');
	    	$className = $logMetadata->name;
	    	$persister = $uow->getEntityPersister($className);	    	
	    	$persister->addInsert($historicoPessoa);
	    	$uow->computeChangeSet($logMetadata, $historicoPessoa);
			$postInsertIds = $persister->executeInserts();			
			// if ($postInsertIds) {
			//     foreach ($postInsertIds as $id => $entity) {
			//         $idField = $logMetadata->identifier[0];
			//         $logMetadata->reflFields[$idField]->setValue($entity, $id);
			//         $uow->addToIdentityMap($entity);
			//     }
			// }
		}
	}

	/**
	 * Funcoes de gatilhos
	 * @todo  after pessoa fonetiza
	 * @todo  after pesoa historico campo
	 * @todo  before pessoa fonetiza
	 * @todo  before pessoa historico (UPDATE) OK
	 * @todo  delete pessoa historico (DELETE) OK
	 */

	/**
	 * Populate from an array.
	 * 
	 * @param  array $data
	 */
	public function populate($data = array())
	{
		var_dump("POPULANDOOOO from an array");
		$this->id = $data['id'];
		$this->nome = $data['nome'];
		$this->data_cad = $data['data_cad'];
		$this->url = $data['url'];
		$this->tipo = $data['tipo'];
		$this->data_rev = $data['data_rev'];
		$this->email = $data['email'];
		$this->situacao = $data['situacao'];
		$this->origem_gravacao = $data['origem_gravacao'];
		$this->operacao = $data['operacao'];
		$this->idsis_rev = $data['idsis_rev'];
		$this->idsis_cad = $data['idsis_cad'];
		$this->idpes_rev = $data['idpes_rev'];
		$this->idpes_cad = $data['idpes_cad'];
	}	

	/**
	 * Configura os filtros dos campos da entidade
	 * 
	 * @return Zend\InputFilter\InputFilter
	 */
	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory = new InputFactory();

			$inputFilter->add($factory->createInput(array(
				'name' => 'id',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
				),
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
							'max' => 150,
						),
					),
				),				
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'url',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 150,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'tipo',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alpha'),
					array('name' => 'StringToUpper'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 1,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'data_rev',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					'name' => new \Zend\Validator\Date(),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'email',
				'required' => false,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'EmailAddress',
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'situacao',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alpha'),
					array('name' => 'StringToUpper'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 1,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'origem_gravacao',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alpha'),
					array('name' => 'StringToUpper'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 1,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'operacao',
				'required' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
					array('name' => 'Alpha'),
					array('name' => 'StringToUpper'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1,
							'max' => 1,
						),
					),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idsis_rev',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idsis_cad',
				'required' => false,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpes_rev',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$inputFilter->add($factory->createInput(array(
				'name' => 'idpes_cad',
				'required' => true,
				'filters' => array(
					array('name' => 'Int'),
				),
			)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}