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

class DefaultController extends Controller
{
    /**
     * @Route("/{page}", name="homepage")
     */
    public function homeAction(Request $request, $page=1)
    {
        $service = $this->get("app.service");
        //Capturamos del repositorio de la tabla contra la DB
        $TapasRepository = $this->getDoctrine()->getRepository(Tapa::class);
        $tapas = $TapasRepository->paginaTapas($page);
        $tapas = $service->textUpper($tapas);
        $tapas = $service->textLower($tapas);
        // replace this example code with whatever you need
        return $this->render('frontal/index.html.twig',array('tapas'=>$tapas,'actualPage'=>$page));
    }

    /**
     * @Route("/myaccount/", name="myaccount")
     */
    public function myaccountAction(Request $request)
    {   
        $TapasRepository = $this->getDoctrine()->getRepository(Tapa::class);
        $tapas = $TapasRepository->findByAutor($this->getUser()->getEmail());
        // replace this example code with whatever you need
        return $this->render('frontal/account.html.twig',array('tapas'=>$tapas));
    }

}
