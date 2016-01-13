<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 13/01/16
 * Time: 18:25
 */
namespace Escola\View\Helper;

use Zend\View\Helper\AbstractHelper;

/***
 * Helper que exibe o tipo de Presenca
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Helper
 * @package View\Helper
 * @version 0.1
 * @example Class TipoPresenca
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 */
class TipoPresenca extends AbstractHelper
{
    public function __invoke($tipoPresenca)
    {
        switch ($tipoPresenca){
            case 1:
                $tipo = 'Apura falta no geral (unificada)';
                break;
            case 2:
                $tipo = 'Apura falta por componente curricular';
                break;
            default:
                $tipo = '';
        }

        return $tipo;
    }
}