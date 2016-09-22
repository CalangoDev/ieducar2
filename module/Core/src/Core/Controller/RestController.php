<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 22/09/16
 * Time: 10:45
 */
namespace Core\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractRestfulController;

class RestController extends AbstractRestfulController
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        if (null == $this->em){
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->em;
    }

}