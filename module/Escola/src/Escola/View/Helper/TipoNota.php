<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 12/01/16
 * Time: 14:14
 */
namespace Escola\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Helper que exibe o tipo do sistema de nota
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Helper
 * @package View\Helper
 * @version 0.1
 * @example Class TipoProgresso
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 */
class TipoNota extends AbstractHelper
{
    public function __invoke($tipoNota)
    {
        switch ($tipoNota){
            case 1:
                $nota = 'Nota numérica';
                break;
            case 2:
                $nota = 'Nota conceitual';
                break;
            default:
                $nota = 'Nenhum';
        }

        return $nota;
    }
}
