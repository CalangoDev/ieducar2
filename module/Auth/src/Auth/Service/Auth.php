<?php
namespace Auth\Service;

use Core\Service\Service;
use Zend\Authentication\AuthenticationService;
// use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Db\Sql\Select;

/**
 * Serviço responsável pela autenticação da aplicação
 * 
 * @category Auth
 * @package Service
 * @author Eduardo Junior<ej@eduardojunior.com>
 */
class Auth extends Service
{
	/**
	 * Adapter usado para a autenticação
	 * @var Zend\Db\Adapter\Adapter 
	 */
	private $dbAdapter;

	/**
	 * Construtor da Classe
	 * 
	 * @return void 
	 */
	public function __construct($dbAdapter = null)
	{
		$this->dbAdapter = $dbAdapter;
	}

	/**
	 * fas as autenticação dos usuarios
	 * 
	 * @param array $params 
	 * @return  array
	 */
	public function authenticate($params)
	{
		if (!isset($params['matricula']) || !isset($params['senha'])) {
			throw new \Exception("Parâmetros inválidos");			
		}

		$senha = md5($params['senha']);		
		$authService = $this->serviceManager->get('Zend\Authentication\AuthenticationService');


		$adapter = $authService->getAdapter();				
		$adapter->setIdentityValue($params['matricula']);
		$adapter->setCredentialValue($senha);
    	$authResult = $authService->authenticate();

		if (!$authResult->isValid()){			
			throw new \Exception("Matrícula ou senha inválidos");
		}

		// $identity = $authResult->getIdentity();
		// $authService->getStorage()->write( $identity  );

		// print_r($authService->getStorage()->readKeyOnly());
		
		// $teste = $this->serviceManager->get('Zend\Authentication\AuthenticationService');
		// $teste->getIdentity();
		// print_r($teste->getIdentity());

		//	salva o usuario na sessao
		//$session = $this->getServiceManager()->get('Session');
		
		//$identity = $authenticationResult->getIdentity();
		// var_dump($authResult->getIdentity());
		// var_dump($authResult->getResultRowObject());
		// $this->getStorage()->write($result->getIdentity());
		
		// var_dump(serialize($authResult->getIdentity()));
		// $authService->getStorage()->write( serialize( $authResult->getIdentity() ) );
		// var_dump($teste);
		// $session->offsetSet('ieducar2', $authResult->getIdentity());

		return true;
	}

	/**
	 * Faz o logout do sistema
	 * 
	 * @return  void 
	 */
	public function logout()
	{
		$auth = new AuthenticationService();
		$session = $this->getServiceManager()->get('Session');
		$session->offsetUnSet('ieducar2');
		$auth->clearIdentify();

		return true;
	}
}