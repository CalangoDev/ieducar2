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
	/*
	public function authenticate($params)
	{
		// if (!isset($params['matricula']) || !isset($params['senha'])) {
		// 	throw new \Exception("Parâmetros inválidos");			
		// }

		// $senha = md5($params['senha']);		
		// $authService = $this->serviceManager->get('Zend\Authentication\AuthenticationService');


		// $adapter = $authService->getAdapter();				
		// $adapter->setIdentityValue($params['matricula']);
		// $adapter->setCredentialValue($senha);
  //   	$authResult = $authService->authenticate();

		// if (!$authResult->isValid()){			
		// 	throw new \Exception("Matrícula ou senha inválidos");
		// }

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

		// return true;
	}*/

	/**
	 * Faz o logout do sistema
	 * 
	 * @return  void 
	 */
	// public function logout()
	// {
	// 	$auth = new AuthenticationService();
	// 	$session = $this->getServiceManager()->get('Session');
	// 	$session->offsetUnSet('ieducar2');
	// 	$auth->clearIdentify();

	// 	return true;
	// }

	/**
     * Faz a autorização do usuário para acessar o recurso
     * @param string $moduleName Nome do módulo sendo acessado
     * @param string $controllerName Nome do controller
     * @param string $actionName Nome da ação
     * @return boolean
     */
    public function authorize($moduleName, $controllerName, $actionName)
    {    	    	
    	/**
    	 * problema serio com o authentication service
    	 * 
    	 * como a entidade funcionario tem um relacionamento one to one, ela nao tem a coluna id 
    	 * e por isso ao usar getservicemanager da um erro quando tenta pegar a identity,
    	 * tentei usar uma composisao de chave primaria, porem sem sucesso ate entao tentar mais isso depois
    	 * pois do jeito que foi feito agora nao tem como funcionar direito
    	 */
        // $auth = new AuthenticationService(); //simples retorna so o id
        $auth = $this->getServiceManager()->get('Zend\Authentication\AuthenticationService');
        $role = 'visitante';
        $superadmin = false;
        
        if ($auth->hasIdentity()) {        	        	
        	$user = $auth->getIdentity();
        	if ($user){        		
            	$role = $user->getFisica()->getId();//pega o codigo da pessoa fisica
            	/*
	             * Verificando se é superusuario se for liberar acesso ao sistema todo
	             */            
	            if ($user->getSuperAdmin() == 1)
	            	$superadmin = true;
        	}            
            // foreach ($auth->getIdentity()['refCodPessoaFj'] as $key => $value) {
            // 	if ($key == 'id'){
            // 		$id = $value;
            // 	}
            // }            
            // $role = $id;
            // var_dump($auth->getIdentity()['refCodPessoaFj']);
        } 
        if ($superadmin){
        	// var_dump("SOU SUPER ADMIN");
        	return true;
        }
        $resource = $controllerName . '.' . $actionName;                 
        /* monta as acls de acordo com o arquivo de configurações */
        // var_dump($resource);
        $acl = $this->getServiceManager()
                    ->get('Core\Acl\Builder')
                    ->build();         
        if ($acl->hasRole($role)){        	
        	if ($acl->isAllowed($role, $resource)) {        	
        		return true;
    		}        	        
        } else {
        	if (($role != 'visitante') && ( 
        		($resource == 'Application\Controller\Index.index')  || ($resource == 'Auth\Controller\Index.logout') ) )
        		return true;
        }
        return false;
    }
}