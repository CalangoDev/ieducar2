<?php
namespace Core\Test;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Application;
use Zend\Di\Di;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\Tools\SchemaTool;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Zend\ServiceManager\ServiceManager
	 */
	protected $serviceManager;

	/**
	 * @var Zend\Mvc\Application
	 */
	protected $application;

	/**
	 * @var Zend\Di\Di
	 */
	protected $di;

	/**
	 * EntityManager
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	public function setup()
	{
		parent::setup();

		$config = include 'config/application.config.php';
		$config['module_listener_options']['config_static_paths'] = array(getcwd() . '/config/test.config.php');

		if (file_exists(__DIR__ . '/config/test.config.php')) {
			$moduleConfig = include __DIR__ . '/config/test.config.php';
			array_unshift($config['module_listener_options']['config_static_paths'], $moduleConfig);			
		}

		$this->serviceManager = new ServiceManager(new ServiceManagerConfig(
			isset($config['service_manager']) ? $config['service_manager'] : array()
		));
		$this->serviceManager->setService('ApplicationConfig', $config);
		$this->serviceManager->setFactory('ServiceListener', 'Zend\Mvc\Service\ServiceListenerFactory');

		$moduleManager = $this->serviceManager->get('ModuleManager');
		$moduleManager->loadModules();
		$this->routes = array();
		foreach ($moduleManager->getModules() as $m) {						
			if (file_exists( __DIR__ . '/../../../../' .ucfirst($m) . '/config/module.config.php')) {
				$moduleConfig = include __DIR__ . '/../../../../' . ucfirst($m) . '/config/module.config.php';
				if (isset($moduleConfig['router'])){
					foreach ($moduleConfig['router']['routes'] as $key => $name) {
						$this->routes[$key] = $name;
					}
				}
			}	
		}
		$this->serviceManager->setAllowOverride(true);

		$this->application = $this->serviceManager->get('Application');
		$this->event = new MvcEvent();
		$this->event->setTarget($this->application)
					->setRequest($this->application->getRequest())
					->setResponse($this->application->getResponse())
					->setRouter($this->serviceManager->get('Router'));							
		$this->em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$this->createDatabase();
	}

	public function tearDown()
	{
		/**
		 * @todo  apagar essa linha depois que verificar nos testes
		 */		
		parent::tearDown();		
		$this->dropDatabase();
	}

	/**
	 * Retrieve Service
	 * @param String $service
	 * @return  Service
	 */
	protected function getService($service)
	{
		return $this->serviceManager->get($service);
	}

	/**
	 * @return void
	 */
	public function createDatabase()
	{		
		$tool = new SchemaTool($this->em);
		$classes = $this->em->getMetadataFactory()->getAllMetadata();				
		$tool->dropSchema($classes);
		$tool->createSchema($classes);
	}
	/**
	 * @return void
	 */
	public function dropDatabase()
	{
		$tool = new SchemaTool($this->em);
		$classes = $this->em->getMetadataFactory()->getAllMetadata();
		//$tool->dropSchema($classes);
	}
}
