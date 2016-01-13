<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 12/01/16
 * Time: 13:56
 */
namespace Escola\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Helper que exibe o tipo de progressao de acordo com o value
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Helper
 * @package View\Helper
 * @version 0.1
 * @example Class TipoProgresso
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 */
class TipoProgressao extends AbstractHelper
{
    public function __invoke($tipoProgresso)
    {
        switch ($tipoProgresso){
            case 1:
                $progressao = 'Continuada';
                break;
            case 2:
                $progressao = 'Não-continuada automática: média e presença';
                break;
            case 3:
                $progressao = 'Não-continuada automática: somente média';
                break;
            case 4:
                $progressao = 'Não-continuada manual';
                break;
            default:
                $progressao = '';
        }

        return $progressao;
    }
}
