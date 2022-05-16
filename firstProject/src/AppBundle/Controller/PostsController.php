<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Form\TapaType;
use AppBundle\Form\UsuarioType;
use AppBundle\Entity\Tapa;
use AppBundle\Entity\Usuario;

/**
 * @Route("/post")
 */
class PostsController extends Controller
{
    /**
     * @Route("/current/{id}", name="post")
     */
    public function tapaAction(Request $request,$id=null)
    {
        if($id != null){
            $TapasRepository = $this->getDoctrine()->getRepository(Tapa::class);
            $tapa = $TapasRepository->find($id);
            return $this->render('frontal/tapa.html.twig',array('tapa'=>$tapa));
        }
        else{
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/new/{id}", name="newpost")
     */
    public function newtapaAction(Request $request,$id=null)
    {
        //Si recibe un id es porque se va a modificar un post existente
        if ($id != null) {
            $TapasRepository = $this->getDoctrine()->getRepository(Tapa::class);
            $tapa = $TapasRepository->find($id);
        }
        //Caso contrario estamos creando un nuevo post
        else {
            $tapa = new Tapa();
        }
        //Construye el Formulario con todos los atirbutos requeridos
        $form = $this->createForm(TapaType::class,$tapa); 
        //Obtenemos la informacion
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Rellenamos el entity tapa
            $tapa = $form->getData();
            $tapa->setFechaCreacion(new \DateTime());
            $tapa->setTop(1);
            //Se almacena una nueva tapa
            $entityManager = $this->getDoctrine()->getManager();
            //Indicamos cual va a ser el objeto que se va a querer almacenar, es decir, la tapa
            $entityManager->persist($tapa);
            //Finalizamos la comunicacion con la base de datos  
            $entityManager->flush();
            //Redireccionamos al homepage
            return $this->redirectToRoute('post',array('id'=> $tapa->getId()));
        }
        return $this->render('frontal/nuevaTapa.html.twig',array('form'=> $form->createView()));
    }

    /**
     * @Route("/delete/{id}", name="deletepost")
     */
    public function deleteTapaAction(Request $request,$id=null)
    {
        //Si recibe un id es porque se va a modificar un post existente
        if ($id != null) {
            $TapasRepository = $this->getDoctrine()->getRepository(Tapa::class);
            $tapa = $TapasRepository->find($id);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tapa); 
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('homepage');
    }
}