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
use Doctrine\ORM\Mapping\UniqueConstraint;

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
 * @ORM\Table(name="cadastro_pessoa")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorMap({"P" = "Pessoa", "F" = "Fisica", "J" = "Juridica"})
 * @ORM\DiscriminatorColumn(name="tipo", type="string")
 */
class Pessoa extends Entity implements EventSubscriber 
{	

	// FILTER
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
	 * @ORM\GeneratedValue(strategy="AUTO")	 
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
	 * @ORM\Column(name="data_cad", type="datetime", nullable=false)
	 */
	protected $dataCad;

	/**
	 * @var  String $url Website
	 * @ORM\Column(type="string", length=60, nullable=true)
	 */
	protected $url;

	/**
	 * @var  String $tipo F(Fisica) ou J(Juridica)
	 * @deprecated deprecated since version 2.0 - Pela get_class da entidade tem como saber o tipo
	 *
	 *
	 * ORM\Column(name="tipo", type="string", length=1)
	 */
	//protected $tipo;

	/**
	 * @var  DateTime $data_rev Data de revisao do cadastro
	 * @todo  Verificar no antigo sistema a logica de quando esse dado é informado
	 * @deprecated deprecated since version 2.0
	 *
	 * ORM\Column(name="data_rev", type="datetime", nullable=true)
	 */
	//protected $dataRev;

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
	 * @deprecated deprecated since version 2.0
	 * 
	 * ORM\Column(name="origem_gravacao", type="string", length=1, nullable=false)
	 */
	//protected $origemGravacao;

	/**
	 * @var  String $operacao I(?) ou A(?) ou E(?) - Não consegui identificar os significados, porem todos os registros são salvos 
	 * como I inserir?
	 * como A alterar?
	 * como E excluir?
	 * @deprecated deprecated since version 2.0
	 *
	 * ORM\Column(type="string", length=1, nullable=false)
	 */
	//protected $operacao;

	/**
	 * @var  Integer $idsis_rev - Id do Sistema porem o rev não consegui encontrar sentido
	 * @deprecated deprecated since version 2.0
	 * 
	 * ORM\Column(name="idsis_rev", type="integer", nullable=true)
	 */
	//protected $idsisRev;

	/**
	 * @var  Integer $idsis_cad - Id do sistema que cadastrou o usuario ver tabela acesso.sistema
	 *
	 * @deprecated deprecated since version 2.0
	 * 
	 * Na versão 1.0 não tem uma chave estrangeira nessa coluna. Algo que pode ser colocado
	 * @todo  coluna tem relacionamento com a tabela acesso.sistema falta ajustar isso e ajustar no teste
	 * 
	 * ORM\Column(name="idsis_cad", type="integer", nullable=false)
	 */
	//protected $idsisCad;

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

	/**
	 * @var Integer $idpes_rev Id da pessoa 
	 * 
	 * ORM\OneToOne(targetEntity="pessoa", inversedBy="pessoa_rev")
	 * ORM\JoinColumn(name="idpes_rev", referencedColumnName="idpes", onDelete="SET NULL")	 
	 */
	//protected $idpes_rev;//protected $idpes_rev;

	/**
	 * //ORM\ManyToOne(targetEntity="pessoa", inversedBy="filhos")
	 * //ORM\JoinColumn(name="forum_opiniao", referencedColumnName="idpes")
	 * //ORM\Column(type="integer", nullable=false)
	 */
	//protected $forum_opiniao;

