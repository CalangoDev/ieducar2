<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 13/01/16
 * Time: 16:06
 */
namespace Escola\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Helper que exibe o parecer descritivo de acordo com o valor passado
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Helper
 * @package View\Helper
 * @version 0.1
 * @example Class ParecerDescritivo
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 */
class ParecerDescritivo extends AbstractHelper
{
    public function __invoke($parecerDescritivo)
    {
        switch($parecerDescritivo){

            case 0:
                $parecer = 'Não usar parecer descritivo';
                break;
            case 2:
                $parecer = 'Um parecer por etapa e por componente curricular';
                break;
            case 3:
                $parecer = 'Um parecer por etapa, geral';
                break;
            case 5:
                $parecer = 'Uma parecer por ano letivo e por componente curricular';
                break;
            case 6:
                $parecer = 'Um parecer por ano letivo, geral';
                break;
            default:
                $parecer = 'Não usar parecer descritivo';

        }

        return $parecer;
    }
}