<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/11/15
 * Time: 15:02
 */
namespace Sandbox\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Documentosandbox
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=48)
     */
    protected $nome;

    /**
     * @ORM\OneToOne(targetEntity="Sandbox\Entity\Usersandbox", mappedBy="documentosandbox", cascade={"all"})
     */
    protected $usersandbox;

    /**
     * @var string $tipoLogradouro Id do tipo de logradouro
     * @ORM\ManyToOne(targetEntity="Core\Entity\TipoLogradouro", cascade={"persist"})
     */
    protected $tipoLogradouro;



    public function getId()
    {
        return $this->id;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getUsersandbox()
    {
        return $this->usersandbox;
    }

    public function setUsersandbox(Usersandbox $usersandbox)
    {
        $this->usersandbox = $usersandbox;
    }

    public function getTipoLogradouro()
    {
        return $this->tipoLogradouro;
    }

    public function setTipoLogradouro(\Core\Entity\TipoLogradouro $tipoLogradouro)
    {
        $this->tipoLogradouro = $tipoLogradouro;
    }


}