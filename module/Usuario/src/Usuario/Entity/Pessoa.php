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
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Id\SequenceGenerator as SeqGen;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Index;

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
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorMap({"P" = "Pessoa", "F" = "Fisica", "J" = "Juridica"})
 * @ORM\DiscriminatorColumn(name="tipo", type="string")
 */
class Pessoa extends Entity implements EventSubscriber 
{	
	
	protected $inputFilter;    

	public function __construct() {
		//$this->filho = new ArrayCollection();
	}

	public function getSubscribedEvents ()
    {
        return array(
            'onFlush',
            'postFlush',
            'preUpdate',
            //'postUpdate'
        );
    }
	
	/**
	 * @var  Int $id Identificador da entidade pessoa
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="idpes", type="integer", nullable=false);
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
	 * ORM\Column(name="tipo", type="string", length=1)
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
	 * como I inserir?
	 * como A alterar?
	 * como E excluir?
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
	 * @todo  coluna tem relacionamento com a tabela acesso.sistema falta ajustar isso e ajustar no teste
	 * 
	 * @ORM\Column(type="integer", nullable=false)
	 */
	protected $idsis_cad;

	/**
	 * @var  Integer $idpes_cad Id da pessoa que efetuou o cadastro
	 * 	ONE TO ONE 
	 * tem um ID DE PESSOA QUE CADASTROU PARA CADA ID DO USUARIO CADASTRADO
	 * 	 
	 * 
	 * ORM\ManyToOne(targetEntity="pessoa", inversedBy="pessoa_cad")
	 * ORM\JoinColumn(name="idpes_cad", referencedColumnName="idpes")
	 */
	//protected $idpes_cad;
	//
	/**
	 * @ORM\ManyToOne(targetEntity="Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_cad", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa_cad;

	/**
	 * ORM\OneToMany(targetEntity="pessoa", mappedBy="idpes_cad", cascade={"persist"})	 
	 * cascade={"remove"} ao deletar registro filho ele identifica o pai e remove o pai tbm. 
	 */
	//protected $pessoa_cad;

	/**
	 * @var Integer $idpes_rev Id da pessoa 
	 * 
	 * ORM\OneToOne(targetEntity="pessoa", inversedBy="pessoa_rev")
	 * ORM\JoinColumn(name="idpes_rev", referencedColumnName="idpes", onDelete="SET NULL")	 
	 */
	//protected $idpes_rev;//protected $idpes_rev;

	/**
	 * ORM\OneToMany(targetEntity="pessoa", mappedBy="idpes_rev", cascade={"persist"})
	 */
	//protected $pessoa_rev;
	

	/**
	 * @ORM\ManyToOne(targetEntity="Pessoa", cascade={"persist"})
	 * @ORM\JoinColumn(name="idpes_rev", referencedColumnName="idpes", onDelete="SET NULL")
	 */
	protected $pessoa_rev;

	/**
	 * ORM\OneToOne(targetEntity="Fisica", mappedBy="pessoa", cascade={"persist"})	 
	 */
	//protected $fisica;

	/**
	 * ////ORM\OneToMany(targetEntity="pessoa", cascade={"persist", "remove"}, mappedBy="forum_opniao")
	 */
	//protected $filhos;

	/**
	 * //ORM\ManyToOne(targetEntity="pessoa", inversedBy="filhos")
	 * //ORM\JoinColumn(name="forum_opiniao", referencedColumnName="idpes")
	 * //ORM\Column(type="integer", nullable=false)
	 */
	//protected $forum_opiniao;

