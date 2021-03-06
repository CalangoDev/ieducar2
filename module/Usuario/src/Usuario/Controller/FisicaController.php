<?php
namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Usuario\Entity\Fisica;
use Usuario\Form\Fisica as FisicaForm;
use Usuario\Entity\EnderecoExterno;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Doctrine\ORM\EntityManager;


use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Zend\Validator\File\Size;


/**
 * Controlador que gerencia pessoas fisicas
 * 
 * @category Usuario
 * @package Controller
 * @author Eduardo Junior <ej@eduardojunior.com>
 */
class FisicaController extends ActionController
{	
	/**
	 * Mostra as pessoas fisicas cadastradas
	 * @return  void
	 */
	public function indexAction()
	{
		// $dados = $this->getEntityManager()->getRepository('Usuario\Entity\Fisica')->findAll();
		$query = $this->getEntityManager()->createQuery('SELECT f FROM Usuario\Entity\Fisica f');

		$dados = new Paginator(
			new DoctrinePaginator(new ORMPaginator($query))
		);		
		
		$dados->setCurrentPageNumber($this->params()->fromRoute('page'))->setItemCountPerPage(10);
		
		return new ViewModel(array(
			'dados' => $dados
		));
	}

	/**
	 * Busca
	 */
	public function buscaAction()
	{
		$q = (string) $this->params()->fromPost('q');		
		$query = $this->getEntityManager()->createQuery("
			SELECT

				f

			FROM

				Usuario\Entity\Fisica f
			
			WHERE

				f.nome LIKE :query 

			OR 

				f.cpf LIKE :query
		");
		$query->setParameter('query', "%".$q."%");		
		$dados = $query->getResult();		
		
		$view = new ViewModel(array(
			'dados' => $dados
		));
		$view->setTerminal(true);

		return $view;
	}

	public function saveAction()
	{

		$fisica = new Fisica();
		$form = new FisicaForm($this->getEntityManager());
		$request = $this->getRequest();

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');

		if ($id > 0){
			
			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);

			if ($fisica->getDataNasc() == null){

				$date = new \DateTime($fisica->getDataNasc(), new \DateTimeZone('America/Sao_Paulo'));	
				$fisica->setDataNasc($date->format('d-m-Y'));

			} else {

				$fisica->setDataNasc($fisica->getDataNasc()->format('d-m-Y'));

			}

//			if ($fisica->getMunicipioNascimento()){
//				var_dump('aqui');
////				$municipioNascimentoId = $fisica->getMunicipioNascimento()->getId();
////				$fisica->setMunicipioNascimento($municipioNascimentoId);
//                var_dump($fisica->getMunicipioNascimento()->getId());
//				$form->get('municipioNascimento')->setValue($fisica->getMunicipioNascimento()->getId());
//			}
//            $documento = $form->get('documento');
//            $documento->get('dataEmissaoRg')->setValue($fisica->getDocumento()->getDataEmissaoRg()->format('d-m-Y'));
//            $form->get('documento')->setValue($documento);
            //$form->get('documento')['dataEmissaoRg']->setValue($fisica->getDocumento()->getDataEmissaoRg()->format('d-m-Y'));
			$form->get('submit')->setAttribute('value', 'Atualizar');
		}
		//$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Usuario\Entity\Fisica'));
		$form->bind($fisica);

		if ($request->isPost()){

			$date = new \DateTime($this->params()->fromPost('dataNasc'), new \DateTimeZone('America/Sao_Paulo'));

            $fisica->setDataNasc($date->format('Y-m-d'));
			$request->getPost()->set('dataNasc', $fisica->getDataNasc());



			$form->setInputFilter($fisica->getInputFilter());

            $semArquivoFoto = $request->getPost()->toArray();
            $arquivoFoto = $this->params()->fromFiles('foto');

            $enderecoExterno = $semArquivoFoto['enderecoExterno'];
            $documento = $semArquivoFoto['documento'];

            if (isset($enderecoExterno['tipoLogradouro']))

                if ($enderecoExterno['tipoLogradouro'] == '')
                $enderecoExterno['tipoLogradouro'] = 0;

            if (isset($documento['orgaoEmissorRg']))

                if ($documento['orgaoEmissorRg'] == '')
                    $documento['orgaoEmissorRg'] = 0;

            // setando id do endereco externo como 0 caso seja a primeira inserção
//            if ($id == 0 && isset($enderecoExterno['id']))
//                $enderecoExterno['id'] = 0;
//
//            if (isset($semArquivoFoto['raca']))
//
//                if ($semArquivoFoto['raca'] == '')
//                    $semArquivoFoto['raca'] = null;
//
//
//
            if (isset($semArquivoFoto['estadoCivil']))

                if ($semArquivoFoto['estadoCivil'] == '')
                    $semArquivoFoto['estadoCivil'] = null;

            if (isset($semArquivoFoto['pessoaPai']))

                if ($semArquivoFoto['pessoaPai'] == '')
                    $semArquivoFoto['pessoaPai'] = null;

            if (isset($semArquivoFoto['pessoaMae']))

                if ($semArquivoFoto['pessoaMae'] == '')
                    $semArquivoFoto['pessoaMae'] = null;
//
//
//            if (isset($documento['id']))
//                $documento['id'] = 0;
//
            if (isset($documento['siglaUfEmissaoRg']))

                if ($documento['siglaUfEmissaoRg'] == '')
                    $documento['siglaUfEmissaoRg'] = 0;

            if (isset($documento['orgaoEmissorRg']))

                if ($documento['orgaoEmissorRg'] == '')
                    $documento['orgaoEmissorRg'] = 0;

            if (isset($documento['siglaUfCertidaoCivil']))

                if ($documento['siglaUfCertidaoCivil'] == '')
                    $documento['siglaUfCertidaoCivil'] = 0;

            if (isset($documento['siglaUfCarteiraTrabalho']))

                if ($documento['siglaUfCarteiraTrabalho'] == '')
                    $documento['siglaUfCarteiraTrabalho'] = 0;


            $tamanho = new Size(array('max' => 20000000));
            $extensao = new \Zend\Validator\File\Extension(array('extension' => array('gif', 'jpg', 'png')));
            $adapter = new \Zend\File\Transfer\Adapter\Http();
            $adapter->setValidators(array($tamanho, $extensao), $arquivoFoto['name']);
            $fotoValidaOuVazia = false;

            // Validando upload da foto
            if ($adapter->isValid()){
                $fotoValidaOuVazia = true;
                $adapter->setDestination(getcwd() . '/data/pessoa');
                $extensao = substr($arquivoFoto['name'], strrpos($arquivoFoto['name'], '.') + 1);
                $horaGeracaoArquivo = date('Ymdhs');
                $nomeArquivoFoto = 'pessoafisica_' . $horaGeracaoArquivo . '.' . $extensao;
                $adapter->addFilter('Rename', array('target' => getcwd() . '/data/pessoa/' . $nomeArquivoFoto . '', 'overwrite' => true));
                //$data['foto'] = $nomeArquivoFoto;
                if ($adapter->receive($arquivoFoto['name'])){
                    if ( is_file( getcwd() . '/data/pessoa/' . $nomeArquivoFoto ) ){
                        $thumbnailer = $this->getServiceLocator()->get('WebinoImageThumb');
                        $thumb = $thumbnailer->create( getcwd() . '/data/pessoa/' . $nomeArquivoFoto, $options = array(
                            'jpegQuality' => 66
                        ));
                        $dimensoes = $thumb->getCurrentDimensions();

                        if ($dimensoes['width'] > 1000){
                            $thumb->resize(1000);
                            $thumb->save( getcwd() . '/data/pessoa/' . $nomeArquivoFoto);
                        }

                    }
                    /*
                     * Tudo ok, salvar no banco de dados
                     */
                    //var_dump($fisica->getId());
                    $data['foto'] = $nomeArquivoFoto;
                    //$fisica->setFoto($nomeArquivoFoto);
                }
            }


            if ($this->params()->fromFiles('foto')['size'] == 0)
                $fotoValidaOuVazia = true;

            if (!isset($nomeArquivoFoto)){
                $nomeArquivoFoto = $arquivoFoto['name'];
            }

            $data = array_merge(
                $semArquivoFoto,
                array('foto' => $nomeArquivoFoto),
                array('enderecoExterno' => $enderecoExterno),
                array('documento' => $documento)
            );


			$form->setData($data);


			if ($form->isValid() && $fotoValidaOuVazia){

                //var_dump($form->getInputFilter()->getValues());
				// $data = $form->getData();
                //var_dump($form->getData());
				// unset($data['submit']);
				// $fisica->setData($data);


                /**
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){
                    $this->getEntityManager()->persist($fisica);
                    $this->flashMessenger()->addMessage(array("success" => "Pessoa Física Salva!"));
                } else {
                    $this->flashMessenger()->addMessage(array("success" => "Pessoa Física foi alterada!"));
                }

                $this->getEntityManager()->flush();

//                $teste = $this->getEntityManager()->find('Usuario\Entity\Fisica', $fisica->getId());
//                var_dump('pegando do banco depois do flush');
//                var_dump($teste->getFoto());

                /**
                 * Redirecionando para lista de pessoas fisicas
                 */
                return $this->redirect()->toUrl('/usuario/fisica');

			} else {

                //var_dump('invalido');
                //var_dump($form->getData());

				if ($this->params()->fromPost('dataNasc')){
					$date = new \DateTime($this->params()->fromPost('dataNasc'), new \DateTimeZone('America/Sao_Paulo'));
					$date = $date->format('d-m-Y');					
					$form->get('dataNasc')->setAttribute('value', $date);
				}

                //foto
                if (!$adapter->isValid()){
                    $dataError = $adapter->getMessages();
                    $error = array();
                    foreach($dataError as $key=>$row)
                    {
                        $error[] = $row;
                    }
                    $form->setMessages(array('foto' => $error));
                }

			}

		}

//		$id = (int) $this->params()->fromRoute('id', 0);
//
//		if ($id >0){
//
//			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);
//			//$form->get('submit')->setAttribute('value', 'Edit');
//
//		}


		return new ViewModel(array(
			'form' => $form
		));
	}

    /**
     * Detalhes de uma pessoa
     */
	public function detalhesAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id == 0)
            throw new \Exception("Código Obrigatório");

        $fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);

        if (!$fisica)
            throw new \Exception("Registro não encontrado");

        $view = new ViewModel(array(
            'data' => $fisica
        ));

        $view->setTerminal(true);

        return $view;
    }


	/**
	 * Excluir uma pessoa
     * @throws \Exception If registro não encontrado
	 * @return void
	 */
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);	
		if ($id == 0)
			throw new \Exception("Código Obrigatório");

		try{		
			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);		
			$this->getEntityManager()->remove($fisica);
			$this->getEntityManager()->flush();			
		} catch(\Exception $e){
			throw new \Exception("Registro não encontrado");			
		}
        $this->flashMessenger()->addMessage(array("success" => "Registro Removido com sucesso!"));
		return $this->redirect()->toUrl('/usuario/fisica');
	}
}