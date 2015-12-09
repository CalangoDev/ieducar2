<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 09/12/15
 * Time: 15:15
 */
namespace Usuario\Repository;

use Doctrine\ORM\EntityRepository;

class FisicaRepository extends EntityRepository
{
    public function getFisicaNaoFuncionario()
    {
        $querybuilder = $this->createQueryBuilder('f');
        return $querybuilder->select('f')
            ->leftJoin('Drh\Entity\Funcionario', 'funcionario', 'WITH', 'f.id = funcionario.fisica')
            ->where('funcionario.fisica IS NULL')->orderBy('f.nome', 'ASC')->getQuery()->getResult();
    }
}