	/**
	 * Função para gerar o timestamp para o atributo data_cad, é executada antes de salvar os dados no banco
	 * @access  public
	 * @return  void 
	 * @ORM\PrePersist
	 */
	public function timestamp()
	{
		if (is_null($this->getDataCad())) {			
			$this->setDataCad(new \DateTime());
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
		if (($this->getOperacao() != "I") && ($this->getOperacao() != "A") && ($this->getOperacao() != "E"))
			//throw new \Exception("O atributo operacao recebeu um valor inválido: \"" . $this->operacao. "\"", 1);
			throw new EntityException("O atributo operacao recebeu um valor inválido: \"" . $this->getOperacao(). "\"", 1);
	}
	
	/**
	 * Funcao para checar se origem de gravacao é diferente de M, U, C ou O
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkOrigemGravacao()
	{	
		if(($this->getOrigemGravacao() != "M") && ($this->getOrigemGravacao() != "U") && ($this->getOrigemGravacao() != "C") && ($this->getOrigemGravacao() != "O"))
			throw new EntityException("O atributo origem_gravacao recebeu um valor inválido: \"" . $this->getOrigemGravacao(). "\"", 1);
	}

	/**
	 * Funcao para checar se o tipo é diferente de F ou J
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkTipo()
	{		
		if(($this->getTipo() != "F") && ($this->getTipo() != "J") && ($this->getTipo() != "" ))
			throw new EntityException("O atributo tipo recebeu um valor inválido: \"" . $this->getTipo(). "\"", 1);
	}

	/**
	 * Funcao para checar se a situacao é diferente de A, P ou I
	 * @access  public
	 * @return  Exception
	 * @ORM\PrePersist
	 */
	public function checkSituacao()
	{
		if(($this->getSituacao() != "A") && ($this->getSituacao() != "P") && ($this->getSituacao() != "I") && ($this->getSituacao() != "") )
			throw new EntityException("O atributo situacao recebeu um valor inválido: \"" . $this->getSituacao() . "\"", 1);
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
        // foreach ($uow->getScheduledEntityUpdates() AS $entity) {        	
        // 	$data = $entity->data_cad;
        // 	$data_formatada = $data->format('Y-m-d H:i:s');
        // 	$entity->setDataCad = $data_formatada;        	
        // 	$this->usuario = $entity;
        // 	//$teste = $em->find('Usuario\Entity\Pessoa', $entity->id);        	
        // 	$this->oldId = $entity->getId();
        // }

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
	        //    var_dump('Removendo entidade ' . get_class($entity) . $add . '.');  
            //die('Removendo entidade ' . get_class($entity) . $add . '.');            
	    	//    $data = $entity->data_cad;
	    	// $data_formatada = $data->format('Y-m-d H:i:s');
	    	// $entity->data_cad = $data_formatada;
	    	$this->usuario = $entity;    	
	    	$this->oldId = $entity->id; 	    	
        }         
	}