	/**
	 * @var EnderecoExterno $enderecoExterno Entity Endereço Externo
	 * 
	 * ORM\OneToOne(targetEntity="Usuario\Entity\EnderecoExterno", cascade={"persist"}, mappedBy="pessoa")
     * @ORM\ManyToOne(targetEntity="EnderecoExterno", cascade={"persist"})
	 * @ORM\JoinColumn(name="enderecoExterno")	 
	 */
	protected $enderecoExterno;
	/**
	 * @var int $pessoa Id da pessoa que tem esse endereco
	 * ORM\ManyToOne(targetEntity="Usuario\Entity\EnderecoExterno")	 	 
	 */
	// protected $enderecoExterno;

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
	    	if ( get_class($entity) == 'Usuario\Entity\Pessoa' ){
	    		$this->usuario = $entity;    	
	    		$this->oldId = $entity->id;	
	    	}	    	
        }         
	}

	public function preUpdate(PreUpdateEventArgs  $args)
	{
		/**
		 * Pegando dados do registro antes de atualizar e salvando na variavel $this->usuario
		 * para Salvar na tabela historico no metodo postFlush()
		 */		
		$entity = $args->getEntity();
		if ((get_class($entity) == 'Usuario\Entity\Pessoa') || (get_class($entity) == 'Usuario\Entity\Fisica') 
			|| (get_class($entity) == 'Usuario\Entity\Juridica')){
			
			$this->usuario = clone $entity;
			$this->oldId = $entity->id;

			($args->hasChangedField('nome')) ? $this->usuario->nome = $args->getOldValue('nome') : null;

			($args->hasChangedField('url')) ? $this->usuario->url = $args->getOldValue('url') : null;
			($args->hasChangedField('tipo')) ? $this->usuario->tipo = $args->getOldValue('tipo') : null;
			($args->hasChangedField('email')) ? $this->usuario->email = $args->getOldValue('email') : null;
			($args->hasChangedField('situacao')) ? $this->usuario->situacao = $args->getOldValue('situacao') : null;
			($args->hasChangedField('origemGravacao')) ? $this->usuario->origem_gravacao = $args->getOldValue('origemGravacao') : null;
			($args->hasChangedField('operacao')) ? $this->usuario->operacao = $args->getOldValue('operacao') : null;
			($args->hasChangedField('idsisCad')) ? $this->usuario->idsisCad = $args->getOldValue('idsisCad') : null;
			($args->hasChangedField('idsisRev')) ? $this->usuario->idsisRev = $args->getOldValue('idsisRev') : null;
			($args->hasChangedField('pessoaCad')) ? $this->usuario->pessoaCad = $args->getOldValue('pessoaCad') : null;
			($args->hasChangedField('pessoaRev')) ? $this->usuario->pessoaRev = $args->getOldValue('pessoaRev') : null;			

		}
		/**
		 * Se for uma entidade do tipo fisica
		 * verifica os campos alterados do update
		 */
		if (get_class($entity) == 'Usuario\Entity\Fisica'){
			($args->hasChangedField('data_nasc')) ? $this->usuario->data_nasc = $args->getOldValue('data_nasc') : null;
			($args->hasChangedField('sexo')) ? $this->usuario->sexo = $args->getOldValue('sexo') : null;
			($args->hasChangedField('dataUniao')) ? $this->usuario->dataUniao = $args->getOldValue('dataUniao') : null;
			($args->hasChangedField('dataObito')) ? $this->usuario->dataObito = $args->getOldValue('dataObito') : null;
			($args->hasChangedField('nacionalidade')) ? $this->usuario->nacionalidade = $args->getOldValue('nacionalidade') : null;
			($args->hasChangedField('dataChegadaBrasil')) ? $this->usuario->dataChegadaBrasil = $args->getOldValue('dataChegadaBrasil') : null;
			($args->hasChangedField('ultimaEmpresa')) ? $this->usuario->ultimaEmpresa = $args->getOldValue('ultimaEmpresa') : null;
			($args->hasChangedField('nomeMae')) ? $this->usuario->nomeMae = $args->getOldValue('nomeMae') : null;
			($args->hasChangedField('nomePai')) ? $this->usuario->nomePai = $args->getOldValue('nomePai') : null;
			($args->hasChangedField('nomeConjuge')) ? $this->usuario->nomeConjuge = $args->getOldValue('nomeConjuge') : null;
			($args->hasChangedField('nomeResponsavel')) ? $this->usuario->nomeResponsavel = $args->getOldValue('nomeResponsavel') : null;
			($args->hasChangedField('justificativaProvisorio')) ? $this->usuario->justificativaProvisorio = $args->getOldValue('justificativaProvisorio') : null;
			($args->hasChangedField('refCodSistema')) ? $this->usuario->refCodSistema = $args->getOldValue('refCodSistema')  : null;
			($args->hasChangedField('cpf')) ? $this->usuario->cpf = $args->getOldValue('cpf') : null;
			($args->hasChangedField('pessoaMae')) ? $this->usuario->pessoaMae = $args->getOldValue('pessoaMae') : null;
			($args->hasChangedField('pessoaPai')) ? $this->usuario->pessoaPai = $args->getOldValue('pessoaPai') : null;
			($args->hasChangedField('pessoaResponsavel')) ? $this->usuario->pessoaResponsavel = $args->getOldValue('pessoaResponsavel') : null;
			($args->hasChangedField('municipioNascimento')) ? $this->usuario->municipioNascimento = $args->getOldValue('municipioNascimento') : null;
			($args->hasChangedField('paisEstrangeiro')) ? $this->usuario->paisEstrangeiro = $args->getOldValue('paisEstrangeiro') : null;
			($args->hasChangedField('escola')) ? $this->usuario->escola = $args->getOldValue('escola') : null;
			($args->hasChangedField('estadoCivil')) ? $this->usuario->estadoCivil = $args->getOldValue('estadoCivil') : null;
			($args->hasChangedField('pessoaConjuge')) ? $this->usuario->pessoaConjuge = $args->getOldValue('pessoaConjuge') : null;
			($args->hasChangedField('ocupacao')) ? $this->usuario->ocupacao = $args->getOldValue('ocupacao') : null;
			($args->hasChangedField('refCodReligiao')) ? $this->usuario->refCodReligiao = $args->getOldValue('refCodReligiao') : null;			
		}

		/**
		 * Se for uma entidade do tipo juridica
		 * verifica os campos alterados do update
		 */
		if (get_class($entity) == 'Usuario\Entity\Juridica'){
			($args->hasChangedField('cnpj')) ? $this->usuario->cnpj : null;
			($args->hasChangedField('inscEstadual')) ? $this->usuario->inscEstadual : null;
			($args->hasChangedField('fantasia')) ? $this->usuario->fantasia : null;
			($args->hasChangedField('capitalSocial')) ? $this->usuario->capitalSocial : null;
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
			/**
			 * @todo verfiicar isso depois das alteracoes de sequence
			 */
			$historicoPessoa = new \Historico\Entity\Pessoa();

			$metadata = $em->getClassMetaData(get_class($historicoPessoa));			
			//$metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
			// var_dump($metadata);
			$sequenceName = $metadata->sequenceGeneratorDefinition['sequenceName'];
			//$sequenceName = 'historico.seq_pessoa';
			$sequenceGenerator = new SeqGen($sequenceName, 1);
			$newId = $sequenceGenerator->generate($em, $historicoPessoa);			
			$historicoPessoa->setId($newId);			
			$historicoPessoa->setIdpes($this->oldId);
			$historicoPessoa->setNome($this->usuario->nome);//->format('Y-m-d H:i:s')
			//$historicoPessoa->data_cad = new \DateTime($this->usuario->getDataCad());
			$historicoPessoa->setDataCad($this->usuario->dataCad);
			$historicoPessoa->setUrl($this->usuario->url);			
			//$historicoPessoa->setTipo($em->getClassMetadata(get_class($this->usuario))->discriminatorValue);

			//$historicoPessoa->setTipo($this->usuario->tipo);
			//if (is_null($this->usuario->dataRev)){
			//	$historicoPessoa->setDataRev(new \DateTime());
			//}
			// // else {
			// // 	$historicoPessoa->data_rev = $this->usuario->data_rev;	
			// // }			
			$historicoPessoa->setEmail($this->usuario->email);
			$historicoPessoa->setSituacao($this->usuario->situacao);

	    	$logMetadata = $em->getClassMetadata('Historico\Entity\Pessoa');
	    	$className = $logMetadata->name;
	    	$persister = $uow->getEntityPersister($className);	    	
	    	$persister->addInsert($historicoPessoa);
	    	$uow->computeChangeSet($logMetadata, $historicoPessoa);
			$postInsertIds = $persister->executeInserts();	

			if (get_class($this->usuario) == 'Usuario\Entity\Fisica'){					
				$historicoFisica = new \Historico\Entity\Fisica();
				$metadata = $em->getClassMetaData(get_class($historicoFisica));						
				$sequenceName = $metadata->sequenceGeneratorDefinition['sequenceName'];
				// $sequenceName = 'historico.seq_fisica';
				$sequenceGenerator = new SeqGen($sequenceName, 1);
				$newId = $sequenceGenerator->generate($em, $historicoFisica);

				$historicoFisica->setId($newId);
				$historicoFisica->setIdpes($this->oldId);
				$historicoFisica->setDataNasc($this->usuario->dataNasc);
				$historicoFisica->setSexo($this->usuario->sexo);
				$historicoFisica->setDataUniao($this->usuario->dataUniao);
				$historicoFisica->setDataObito($this->usuario->dataObito);
				$historicoFisica->setNacionalidade($this->usuario->nacionalidade);
				$historicoFisica->setDataChegadaBrasil($this->usuario->dataChegadaBrasil);
				$historicoFisica->setUltimaEmpresa($this->usuario->ultimaEmpresa);
				$historicoFisica->setJustificativaProvisorio($this->usuario->justificativaProvisorio);
				$historicoFisica->setCpf($this->usuario->cpf);				
				$historicoFisica->setPessoaMae($this->usuario->pessoaMae);
				$historicoFisica->setPessoaPai($this->usuario->pessoaPai);
				$historicoFisica->setPessoaResponsavel($this->usuario->pessoaResponsavel);
				$historicoFisica->setMunicipioNascimento($this->usuario->municipioNascimento);
				$historicoFisica->setPaisEstrangeiro($this->usuario->paisEstrangeiro);
				$historicoFisica->setEscola($this->usuario->escola);
				$historicoFisica->setEstadoCivil($this->usuario->estadoCivil);
				$historicoFisica->setPessoaConjuge($this->usuario->pessoaConjuge);
				$historicoFisica->setOcupacao($this->usuario->ocupacao);
				$historicoFisica->setRefCodReligiao($this->usuario->refCodReligiao);
				$logMetadata = $em->getClassMetadata('Historico\Entity\Fisica');
				$className = $logMetadata->name;
				$persister = $uow->getEntityPersister($className);
				$persister->addInsert($historicoFisica);
				$uow->computeChangeSet($logMetadata, $historicoFisica);
				$postInsertIds = $persister->executeInserts();				
			}

			if (get_class($this->usuario) == 'Usuario\Entity\Juridica'){
				$historicoJuridica = new \Historico\Entity\Juridica();
				$metadata = $em->getClassMetaData(get_class($historicoJuridica));						
				$sequenceName = $metadata->sequenceGeneratorDefinition['sequenceName'];
				// $sequenceName = 'historico.seq_juridica';
				$sequenceGenerator = new SeqGen($sequenceName, 1);
				$newId = $sequenceGenerator->generate($em, $historicoJuridica);

				$historicoJuridica->setId($newId);
				$historicoJuridica->setIdpes($this->oldId);
				$historicoJuridica->setCnpj($this->usuario->cnpj);
				$historicoJuridica->setInscEstadual($this->usuario->inscEstadual);
				$historicoJuridica->setFantasia($this->usuario->fantasia);
				$historicoJuridica->setCapitalSocial($this->usuario->capitalSocial);
				$logMetadata = $em->getClassMetadata('Historico\Entity\Juridica');
				$className = $logMetadata->name;
				$persister = $uow->getEntityPersister($className);
				$persister->addInsert($historicoJuridica);
				$uow->computeChangeSet($logMetadata, $historicoJuridica);
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
		$this->setId(isset($data['id']) ? $data['id'] : null);
		$this->setNome(isset($data['nome']) ? $data['nome'] : null);
		$this->setDataCad(isset($data['dataCad']) ? $data['dataCad'] : null);
		if (!empty($data['url']))
			$this->setUrl(isset($data['url']) ? $data['url'] : null);
		$this->setTipo(isset($data['tipo']) ? $data['tipo'] : null);
		$this->setDataRev(isset($data['dataRev']) ? $data['dataRev'] : null);		
		if (!empty($data['email']))
			$this->setEmail(isset($data['email']) ? $data['email'] : null);
		$this->setSituacao(isset($data['situacao']) ? $data['situacao'] : null);
		//$this->setOrigemGravacao(isset($data['origem_gravacao']) ? $data['origem_gravacao'] : null);
		//$this->setOperacao(isset($data['operacao']) ? $data['operacao'] : null);
		$this->setIdsisRev(isset($data['idsisRev']) ? $data['idsisRev'] : null);
		//$this->setIdsisCad(isset($data['idsis_cad']) ? $data['idsis_cad'] : null);
		$this->setPessoaCad(isset($data['pessoaCad']) ? $data['pessoaCad'] : null);
		$this->setPessoaRev(isset($data['pessoaRev']) ? $data['pessoaRev'] : null);	
	}	

	public function setData($data)
	{				
		$this->setId(isset($data['id']) ? $data['id'] : null);
		$this->setNome(isset($data['nome']) ? $data['nome'] : null);
		$this->setDataCad(isset($data['dataCad']) ? $data['dataCad'] : null);
		if (!empty($data['url']))
			$this->setUrl(isset($data['url']) ? $data['url'] : null);
		$this->setTipo(isset($data['tipo']) ? $data['tipo'] : null);
		$this->setDataRev(isset($data['dataRev']) ? $data['dataRev'] : null);		
		if (!empty($data['email']))
			$this->setEmail(isset($data['email']) ? $data['email'] : null);
		$this->setSituacao(isset($data['situacao']) ? $data['situacao'] : null);
		//$this->setOrigemGravacao(isset($data['origem_gravacao']) ? $data['origem_gravacao'] : null);
		//$this->setOperacao(isset($data['operacao']) ? $data['operacao'] : null);
		$this->setIdsisRev(isset($data['idsisRev']) ? $data['idsisRev'] : null);
		//$this->setIdsisCad(isset($data['idsis_cad']) ? $data['idsis_cad'] : null);
		$this->setPessoaCad(isset($data['pessoaCad']) ? $data['pessoaCad'] : null);
		$this->setPessoaRev(isset($data['pessoaRev']) ? $data['pessoaRev'] : null);	
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

	public function getNome()
	{
		return $this->nome;
	}

	public function setNome($nome)
	{		
		$this->nome = $this->valid("nome", $nome);
	}

	public function getDataCad()
	{
		return $this->dataCad;
	}

	public function setDataCad($value)
	{
		$this->dataCad = $this->valid("dataCad", $value);
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function setUrl($url)
	{
		$this->url = $this->valid("url", $url);		
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

	public function getEnderecoExterno()
	{
		return $this->enderecoExterno;
	}

	public function setEnderecoExterno(EnderecoExterno $enderecoExterno = null)
	{
		$this->enderecoExterno = $this->valid("enderecoExterno", $enderecoExterno);
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
				'name' => 'enderecoExterno',
				'required' => true,
				'continue_if_empty' => true,
			)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}
}