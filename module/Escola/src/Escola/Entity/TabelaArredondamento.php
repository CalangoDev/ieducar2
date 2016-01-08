<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 08/01/16
 * Time: 13:39
 */
namespace Escola\Entity;

use Core\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidade TabelaArredondamento
 *
 * @author Eduardo Junior <ej@calangodev.com.br>
 * @category Entidade
 * @subpackage TabelaArrendondamento
 * @version 0.2
 * @example Class TabelaArredondamento
 * @copyright Copyright (c) 2015 CalangoDev (http://www.calangodev.com.br)
 *
 * @ORM\Entity
 * @ORM\Table(name="escola_tabela_arredondamento")
 */
class TabelaArredondamento extends Entity
{

    public function __construct()
    {
        $this->notas = new ArrayCollection();
    }

    /**
     * @var int $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $nome Nome da Tabela
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $nome;

    /**
     * @var smallint $tipoNota
     *
     * Pode ser nota numerica ou conceitual ( 1 = numerica, 2 = conceitual )
     *
     * @ORM\Column(type="smallint", length=1, nullable=false)
     */
    protected $tipoNota = 1;

    /**
     * @var Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Escola\Entity\TabelaArredondamentoValor", mappedBy="tabelaArredondamento",
     *     cascade={"all"}, orphanRemoval=true, fetch="LAZY")
     */
    protected $notas;

    public function addNotas(Collection $notas)
    {
        foreach ($notas as $nota) $this->notas->add($nota);
    }

    public function removeNotas(Collection $notas)
    {
        foreach ($notas as $nota) $this->notas->removeElement($nota);
    }

    public function getNotas()
    {
        return $this->notas;
    }

}