<?php
namespace Auth\Service;

use DateTime;
use Core\Test\ServiceTestCase;
use Portal\Entity\Funcionario;
use Core\Model\EntityException;
use Zend\Authentication\AuthenticationService;

/**
 * Testes do serviço de Auth
 * 
 * @category Auth
 * @package Service
 * @author  Eduardo Junior<ej@eduardojunior.com>
 */

/**
 * @group Service
 */
class AuthTest extends ServiceTestCase
{
	// *
 //     * Authenticação sem parâmetros
 //     * @expectedException \Exception
 //     * @return void
     
 //    // public function testAuthenticateWithoutParams()
    // {
    //     $authService = $this->serviceManager->get('Auth\Service\Auth');

    //     $authService->authenticate();
    //  }

    /**
     * Authenticação sem parâmetros
     * @expectedException \Exception
     * @expectedExceptionMessage Parâmetros inválidos
     * @return void
     */
    // public function testAuthenticateEmptyParams()
    // {
    //     $authService = $this->serviceManager->get('Auth\Service\Auth');

    //     $authService->authenticate(array());
    //  }

    /**
     * Teste da autenticação inválida
     * @expectedException \Exception
     * @expectedExceptionMessage Matrícula ou senha inválidos
     * @return void
     */    
    // public function testAuthenticateInvalidParameters()
    // {
    //     $authService = $this->serviceManager->get('Auth\Service\Auth');

    //     $authService->authenticate(array('matricula' => 'invalid', 'senha' => 'invalid'));
    // }

    /**
     * Teste da autenticação Inválida
     * @expectedException \Exception
     * @expectedExceptionMessage Matrícula ou senha inválidos
     * @return void
     */
  //   public function testAuthenticateInvalidPassword()
  //   {

  // 		//   	$fisica = $this->buildFisica();
		// // $this->em->persist($fisica);

		// // $funcionario = $this->buildFuncionario();
		// // $funcionario->setId($fisica);

		// // $this->em->persist($funcionario);
		// // $this->em->flush();

  //       $authService = $this->serviceManager->get('Auth\Service\Auth');
        
  //       $funcionario = $this->buildFuncionario();

  //       $authService->authenticate(array('matricula' => $funcionario->getMatricula(), 'senha' => 'invalid'));
  //   }

    /**
     * Teste da autenticação Válida
     * @return void
     */    
    // public function testAuthenticateValidParams()
    // {   
    //     $authService = $this->serviceManager->get('Auth\Service\Auth');
    //     $fisica = $this->buildFisica();
    //     $em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
    //     $em->persist($fisica);

    //     $funcionario = $this->buildFuncionario();
    //     $funcionario->setId($fisica);
    //     $em->persist($funcionario);
    //     $em->flush();
        
    //     $result = $authService->authenticate(array('matricula' => $funcionario->getMatricula(), 'senha' => 'admin'));
    //     $this->assertTrue($result);        

    //     //testar a se a authenticação foi criada        
    //     // $auth = $this->serviceManager->get('Zend\Authentication\AuthenticationService');
       

    //     // print_r($auth->getStorage()->read());
    //     // print_r($auth->getStorage()->readKeyOnly());

    //     // $auth = $this->serviceManager->get('Auth\Service\Auth');
    //     // var_dump($auth->getIdentity()->getMatricula());
    //     // $identity = $auth->getIdentity();
    //    	// print_r($auth->getStorage());
    //     // $this->assertInstanceOf($auth->getIdentity(), $funcionario->getMatricula());
    //     // $this->assertEquals($auth->getIdentity()->getMatricula(), $funcionario->getMatricula());

    //     //verifica se o usuário foi salvo na sessão
    //     // $session = $this->serviceManager->get('Session');        
    //     // $savedFuncionario = $session->offsetGet('ieducar2');
        
    //     // $this->assertEquals($funcionario->getId(), $savedFuncionario->getId());
    // }

    /**
     * Limpa a autenticação depois de cada teste
     * @return void
     */
    // public function tearDown()
    // {        
    //     parent::tearDown();
    //     $auth = new AuthenticationService();
    //     $auth->clearIdentity();
    // }

    /**
     * Teste do logout
     * @return void
     */
    // public function testLogout()
    // {
    //     $authService = $this->serviceManager->get('Auth\Service\Auth');
    //     $funcionario = $this->buildFuncionario();
        
    //     $result = $authService->authenticate(
    //         array('matricula' => $funcionario->getMatricula(), 'senha' => 'apple')
    //     );

    //     $this->assertTrue($result);

    //     $result = $authService->logout();
    //     $this->assertTrue($result);
        
    //     //verifica se removeu a identidade da autenticação
    //     $auth = new AuthenticationService();
    //     $this->assertNull($auth->getIdentity());

    //     //verifica se o usuário foi removido da sessão
    //     $session = $this->serviceManager->get('Session');
    //     $savedFuncionario = $session->offsetGet('ieducar2');
    //     $this->assertNull($savedFuncionario);
    // }

    /**
     * Teste da autorização
     * @return void
     */
    // public function testAuthorize()
    // {
    //     $authService = $this->getService('Auth\Service\Auth');
    //     $result = $authService->authorize();
    //     $this->assertFalse($result);
        
    //     $funcionario = $this->buildFuncionario();

    //     $result = $authService->authenticate(
    //         array('matricula' => $funcionario->getMatricula(), 'senha' => 'apple')
    //     );

    //     $this->assertTrue($result);

    //     $result = $authService->authorize();
    //     $this->assertTrue($result);
    // }

 //    private function buildFisica()
	// {	
 //    	/**
 //    	 * Dados fisica
 //    	 */    	
	// 	$fisica = new \Usuario\Entity\Fisica;
	// 	$fisica->setSexo("M");
	// 	$fisica->setOrigemGravacao("M");
	// 	$fisica->setOperacao("I");
	// 	$fisica->setIdsisCad(1);
	// 	$fisica->setNome('Steve Jobs');
	// 	$fisica->setTipo("F");
 //    	$fisica->setSituacao("A");
 //    	$fisica->setOrigemGravacao("M");
 //    	$fisica->setOperacao("I");
 //    	$fisica->setIdsisCad(1);    	

 //    	return $fisica;
	// }


	// private function buildFuncionario()
	// {
	// 	$funcionario = new Funcionario;
	// 	$funcionario->setMatricula('admin');
	// 	$funcionario->setSenha('admin');
	// 	$funcionario->setAtivo(1);		

	// 	return $funcionario;
	// }
  
}