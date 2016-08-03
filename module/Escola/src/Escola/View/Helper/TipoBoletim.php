<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 21/05/16
 * Time: 18:25
 */
namespace Escola\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Helper que exite o tipo de boletim
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Helper
 * @package View\Helper
 * @version 0.1
 * @example Class TipoBoletim
 * @copyright Copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 */
class TipoBoletim extends AbstractHelper
{
    public function __invoke($tipoBoletim)
    {
        switch ($tipoBoletim){
            case 1:
                $tipo = 'Bimestral';
                break;
            case 2:
                $tipo = 'Trimestral';
                break;
            case 3:
                $tipo = 'Trismestral conceitual';
                break;
            case 4:
                $tipo = 'Semestral';
                break;
            case 5:
                $tipo = 'Semestral conceitual';
                break;
            case 6:
                $tipo = 'Semestral educação infantil';
                break;
            case 7;
                $tipo = 'Parecer descritivo por componente curricular';
                break;
            case 8:
                $tipo = 'Parecer descritivo geral';
                break;
            default:
                $tipo = '';
        }

        return $tipo;
    }
}