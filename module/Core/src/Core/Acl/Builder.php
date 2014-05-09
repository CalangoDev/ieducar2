<?php 
namespace Core\Acl;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

use Doctrine\ORM\EntityManager;

class Builder implements ServiceManagerAwareInterface
{
	/**
	 * @var ServiceManager
	 */
	protected $ServiceManager;

	/**
	 * @param ServiceManager $serviceManager
	 */
	public function setServiceManager(ServiceManager $serviceManager)
	{
		$this->serviceManager = $serviceManager;
	}

	/**
	 * Retrieve serviceManager instance
	 *
	 * @return ServiceLocatorInterface
	 */
	public function getServiceManager()
	{
		return $this->serviceManager;
	}

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}

	public function getEntityManager()
	{
		if (null === $this->em){
			$this->em = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
		}		
		return $this->em;
	}
	
	//	$dados = $this->getEntityManager()->getRepository('Drh\Entity\Setor')->findAll();	

	/**
	 * Constroi a ACL
	 * @return Acl
	 */
	public function build()
	{	
		$acl = new Acl();	
		/**
		 * Construir a Acl estática para usuarios guest/visistante, ao qual deve ter permissao de acessar o recurso da tela de autenticação
		 * Resource: Auth\Controller\Index.index
		 * Role: visitante
		 * Privilege: allow
		 */		
		$acl->addRole(new Role('visitante'), null);
		$acl->addResource(new Resource('Auth\Controller\Index.index'));	
		$acl->addResource(new Resource('DoctrineORMModule\Yuml\YumlController.index'));
		$acl->allow('visitante', 'Auth\Controller\Index.index');
		$acl->allow('visitante', 'DoctrineORMModule\Yuml\YumlController.index');
		/**
		 * Buscar as Acls Dynamicas no banco de dados
		 * 
		 * Entidades 
		 * Resource: Auth\Entity\Resource
		 * Role and Privilege: Auth\Entity\Role
		 */
		$resources = $this->getEntityManager()->getRepository('Auth\Entity\Resource')->findAll();
		foreach ($resources as $resource) {			
			$acl->addResource(new Resource($resource->getNome()));
			
		}
		/**
         * $query = $this->_em->createQuery("SELECT u FROM
         * DoctrineTest\Entity\Comment u WHERE u.userId = :userId");
         * $query->setParameters(array('userId' => $userId));
         * return $query->getResult();
        */
		$roles = $this->getEntityManager()->createQuery('SELECT r.id, r.privilegio FROM Auth\Entity\Role r LEFT JOIN r.funcionario f ')->getResult();				
		// $roles = $this->getEntityManager()->createQueryBuilder();
		// $roles->add('select', 'r.id, r.privilegio')
		// 	  ->add('from', 'Auth\Entity\Role r');
		// 	  // ->add('groupBy', 'r.funcionario');
		// var_dump($roles->__toString());
		// $query = $roles->getQuery();
		// $roles = $query->getResult();
		// 	  var_dump($roles);
		foreach ($roles as $role) {			
			// $acl->addRole(new Role($role->getFuncionario()->getId(), 'visitante'));
		}
		/**
		 * @todo adicionar os privilegios
		 */
		// $config = $this->getServiceManager()->get('Config');		
		// foreach ($config['acl']['roles'] as $role => $parent) {
		// 	$acl->addRole(new Role($role), $parent);
		// }
		// foreach ($config['acl']['resources'] as $r) {
		// 	$acl->addResource(new Resource($r));
		// }
		// foreach ($config['acl']['privilege'] as $role => $privilege) {
		// 	if (isset($privilege['allow'])) {
		// 		foreach ($privilege['allow'] as $p) {
		// 			$acl->allow($role, $p);
		// 		}
		// 	}
		// 	if (isset($privilege['deny'])) {
		// 		foreach ($privilege['deny'] as $p) {
		// 			$acl->deny($role, $p);
		// 		}
		// 	}
		// }
		return $acl;
	}
}