	public function preUpdate(PreUpdateEventArgs  $args)
	{
		/**
		 * Pegando dados do registro antes de atualizar e salvando na variavel $this->usuario
		 * para Salvar na tabela historico no metodo postFlush()
		 */		
		$entity = $args->getEntity();
		$this->usuario = $entity;
		$this->oldId = $entity->id;

		($args->hasChangedField('nome')) ? $this->usuario->nome = $args->getOldValue('nome') : null;		
		($args->hasChangedField('url')) ? $this->usuario->url = $args->getOldValue('url') : null;
		($args->hasChangedField('tipo')) ? $this->usuario->tipo = $args->getOldValue('tipo') : null;
		($args->hasChangedField('email')) ? $this->usuario->email = $args->getOldValue('email') : null;
		($args->hasChangedField('situacao')) ? $this->usuario->situacao = $args->getOldValue('situacao') : null;
		($args->hasChangedField('origem_gravacao')) ? $this->usuario->origem_gravacao = $args->getOldValue('origem_gravacao') : null;
		($args->hasChangedField('operacao')) ? $this->usuario->operacao = $args->getOldValue('operacao') : null;
		($args->hasChangedField('idsis_cad')) ? $this->usuario->idsis_cad = $args->getOldValue('idsis_cad') : null;
		($args->hasChangedField('idsis_rev')) ? $this->usuario->idsis_rev = $args->getOldValue('idsis_rev') : null;
		($args->hasChangedField('pessoa_cad')) ? $this->usuario->pessoa_cad = $args->getOldValue('pessoa_cad') : null;
		($args->hasChangedField('pessoa_rev')) ? $this->usuario->pessoa_rev = $args->getOldValue('pessoa_rev') : null;
		/**
		 * Se for uma entidade do tipo fisica
		 * verifica os campos alterados do update
		 */
		if (get_class($entity) == 'Usuario\Entity\Fisica'){
			($args->hasChangedField('data_nasc')) ? $this->usuario->data_nasc = $args->getOldValue('data_nasc') : null;
			($args->hasChangedField('sexo')) ? $this->usuario->sexo = $args->getOldValue('sexo') : null;
			($args->hasChangedField('data_uniao')) ? $this->usuario->data_uniao = $args->getOldValue('data_uniao') : null;
			($args->hasChangedField('data_obito')) ? $this->usuario->data_obito = $args->getOldValue('data_obito') : null;
			($args->hasChangedField('nacionalidade')) ? $this->usuario->nacionalidade = $args->getOldValue('nacionalidade') : null;
			($args->hasChangedField('data_chegada_brasil')) ? $this->usuario->data_chegada_brasil = $args->getOldValue('data_chegada_brasil') : null;
			($args->hasChangedField('ultima_empresa')) ? $this->usuario->ultima_empresa = $args->getOldValue('ultima_empresa') : null;
			($args->hasChangedField('nome_mae')) ? $this->usuario->nome_mae = $args->getOldValue('nome_mae') : null;
			($args->hasChangedField('nome_pai')) ? $this->usuario->nome_pai = $args->getOldValue('nome_pai') : null;
			($args->hasChangedField('nome_conjuge')) ? $this->usuario->nome_conjuge = $args->getOldValue('nome_conjuge') : null;
			($args->hasChangedField('nome_responsavel')) ? $this->usuario->nome_responsavel = $args->getOldValue('nome_responsavel') : null;
			($args->hasChangedField('justificativa_provisorio')) ? $this->usuario->justificativa_provisorio = $args->getOldValue('justificativa_provisorio') : null;
			($args->hasChangedField('ref_cod_sistema')) ? $this->usuario->ref_cod_sistema = $args->getOldValue('ref_cod_sistema')  : null;
			($args->hasChangedField('cpf')) ? $this->usuario->cpf = $args->getOldValue('cpf') : null;
			($args->hasChangedField('pessoa_mae')) ? $this->usuario->pessoa_mae = $args->getOldValue('pessoa_mae') : null;
			($args->hasChangedField('pessoa_pai')) ? $this->usuario->pessoa_pai = $args->getOldValue('pessoa_pai') : null;
			($args->hasChangedField('pessoa_responsavel')) ? $this->usuario->pessoa_responsavel = $args->getOldValue('pessoa_responsavel') : null;
			($args->hasChangedField('municipio_nascimento')) ? $this->usuario->municipio_nascimento = $args->getOldValue('municipio_nascimento') : null;
			($args->hasChangedField('pais_estrangeiro')) ? $this->usuario->pais_estrangeiro = $args->getOldValue('pais_estrangeiro') : null;
			($args->hasChangedField('escola')) ? $this->usuario->escola = $args->getOldValue('escola') : null;
			($args->hasChangedField('estado_civil')) ? $this->usuario->estado_civil = $args->getOldValue('estado_civil') : null;
			($args->hasChangedField('pessoa_conjuge')) ? $this->usuario->pessoa_conjuge = $args->getOldValue('pessoa_conjuge') : null;
			($args->hasChangedField('ocupacao')) ? $this->usuario->ocupacao = $args->getOldValue('ocupacao') : null;
			($args->hasChangedField('ref_cod_religiao')) ? $this->usuario->ref_cod_religiao = $args->getOldValue('ref_cod_religiao') : null;
		}
		

	}
	// public function postUpdate(LifecycleEventArgs $args)
	// {
	// 	var_dump("POST UPDATE EXEC SUCESS");
	// 	$em = $args->getEntityManager();
	// 	$uow = $em->getUnitOfWork();
	// 	$entity = $args->getEntity();		
	// 	if ($args->hasChangedField('nome')){
	// 		var_dump("MUDOU O NOME");
	// 	}
	// 	//var_dump($args);
	// 	$historicoPessoa = new \Historico\Entity\Pessoa();
	// 	$sequenceName = 'historico.seq_pessoa';
	// 	$sequenceGenerator = new SeqGen($sequenceName, 1);
	// 	$newId = $sequenceGenerator->generate($em, $historicoPessoa);
	// 	$historicoPessoa->setId($newId);
	// 	$historicoPessoa->setIdpes($entity->id);

