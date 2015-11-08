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
 * @ORM\Table(name="Students")
 */
class User
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
    protected $name;

    /**
     * @ORM\OneToOne(targetEntity="City")
     */
    protected $city;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCity(City $city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }
}