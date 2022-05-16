<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Tapa;

/**
 * @Route("/api/postslist")
 */
class ApiController extends Controller
{
    /**
    * @Route("/autor/{autor}", methods={"GET"})
    */
    public function listPostsAutorAction($autor=null){

        $TapasRepository = $this->getDoctrine()->getRepository(Tapa::class);
        if(($this->getUser()->getRoles() == ['ROLE_ADMIN']) && ($autor==null)){
            $tapas = $TapasRepository->findAll();
        }
        else if($this->getUser()->getRoles() == ['ROLE_ADMIN'] && $autor != null){
            $tapas = $TapasRepository->findByAutor($autor);
        }
        else{
            $tapas = $TapasRepository->findByAutor($this->getUser()->getUsername());
        }
        $tapasArray = array();
        foreach ($tapas as $tapa) {
            $tapaArray = array();
            $tapaArray['titulo'] = $tapa->getTitulo();
            $tapaArray['autor']= $tapa->getAutor();
            $tapaArray['descripcion']= $tapa->getDescripcion();
            $tapaArray['fechaCreacion']= $tapa->getFechaCreacion();
            $tapasArray[] = $tapaArray;
        }
        $response = new JsonResponse($tapasArray);
        return $response;
    }

     /**
    * @Route("/titulo/{titulo}", methods={"GET"})
    */
    public function listPostsTitleAction($titulo=null){
        
        $TapasRepository = $this->getDoctrine()->getRepository(Tapa::class);
        if(($this->getUser()->getRoles() == ['ROLE_ADMIN']) && ($titulo==null)){
            $tapas = $TapasRepository->findAll();
        }
        else if($this->getUser()->getRoles() == ['ROLE_ADMIN'] && $titulo != null){
            $tapas = $TapasRepository->findByTitulo($titulo);
        }
        else{
            $tapas_a = $TapasRepository->findByTitulo($titulo);
            $tapas = [];
            for ($i=0; $i < count($tapas_a); $i++) { 
                if($this->getUser()->getUsername() == $tapas_a[$i]->getAutor()){
                    $tapas[] = $tapas_a[$i];
                }
            }

        }
        $tapasArray = array();
        foreach ($tapas as $tapa) {
            $tapaArray = array();
            $tapaArray['titulo'] = $tapa->getTitulo();
            $tapaArray['autor']= $tapa->getAutor();
            $tapaArray['descripcion']= $tapa->getDescripcion();
            $tapaArray['fechaCreacion']= $tapa->getFechaCreacion();
            $tapasArray[] = $tapaArray;
        }
        $response = new JsonResponse($tapasArray);
        return $response;
    }

}

?>