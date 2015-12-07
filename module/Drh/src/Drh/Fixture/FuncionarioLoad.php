<?php
namespace Portal\Fixture;

use Portal\Entity\Funcionario;
use Usuario\Entity\Fisica;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class FuncionarioLoad extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		
		$funcionario = new Funcionario();
		$funcionario->setRefCodPessoaFj($this->getReference('admin-user'));
		$funcionario->setMatricula('admin');
		$funcionario->setSenha('admin');
		$funcionario->setAtivo(1);
		$funcionario->setSuperAdmin(1);

		$manager->persist($funcionario);
		$manager->flush();

	}

	public function getOrder()
	{
		return 2;
	}
}