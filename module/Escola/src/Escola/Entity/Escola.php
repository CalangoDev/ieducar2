<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 16/01/16
 * Time: 19:49
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade Escola
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage Escola
 * @version 0.1
 * @copyright (c) 2016 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_escola")
 */
class Escola extends Entity
{
    /**
     * @var int $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $sigla
     *
     * @ORM\Column(type="string", length=30)
     */
    protected $sigla;

    /**
     * @var int $pessoa
     *
     * @ORM\OneToOne(targetEntity="Usuario\Entity\Pessoa", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="idpes")
     */
    protected $pessoa;

    /**
     * @var int $redeEnsino
     *
     * @ORM\ManyToOne(targetEntity="Escola\Entity\RedeEnsino", cascade={"persist"})
     */
    protected $redeEnsino;
}