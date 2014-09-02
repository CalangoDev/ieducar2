<?php
namespace Core\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;

/**
 *
 * @category Core
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class IndexController extends ActionController
{
	public function indexAction()
	{
		var_dump("INDEX");
		return new ViewModel();
	}
}