	// 	// $historicoPessoa->setNome($args->getOldValue('nome'));
	// 	// $historicoPessoa->setDataCad($args->getOldValue('data_cad'));
	// 	// $historicoPessoa->setUrl($args->getOldValue('url'));
	// 	// $historicoPessoa->setTipo($args->getOldValue('tipo'));
		
		
	// }

	public function postFlush(PostFlushEventArgs $args)
	{
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork(); 				
		if (!empty($this->usuario)) {			
			// var_dump("ativando historico");
			$historicoPessoa = new \Historico\Entity\Pessoa();
			$sequenceName = 'historico.seq_pessoa';
			$sequenceGenerator = new SeqGen($sequenceName, 1);
			$newId = $sequenceGenerator->generate($em, $historicoPessoa);

			$historicoPessoa->setId($newId);
			$historicoPessoa->setIdpes($this->oldId);
			$historicoPessoa->setNome($this->usuario->nome);//->format('Y-m-d H:i:s')
			//$historicoPessoa->data_cad = new \DateTime($this->usuario->getDataCad());
			$historicoPessoa->setDataCad($this->usuario->data_cad);
			$historicoPessoa->setUrl($this->usuario->url);			
			//$historicoPessoa->setTipo($em->getClassMetadata(get_class($this->usuario))->discriminatorValue);
			$historicoPessoa->setTipo($this->usuario->tipo);
			if (is_null($this->usuario->data_rev)){
				$historicoPessoa->setDataRev(new \DateTime());
			} 
			// // else {
			// // 	$historicoPessoa->data_rev = $this->usuario->data_rev;	
			// // }			
			$historicoPessoa->setEmail($this->usuario->email);
			$historicoPessoa->setSituacao($this->usuario->situacao);
	    	$historicoPessoa->setOrigemGravacao($this->usuario->origem_gravacao);
	    	$historicoPessoa->setOperacao($this->usuario->operacao);
	    	$historicoPessoa->setIdsisCad($this->usuario->idsis_cad);
	    	$historicoPessoa->setIdsisRev($this->usuario->idsis_rev);	    	
	    	$historicoPessoa->setPessoaCad($this->usuario->pessoa_cad);	    	
	    	$historicoPessoa->setPessoaRev($this->usuario->pessoa_rev);	 


	    	$logMetadata = $em->getClassMetadata('Historico\Entity\Pessoa');
	    	$className = $logMetadata->name;
	    	$persister = $uow->getEntityPersister($className);	    	
	    	$persister->addInsert($historicoPessoa);
	    	$uow->computeChangeSet($logMetadata, $historicoPessoa);
			$postInsertIds = $persister->executeInserts();	

			if (get_class($this->usuario) == 'Usuario\Entity\Fisica'){				
				$historicoFisica = new \Historico\Entity\Fisica();
				$sequenceName = 'historico.seq_fisica';
				$sequenceGenerator = new SeqGen($sequenceName, 1);
				$newId = $sequenceGenerator->generate($em, $historicoFisica);

				$historicoFisica->setId($newId);
				$historicoFisica->setIdpes($this->oldId);
				$historicoFisica->setDataNasc($this->usuario->data_nasc);
				$historicoFisica->setSexo($this->usuario->sexo);
				$historicoFisica->setDataUniao($this->usuario->data_uniao);
				$historicoFisica->setDataObito($this->usuario->data_obito);
				$historicoFisica->setNacionalidade($this->usuario->nacionalidade);
				$historicoFisica->setDataChegadaBrasil($this->usuario->data_chegada_brasil);
				$historicoFisica->setUltimaEmpresa($this->usuario->ultima_empresa);
				$historicoFisica->setNomeMae($this->usuario->nome_mae);
				$historicoFisica->setNomePai($this->usuario->nome_pai);
				$historicoFisica->setNomeConjuge($this->usuario->nome_conjuge);
				$historicoFisica->setNomeResponsavel($this->usuario->nome_responsavel);
				$historicoFisica->setJustificativaProvisorio($this->usuario->justificativa_provisorio);
				$historicoFisica->setRefCodSistema($this->usuario->ref_cod_sistema);
				$historicoFisica->setCpf($this->usuario->cpf);				
				$historicoFisica->setPessoaMae($this->usuario->pessoa_mae);
				$historicoFisica->setPessoaPai($this->usuario->pessoa_pai);
				$historicoFisica->setPessoaResponsavel($this->usuario->pessoa_responsavel);
				$historicoFisica->setMunicipioNascimento($this->usuario->municipio_nascimento);
				$historicoFisica->setPaisEstrangeiro($this->usuario->pais_estrangeiro);
				$historicoFisica->setEscola($this->usuario->escola);
				$historicoFisica->setEstadoCivil($this->usuario->estado_civil);
				$historicoFisica->setPessoaConjuge($this->usuario->pessoa_conjuge);
				$historicoFisica->setOcupacao($this->usuario->ocupacao);
				$historicoFisica->setRefCodReligiao($this->usuario->ref_cod_religiao);
				$logMetadata = $em->getClassMetadata('Historico\Entity\Fisica');
				$className = $logMetadata->name;
				$persister = $uow->getEntityPersister($className);
				$persister->addInsert($historicoFisica);
				$uow->computeChangeSet($logMetadata, $historicoFisica);
				$postInsertIds = $persister->executeInserts();				
			}			
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
	public function populate($data)
	{
		var_dump("POPULANDOOOO from an array");
		$this->setId(isset($data['id']) ? $data['id'] : null);
		$this->setNome(isset($data['nome']) ? $data['nome'] : null);
		$this->setDataCad(isset($data['data_cad']) ? $data['data_cad'] : null);
		if (!empty($data['url']))
			$this->setUrl(isset($data['url']) ? $data['url'] : null);
		$this->setTipo(isset($data['tipo']) ? $data['tipo'] : null);
		$this->setDataRev(isset($data['data_rev']) ? $data['data_rev'] : null);		
		if (!empty($data['email']))
			$this->setEmail(isset($data['email']) ? $data['email'] : null);
		$this->setSituacao(isset($data['situacao']) ? $data['situacao'] : null);
		//$this->setOrigemGravacao(isset($data['origem_gravacao']) ? $data['origem_gravacao'] : null);
		//$this->setOperacao(isset($data['operacao']) ? $data['operacao'] : null);
		$this->setIdsisRev(isset($data['idsis_rev']) ? $data['idsis_rev'] : null);
		//$this->setIdsisCad(isset($data['idsis_cad']) ? $data['idsis_cad'] : null);
		$this->setPessoaCad(isset($data['pessoa_cad']) ? $data['pessoa_cad'] : null);
		$this->setPessoaRev(isset($data['pessoa_rev']) ? $data['pessoa_rev'] : null);	
	}	

	public function setData($data)
	{				
		$this->setId(isset($data['id']) ? $data['id'] : null);
		$this->setNome(isset($data['nome']) ? $data['nome'] : null);
		$this->setDataCad(isset($data['data_cad']) ? $data['data_cad'] : null);
		if (!empty($data['url']))
			$this->setUrl(isset($data['url']) ? $data['url'] : null);
		$this->setTipo(isset($data['tipo']) ? $data['tipo'] : null);
		$this->setDataRev(isset($data['data_rev']) ? $data['data_rev'] : null);		
		if (!empty($data['email']))
			$this->setEmail(isset($data['email']) ? $data['email'] : null);
		$this->setSituacao(isset($data['situacao']) ? $data['situacao'] : null);
		//$this->setOrigemGravacao(isset($data['origem_gravacao']) ? $data['origem_gravacao'] : null);
		//$this->setOperacao(isset($data['operacao']) ? $data['operacao'] : null);
		$this->setIdsisRev(isset($data['idsis_rev']) ? $data['idsis_rev'] : null);
		//$this->setIdsisCad(isset($data['idsis_cad']) ? $data['idsis_cad'] : null);
		$this->setPessoaCad(isset($data['pessoa_cad']) ? $data['pessoa_cad'] : null);
		$this->setPessoaRev(isset($data['pessoa_rev']) ? $data['pessoa_rev'] : null);	
	}

	// public function exchangeArray($data)
 //    {
 //    	var_dump("AQUI exchangearray");
 //    }

	/**
	 * Getters and Setters
	 * 
	 * nos setters usar a funcao $this->valid($property, $value)
	 * 
	 * funcao verifica se existe filtros
	 */
	
	public function getId()
	{
		return $this->id;
	}

	public function setId($value)
	{
		$this->id = $this->valid("id", $value);
	}

	public function getNome()
	{
		return $this->nome;
	}

	public function setNome($value)
	{
		$this->nome = $this->valid("nome", $value);
	}

	public function getDataCad()
	{
		return $this->data_cad;
	}

	public function setDataCad($value)
	{
		$this->data_cad = $this->valid("data_cad", $value);
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function setUrl($url)
	{
		$this->url = $this->valid("url", $url);		
	}

	public function getTipo() 
	{		
	    return $this->tipo;
	}
	
	public function setTipo($value) 
	{
	    $this->tipo = $this->valid("tipo", $value);
	}

	public function getDataRev()
	{
		return $this->data_rev;
	}

	public function setDataRev($value)
	{
		$this->data_rev = $this->valid("data_rev", $value);
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($value)
	{
		$this->email = $this->valid("email", $value);
	}

	public function getSituacao()
	{
		return $this->situacao;
	}

	public function setSituacao($value)
	{
		$this->situacao = $this->valid("situacao", $value);
	}

	public function getOrigemGravacao()
	{
		return $this->origem_gravacao;
	}
					
	public function setOrigemGravacao($value)
	{
		$this->origem_gravacao = $this->valid("origem_gravacao", $value);
	}

	public function getOperacao()
	{
		return $this->operacao;
	}

	public function setOperacao($value)
	{
		$this->operacao = $this->valid("operacao", $value);
	}

	public function getIdsisRev()
	{
		return $this->idsis_rev;
	}

	public function setIdsisRev($value)
	{
		$this->idsis_rev = $this->valid("idsis_rev", $value);
	}

	public function getIdsisCad()
	{
		return $this->idsis_cad;
	}

	public function setIdsisCad($value)
	{
		$this->idsis_cad = $this->valid("idsis_cad", $value);
	}

	public function getPessoaCad()
	{
		return $this->pessoa_cad;
	}

	public function setPessoaCad($value)
	{
		$this->pessoa_cad = $value;
	}

	public function getPessoaRev()
	{
		return $this->pessoa_rev;
	}

	public function setPessoaRev($value)
	{
		$this->pessoa_rev = $value;
	}

	public function getFisica()
	{
		return $this->fisica;
	}

	public function setFisica($value)
	{
		$this->fisica = $value;
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
				'allow_empty' => true,
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 0,
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
						false,
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

			// $inputFilter->add($factory->createInput(array(
			// 	'name' => 'idpes_cad',
			// 	'required' => true,
			// 	'filters' => array(
			// 		array('name' => 'Int'),
			// 	),
			// )));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

	public function removeInputFilter($input)
    {        
        $inputFilter    = new InputFilter();                        
        $this->inputFilter->remove($input);
        
        return $this->inputFilter;
    }
}