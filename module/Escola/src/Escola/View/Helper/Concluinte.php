<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 15/01/16
 * Time: 10:52
 */
namespace Escola\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Helper que exibe se concluinte ou nao
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Helper
 * @package View\Helper
 * @version 0.1
 * @example Class Concluinte
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 */
class Concluinte extends AbstractHelper
{
    public function __invoke($value)
    {
        switch ($value){
            case 0:
                $concluinte = 'Não';
                break;
            default:
                $concluinte = 'Sim';
        }

        return $concluinte;
    }
}
