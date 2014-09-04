<?php
namespace Core\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Core\Entity\Uf;
// use Core\Entity\Sp;
// use Core\Entity\Ba;
/**
 * Controlador responsavel pelo o tratamento de cep do sistema
 *
 * @category Core
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class CepController extends ActionController
{
	public function indexAction()
	{		
		$cep = (string) $this->getEvent()->getRouteMatch()->getParam('value');
		if ($cep != ""){
			//Determinando a qual uf o cep pertence
			$query = $this->getEntityManager()->createQuery("
				SELECT

					uf

				FROM

					Core\Entity\Uf uf
				
				WHERE

					:cep
					
					BETWEEN uf.cep1 AND uf.cep2
			");
			$query->setParameter('cep', $cep);		
			$dados = $query->getResult();

			if (count($dados) > 0){
				$uf = ucwords(strtolower($dados[0]->getUf()));	
				$idEstado = $dados[0]->getId();
				//Verificar se existe no cep por estado
				$entity = 'Core\Entity\\' . $uf;				
				$query = $this->getEntityManager()->createQuery("
					SELECT 
						r
					FROM
						" . $entity ." r
					WHERE
						r.cep = :cep
				");				
				$query->setParameter('cep', $cep);
				$dados = $query->getResult();
				if ($dados[0]) {
					// var_dump($dados[0]);
					$dados = array(
						'id' => $dados[0]->getId(),
						'cidade' => $dados[0]->getCidade(),
						'logradouro' => utf8_encode($dados[0]->getLogradouro()),
						'bairro' => utf8_encode($dados[0]->getBairro()),
						'cep' => $dados[0]->getCep(),
						'tipoLogradouro' => $dados[0]->getTipoLogradouro(),
						'uf' => $uf,
						'idEstado' => $idEstado
					);

				}

				/**
				 * Se nao tem dados no cep por estado, pesquisar na entidade cep unico
				 */
				if (count($dados) <= 0){
					$query = $this->getEntityManager()->createQuery("
						SELECT 
							r
						FROM 
							Core\Entity\CepUnico r
						WHERE
							r.cep = :cep
					");
					$query->setParameter('cep', $cep);
					$dados = $query->getResult();
					
					//montar o JSON de retorno :D
					if ($dados[0]){
						$dados = array(
							'id' => $dados[0]->getId(),
							'nome' => utf8_encode($dados[0]->getNome()),
							'nomeSemAcento' => $dados[0]->getNomeSemAcento(),
							'cep' => $dados[0]->getCep(),
							'uf' => $uf,
							'idEstado' => $idEstado
						);
						// var_dump($dados);
					}
				}								
			}				
			
		}		
        return new JsonModel($dados);
	}
}