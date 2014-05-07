<?php 
namespace Core\Acl;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

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
	 * Constroi a ACL
	 * @return Acl
	 */
	public function build()
	{
		/**
		 * @todo  ajustar essa classe para o doctrine
		 */
		$config = $this->getServiceManager()->get('Config');
		$acl = new Acl();
		foreach ($config['acl']['roles'] as $role => $parent) {
			$acl->addRole(new Role($role), $parent);
		}
		foreach ($config['acl']['resources'] as $r) {
			$acl->addResource(new Resource($r));
		}
		foreach ($config['acl']['privilege'] as $role => $privilege) {
			if (isset($privilege['allow'])) {
				foreach ($privilege['allow'] as $p) {
					$acl->allow($role, $p);
				}
			}
			if (isset($privilege['deny'])) {
				foreach ($privilege['deny'] as $p) {
					$acl->deny($role, $p);
				}
			}
		}
		return $acl;
	}
}