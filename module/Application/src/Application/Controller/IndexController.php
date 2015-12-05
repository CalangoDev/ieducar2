<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

//use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Core\Controller\ActionController;
use Zend\Http\Headers;
use Zend\Http\Response\Stream;


/**
 * Controlador da aplicacao
 *
 * @category Application
 * @package Controller
 * @author Eduardo Junior <ej@calangodev.com.br>
 */
class IndexController extends ActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function arquivoAction()
    {
        $diretorio = (string) $this->params()->fromRoute('diretorio');
        $arquivo = (string) $this->params()->fromRoute('nome');

        switch ($diretorio) {
            case 'pessoa':
                if (is_file(getcwd() . '/data/pessoa/' . $arquivo)){
                    $file = getcwd() . '/data/pessoa/'. $arquivo;
                }
                break;
            default:
                throw new \Exception("Diretório Inexistente", 1);
                break;
        }

        $response = new Stream();
        $response->setStream(fopen($file, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($file));

        $extension = substr($arquivo, strrpos($arquivo, '.') + 1);

        // $finfo = finfo_open($extension);
        // var_dump($finfo);

        switch ($extension) {
            case 'JPG':
            case 'jpg':
                $contentType = 'image/jpeg';
                break;
            case 'pdf':
            case 'PDF':
                $contentType = 'application/pdf';
                break;
            case 'png':
            case 'PNG':
                $contentType = 'image/png';
                break;
            case 'gif':
            case 'GIF':
                $contentType = 'image/gif';
                break;
            case 'doc':
            case 'docx':
                $contentType = 'application/msword';
                break;
            case 'html':
                $contentType = 'text/html';
                break;
        }

        if ($diretorio == 'arquivo'){
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            // var_dump($finfo);
            $contentType = finfo_file($finfo, $file);
            finfo_close($finfo);
        }

        $headers = new Headers();


        // $headers->addHeaders(array(
        //     'Content-Disposition' => 'attachment; filename="' . basename($file) .'"',
        //     'Content-Type' => 'application/octet-stream',
        //     'Content-Length' => filesize($file)
        // ));
        $headers->addHeaders(array(
            'Content-Type' => $contentType,
            'Content-Length' => filesize($file)
        ));
        $response->setHeaders($headers);
        return $response;

    }
}
