<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/11/15
 * Time: 15:10
 */
namespace Sandbox\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="usersandbox")
 */
class Usersandbox
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
     * @ORM\OneToOne(targetEntity="Documentosandbox", inversedBy="usersandbox", cascade={"persist"})
     */
    protected $documentosandbox;

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getDocumentosandbox()
    {
        return $this->documentosandbox;
    }

    public function setDocumentosandbox(Documentosandbox $documentosandbox = null)
    {
        $this->documentosandbox = $documentosandbox;
    }

}