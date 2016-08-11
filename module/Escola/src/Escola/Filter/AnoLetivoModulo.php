<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 05/08/16
 * Time: 20:44
 */
namespace Escola\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\FilterInterface;
use Zend\Filter\Exception;

class AnoLetivoModulo extends AbstractFilter implements FilterInterface
{

    public function __construct($options = null)
    {
        if (null !== $options){
            $this->setOptions($options);
        }
    }

    public function getDate($date)
    {
        //date_default_timezone_set('America/Sao_Paulo');
        $date = new \DateTime($date, new \DateTimeZone('America/Sao_Paulo'));
        var_dump($date);
        //var_dump($date->format('Y-m-d'));
        return $date;
    }

    public function filter($value)
    {
        var_dump($value);
//        foreach ($value as $v){
//            var_dump($v['dataInicio']);
//            $dataInicio = self::getDate($v['dataInicio']);
//            $dataFim = self::getDate($v['dataFim']);
//            if(strtotime($dataInicio->format('Y-m-d')) > strtotime($dataFim->format('Y-m-d'))){
//                var_dump('data inicio maior');
//            }
//            throw new Exception\RuntimeException(sprintf("Data '%s' inválido", 'teste'));
//        }
        //return null;
        return $value;
    }
}