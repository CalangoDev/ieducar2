<?php
namespace Auth\Fixture;

use Auth\Entity\Resource;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class ResourceLoad implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$resources = array(
			array(
				'nome' => 'Auth\Controller\Resource.index',
				'descricao' => 'Lista os Resources do sistema'
			),
			array(
				'nome' => 'Auth\Controller\Resource.busca',
				'descricao' => 'Busca de Resources'
			),
			array(
				'nome' => 'Auth\Controller\Resource.save',
				'descricao' => 'Salva ou Edita um Resource'
			),
			array(
				'nome' => 'Auth\Controller\Resource.delete',
				'descricao' => 'Remove um resource do sistema'
			),
			array(
				'nome' => 'Auth\Controller\Role.index',
				'descricao' => 'Lista Regras Cadastradas'
			),
			array(
				'nome' => 'Auth\Controller\Role.busca',
				'descricao' => 'Busca de Regras'
			),
			array(
				'nome' => 'Auth\Controller\Role.save',
				'descricao' => 'Salva ou edita uma regra'
			),
			array(
				'nome' => 'Auth\Controller\Role.delete',
				'descricao' => 'Remove uma regra'
			),
			array(
				'nome' => 'Drh\Controller\Setor.index',
				'descricao' => 'Tela inicial de setores'
			),
			array(
				'nome' => 'Drh\Controller\Setor.save',
				'descricao' => 'Salva ou edita um setor'
			),
			array(
				'nome' => 'Drh\Controller\Setor.delete',
				'descricao' => 'Remove um setor'
			),
			//faltando busca do setor
			array(
				'nome' => 'Usuario\Controller\Fisica.index',
				'descricao' => 'Lista de pessoas fisicas'
			),
			array(
				'nome' => 'Usuario\Controller\Fisica.busca',
				'descricao' => 'Busca de pessoas físicas'
			),
			array(
				'nome' => 'Usuario\Controller\Fisica.save',
				'descricao' => 'Salva ou edita pessoas fisicas'
			),
			array(
				'nome' => 'Usuario\Controller\Fisica.delete',
				'descricao' => 'Remove uma pessoa física'
			),
            array(
                'nome' => 'Core\Controller\Municipio.index',
                'descricao' => 'Lista de municipios, por busca'
            )

		);

		foreach ($resources as $key => $value) {
			$resource = new Resource;
			$resource->setNome($value['nome']);
			$resource->setDescricao($value['descricao']);

			$manager->persist($resource);
		}
		
		$manager->flush();
	}
}