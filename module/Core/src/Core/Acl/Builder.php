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
		
	/**
	 * Constroi a ACL
	 * @return Acl
	 */
	public function build()
	{	
		$acl = new Acl();				 
		$acl->addRole(new Role('visitante'), null);
		$acl->addRole(new Role(7), 'visitante');		
		$acl->addResource(new Resource('Auth\Controller\Index.index'));	
		$acl->addResource(new Resource('Auth\Controller\Index.logout'));	
		$acl->addResource(new Resource('DoctrineORMModule\Yuml\YumlController.index'));

		$resources = $this->getEntityManager()->getRepository('Auth\Entity\Resource')->findAll();
		foreach ($resources as $resource) {			
			$acl->addResource(new Resource($resource->getNome()));			
		}

		$acl->allow('visitante', 'Auth\Controller\Index.index');
		$acl->allow('visitante', 'Auth\Controller\Index.logout');
		$acl->allow('visitante', 'DoctrineORMModule\Yuml\YumlController.index');
		
		$roles = $this->getEntityManager()->getRepository('Auth\Entity\Role')->findAll();
		foreach ($roles as $role) {
			/**
			 * Se a regra nao existe, insere ele
			 */			
			if (! $acl->hasRole($role->getFuncionario()->getId()))
				$acl->addRole(new Role($role->getFuncionario()->getId(), 'visitante'));				
			/**
			 * Verifica o privilegio
			 * 
			 * 0 - allow
			 * 1 - denny			
			 */
			($role->getPrivilegio == 0) ? $acl->allow($role->getFuncionario()->getId(), $role->getResource()->getNome()) : $acl->deny($role->getFuncionario()->getId(), $role->getResource()->getNome());
		}
		return $acl;
	}
}