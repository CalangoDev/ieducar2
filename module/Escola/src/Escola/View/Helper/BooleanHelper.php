<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 01/04/16
 * Time: 11:18
 */
namespace Escola\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Helper que exibe sim ou não para valores Booleanos 0 ou 1
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Helper
 * @package View\Helper
 * @version 0.1
 * @example Class Boolean
 * @copyright Copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 */
class BooleanHelper extends AbstractHelper
{
    public function __invoke($value)
    {
        switch ($value){
            case 0:
                $retorno = 'Não';
                break;
            default:
                $retorno = 'Sim';
        }

        return $retorno;
    }
}