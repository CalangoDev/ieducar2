<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 10:20
 */
namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Helper que exibe o tipo do sistema de nota
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Helper
 * @package View\Helper
 * @version 0.1
 * @example Class Ativo
 * @copyright Copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 */
class Ativo extends AbstractHelper
{
    public function __invoke($value)
    {
        switch ($value){
            case 0:
                $ativo = 'Não';
                break;
            default:
                $ativo = 'Sim';
        }

        return $ativo;
    }
}
