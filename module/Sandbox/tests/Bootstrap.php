<?php
namespace SandboxTest;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoLoader;

/**
 * Bootstrap for phpunit
 */
class Bootstrap
{
	static function getModulePath()
	{
		return __DIR__ . '/../../../module/Usuario';
	}

	static public function go()
	{

		chdir(dirname(__DIR__ . '/../../../..'));

		include 'init_autoloader.php';

		define('ZF2_PATH', realpath('vendor/zendframework/zendframework/library'));

		$path = array(
			ZF2_PATH,
			get_include_path(),
		);
		set_include_path(implode(PATH_SEPARATOR, $path));

		// require_once 'Zend/Loader/AutoloaderFactory.php';
		// require_once 'Zend/Loader/StandardAutoLoader.php';

		//setup autoloader
		AutoloaderFactory::factory(
			array(
				'Zend\Loader\StandardAutoLoader' => array(
					StandardAutoLoader::AUTOREGISTER_ZF => true,
					StandardAutoLoader::ACT_AS_FALLBACK => false,
					StandardAutoLoader::LOAD_NS => array(
						'Core' => getcwd() . '/module/Core/src/Core'
					)
				)
			)
		);
	}
}

Bootstrap::go();