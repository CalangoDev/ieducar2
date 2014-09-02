<?php
namespace Core\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;

/**
 * Controlador responsavel pelo o tratamento de cep do sistema
 *
 * @category Core
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class CepController extends ActionController
{
	public function indexAction()
	{
		var_dump("estamos aqui voila");

		return new ViewModel(array(
			
		));
	}
}