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
//		$enderecoExterno = new EnderecoExterno();
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

			$form->get('submit')->setAttribute('value', 'Atualizar');
		}
		//$form->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Usuario\Entity\Fisica'));


//		$enderecoExterno->setTipoLogradouro($this->params()->fromPost('tipoLogradouro'));
//		$enderecoExterno->setSiglaUf($this->params()->fromPost('siglaUf'));
//		$enderecoExterno->setOperacao(($id > 0) ? "A" : "I");
//		$enderecoExterno->setOrigemGravacao("U");
//		$enderecoExterno->setLogradouro($this->params()->fromPost('logradouro'));
//
//        if ($this->params()->fromPost('cidade'))
//			$enderecoExterno->setCidade($this->params()->fromPost('cidade'));
//
//        if ($this->params()->fromPost('cep')){
//            $enderecoExterno->setCep($this->params()->fromPost('cep'));
//            var_dump('TEM CEP TENTANDO PREENCHER ELE');
//        }


//        $enderecoExterno->setIdsisCad(1);
//		$fisica->setEnderecoExterno($enderecoExterno);

		$form->bind($fisica);

		if ($request->isPost()){

			/**
			 * $id vindo do formulario se id > 0 é update/alteracao se nao é insercao
			 */
			$id  = (int) $this->params()->fromPost('id', 0);
			$date = new \DateTime($this->params()->fromPost('dataNasc'), new \DateTimeZone('America/Sao_Paulo'));
			$fisica->setDataNasc($date->format('Y-m-d'));			
			$request->getPost()->set('dataNasc', $fisica->getDataNasc());
			$form->setInputFilter($fisica->getInputFilter());						

			$cpf  = $this->params()->fromPost('cpf', 0);	

			if ($cpf == '' || $cpf == 0){

				$fisica->removeInputFilter('cpf');				
				$form->remove('cpf');
				// unset($data['cpf']);

			}

            $raca = $this->params()->fromPost('raca', 0);
            if ($raca == '' || $raca == 0){
                $fisica->removeInputFilter('raca');
                $form->remove('raca');
            }

            $estadoCivil = $this->params()->fromPost('estadoCivil');
            if ($estadoCivil == '' || $estadoCivil == 0){
                $fisica->removeInputFilter('estadoCivil');
                $form->remove('estadoCivil');
            }

			$dataNasc  = $this->params()->fromPost('dataNasc', 0);

            if ($dataNasc == '')
				$fisica->removeInputFilter('dataNasc');

            $pessoaMae = $this->params()->fromPost('pessoaMae', 0);

            if ($pessoaMae == '' || $pessoaMae == 0){
                $fisica->removeInputFilter('pessoaMae');
                $form->remove('pessoaMae');
            }

            $pessoaPai = $this->params()->fromPost('pessoaPai');

            if ($pessoaPai == '' || $pessoaPai == 0){
                $fisica->removeInputFilter('pessoaPai');
                $form->remove('pessoaPai');
            }

			$enderecoExterno = $this->params()->fromPost('enderecoExterno');
            var_dump($enderecoExterno);

			if ($enderecoExterno == '' || $enderecoExterno == 0){
				$fisica->removeInputFilter('enderecoExterno');
				$form->remove('enderecoExterno');
			}

			// $data = $request->getPost();			
			// $arrayEndExterno = array(
			// 	'cep' => $data->get('cep'),
			// 	'siglaUf' => $data->get('siglaUf'),
			// 	'tipoLogradouro' => $data->get('tipoLogradouro'),
			// 	'zonaLocalizacao' => $data->get('zonaLocalizacao'),
			// 	'cidade' => $data->get('cidade')
			// );
			// $data->set('enderecoExterno', $arrayEndExterno);

			// unset($data['cep']);
			// unset($data['siglaUf']);
			// unset($data['tipoLogradouro']);
			// unset($data['zonaLocalizacao']);			
			// var_dump($request->getPost());

			// $form->setValidationGroup(array(
			//      'nome',
			//      'enderecoExterno' => array(
			//         'cep',
			//         'siglaUf',
			//         'tipoLogradouro',
			//         'zonaLocalizacao',
			//         'cidade'
			//     ),
			// ));

            $semArquivoFoto = $request->getPost()->toArray();
            $arquivoFoto = $this->params()->fromFiles('foto');
            $data = array_merge(
                $semArquivoFoto,
                array('foto' => $arquivoFoto['name'])
            );

//            var_dump($data);

			$form->setData($data);
			// $form->setData($data);
			// var_dump($request->getPost());

            $tamanho = new Size(array('max' => 20000000));
            $extensao = new \Zend\Validator\File\Extension(array('extension' => array('gif', 'jpg', 'png')));
            $adapter = new \Zend\File\Transfer\Adapter\Http();
            $adapter->setValidators(array($tamanho, $extensao), $arquivoFoto['name']);
            $fotoValidaOuVazia = false;

            if ($adapter->isValid())
                $fotoValidaOuVazia = true;

            if ($this->params()->fromFiles('foto')['size'] == 0)
                $fotoValidaOuVazia = true;
						
			if ($form->isValid() && $fotoValidaOuVazia){
				// $data = $form->getData();				
				// unset($data['submit']);
				// $fisica->setData($data);

                // Validando upload da foto
				if ($adapter->isValid()){
					$adapter->setDestination(getcwd() . '/data/pessoa');
                    $extensao = substr($arquivoFoto['name'], strrpos($arquivoFoto['name'], '.') + 1);
                    $horaGeracaoArquivo = date('Ymdhs');
                    $nomeArquivoFoto = 'pessoafisica_' . $horaGeracaoArquivo . '.' . $extensao;
                    $adapter->addFilter('Rename', array('target' => getcwd() . '/data/pessoa/' . $nomeArquivoFoto . '', 'overwrite' => true));
                    $data['foto'] = $nomeArquivoFoto;
                    if ($adapter->receive($arquivoFoto['name'])){
                        if (is_file(getcwd() . '/data/pessoa/' . $data['foto'])){
                            /*
                             * Tudo ok, salvar no banco de dados
                             */
                            $fisica->setFoto($data['foto']);
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
                    }
				}


                /**
                 * Persistindo os dados
                 */
                $id = (int) $this->params()->fromPost('id', 0);
                if ($id == 0){

                        // $enderecoExterno = new EnderecoExterno();
                        // $enderecoExterno->setPessoa($fisica);
                        // $enderecoExterno->setLogradouro($this->params()->fromPost('logradouro'));
                        // $enderecoExterno->setCidade($this->params()->fromPost('cidade'));
                        // $enderecoExterno->setSiglaUf($this->params()->fromPost('uf'));
                        // $enderecoExterno->setSiglaUf($this->params()->fromPost('uf'));
                        // $enderecoExterno->setOrigemGravacao("U");
                        // $enderecoExterno->setOperacao(($id > 0) ? "A" : "I");
                        // $enderecoExterno->setIdsisCad(1);
                        // $enderecoExterno->setPessoa($fisica);
                    //var_dump($fisica->getEnderecoExterno());
                    $this->getEntityManager()->persist($fisica);
                    //$enderecoExterno->setPessoa($fisica);
                    // $this->getEntityManager()->persist($enderecoExterno);
                    // $pessoa = $this->getEntityManager()->find('Usuario\Entity\Pessoa', $fisica->getId());
                    $this->flashMessenger()->addSuccessMessage('Pessoa Salva');
                } else {
                    $this->flashMessenger()->addSuccessMessage('Pessoa foi alterada!');
                }

                $this->getEntityManager()->flush();

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

                /**
                 * Checar isso e ver o teste
                 * form invalido, onde nao tenha cpf
                 */
				if ($cpf == ''){

                    $form->add(array(
					    'name' => 'cpf',
					    'attributes' => array(
                            'type' => 'text',
                            'class' => 'form-control cpf',
                            'pattern' => "\d{3}\.\d{3}\.\d{3}-\d{2}",
                            'title' => "Digite o CPF no formato nnn.nnn.nnn-nn"
					    ),
					    'options' => array(
						    'label' => 'CPF <small>nnn.nnn.nnn-nn</small>'
					    ),
				    ));

				}

                /**
                 * mesmo problema do cpf ...
                 */
                if ($raca == ''){

                    $form->add(array(
                        'name' => 'raca',
                        'attributes' => array(
                            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                            'class' => 'form-control chosen-select',
                            'style' => 'height:100px;',
                        ),
                        'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                        'options' => array(
                            'label' => 'Raça:',
//				'empty_option' => 'Selecione',
                            'object_manager' => $this->getEntityManager(),
                            'target_class' => 'Usuario\Entity\Raca',
                            'property' => 'nome',
                            'find_method' => array(
                                'name' => 'findBy',
                                'params' => array(
                                    'criteria' => array('ativo' => true),
                                    'orderBy' => array('nome' => 'ASC')
                                ),
                            ),
                            'display_empty_item' => true,
                            'empty_item_label'   => 'Selecione',
                        ),
                    ));


                    $form->add(array(
                        'name' => 'estadoCivil',
                        'attributes' => array(
                            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                            'class' => 'form-control chosen-select',
                            'style' => 'height:100px;',
                        ),
                        'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                        'options' => array(
                            'label' => 'Estado Cívil:',
                            'object_manager' => $this->getEntityManager(),
                            'target_class' => 'Usuario\Entity\EstadoCivil',
                            'property' => 'descricao',
                            'find_method' => array(
                                'name' => 'findBy',
                                'params' => array(
                                    'criteria' => array(),
                                    'orderBy' => array('descricao' => 'ASC')
                                ),
                            ),
                            'display_empty_item' => true,
                            'empty_item_label' => 'Selecione',
                        ),
                    ));


                    $form->add(array(
                        'name' => 'pessoaMae',
                        'attributes' => array(
                            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                            'class' => 'form-control chosen-select',
                            'style' => 'height:100px;',
                        ),
                        'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                        'options' => array(
                            'label' => 'Mãe:',
                            'object_manager' => $this->getEntityManager(),
                            'target_class' => 'Usuario\Entity\Fisica',
                            'property' => 'nome',
                            'find_method' => array(
                                'name' => 'findBy',
                                'params' => array(
                                    'criteria' => array('sexo' => 'F', 'situacao' => 'A'),
                                    'orderBy' => array('nome' => 'ASC')
                                ),
                            ),
                            'display_empty_item' => true,
                            'empty_item_label' => 'Informe o nome da mãe, CPF, ou RG da pessoa',
                            'label_generator' => function($em) {
                                $label = '';
                                if ($em->getNome()){
                                    $label .=  $em->getNome();
                                }
                                if ($em->getCpf()){
                                    $label .= ' - CPF (' . $em->getCpf() . ')';
                                }
                                /*
                                if ($em->getRg()){
                                    $label .= ' - ' . $em->getRg();
                                }*/

                                return $label;

                            },
                        ),
                    ));

                    $form->add(array(
                        'name' => 'pessoaPai',
                        'attributes' => array(
                            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                            'class' => 'form-control chosen-select',
                            'style' => 'height:100px;',
                        ),
                        'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                        'options' => array(
                            'label' => 'Pai:',
                            'object_manager' => $this->getEntityManager(),
                            'target_class' => 'Usuario\Entity\Fisica',
                            'property' => 'nome',
                            'find_method' => array(
                                'name' => 'findBy',
                                'params' => array(
                                    'criteria' => array('sexo' => 'M', 'situacao' => 'A'),
                                    'orderBy' => array('nome' => 'ASC')
                                ),
                            ),
                            'display_empty_item' => true,
                            'empty_item_label' => 'Informe o nome do pai, CPF, ou RG da pessoa',
                            'label_generator' => function($em) {
                                $label = '';
                                if ($em->getNome()){
                                    $label .=  $em->getNome();
                                }
                                if ($em->getCpf()){
                                    $label .= ' - CPF (' . $em->getCpf() . ')';
                                }
                                /*
                                if ($em->getRg()){
                                    $label .= ' - ' . $em->getRg();
                                }*/

                                return $label;

                            },
                        ),
                    ));

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

		}

        /*
         * codigo desnecessario

		$id = (int) $this->params()->fromRoute('id', 0);

		if ($id >0){

			$fisica = $this->getEntityManager()->find('Usuario\Entity\Fisica', $id);			
			//$form->get('submit')->setAttribute('value', 'Edit');
         *
		}
        */

		return new ViewModel(array(
			'form' => $form
		));
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
		
		return $this->redirect()->toUrl('/usuario/fisica');
	}
}