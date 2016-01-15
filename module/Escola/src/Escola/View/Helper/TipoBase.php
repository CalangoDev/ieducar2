<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 14/01/16
 * Time: 20:29
 */
namespace Escola\View\Helper;

use Zend\View\Helper\AbstractHelper;

/***
 * Helper que exibe o tipo de base curricular
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Helper
 * @package View\Helper
 * @version 0.1
 * @example Class TipoBase
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 */
class TipoBase extends AbstractHelper
{
    public function __invoke($value)
    {
        switch ($value){
            case 1:
                $tipo = 'Base nacional comum';
                break;
            case 2:
                $tipo = 'Base diversificada';
                break;
            case 3:
                $tipo = 'Base profissional';
                break;
            default:
                $tipo = '';
        }

        return $tipo;
    }
}