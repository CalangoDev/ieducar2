<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 07/08/16
 * Time: 22:36
 */
namespace Escola\Validator;

use Zend\Validator\AbstractValidator;

class AnoLetivoModulo extends AbstractValidator
{
    const NOT_NUM = 'numeric';
    const DATA_INICIO_EMPTY = 'dataInicioEmpty';
    const DATA_FIM_EMPTY = 'dataFimEmpty';
    const MODULO_EMPTY = 'moduloEmpty';
    const DATA_INICIO_MAIOR_DATA_FIM = 'dataInicioMaiorDataFim';

    protected $messageTemplates = [
        self::NOT_NUM => 'Campo só aceita numeros',
        self::DATA_INICIO_EMPTY => 'Campo data de Inicio esta vazio',
        self::DATA_FIM_EMPTY => 'Campo data fim esta vazio',
        self::MODULO_EMPTY => 'Campo módulo não selecionado',
        self::DATA_INICIO_MAIOR_DATA_FIM => 'Data de Início não pode ser maior que data fim'
    ];

    public function isValid($value)
    {
        $this->setValue($value);

        foreach ($value as $v){

            if ('' === $v['dataInicio']){
                $this->error(self::DATA_INICIO_EMPTY);
                return false;
            }

            if ('' === $v['dataFim']){
                $this->error(self::DATA_FIM_EMPTY);
                return false;
            }

            if ('' === $v['modulo']){
                $this->error(self::MODULO_EMPTY);
                return false;
            }

            // formatar datas para poder fazer comparacao
            $dataInicio = new \DateTime($v['dataInicio'], new \DateTimeZone('America/Sao_Paulo'));
            $dataInicio->format('Y-m-d');

            $dataFim = new \DateTime($v['dataFim'], new \DateTimeZone('America/Sao_Paulo'));
            $dataFim->format('Y-m-d');

            if ($dataInicio > $dataFim){
                $this->error(self::DATA_INICIO_MAIOR_DATA_FIM);
                return false;
            }

        }

        return true;
    }

}