<?php
namespace Usuario\Fixture;

use Usuario\Entity\Fisica;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\DataFixtures\SharedFixtureInterface;

class FisicaLoad implements FixtureInterface, OrderedFixtureInterface, SharedFixtureInterface
{
	private $referenceRepository;

    public function setReferenceRepository(ReferenceRepository $referenceRepository)
    {
        $this->referenceRepository = $referenceRepository;
    }

	public function load(ObjectManager $manager)
	{
		$fisica = new Fisica;
		$fisica->setNome("Admin");
		$fisica->setSexo("M");
		$fisica->setOrigemGravacao("M");
		$fisica->setOperacao("I");
		$fisica->setIdSisCad(1);
		$fisica->setSituacao("A");

		$manager->persist($fisica);
		$manager->flush();

		$this->referenceRepository->addReference('admin-user', $fisica);		

	}

	public function getOrder()
	{
		return 1;
	}
}