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
 * @Route("/user")
 */
class UsersController extends Controller
{

    /**
     * @Route("/newuser/{id}", name="register")
     */
    public function registerAction(Request $request,UserPasswordEncoderInterface $passwordEncoder,$id=null)
    {
        if($id != null){
            $UsuariosRepository = $this->getDoctrine()->getRepository(Usuario::class);
            $user = $UsuariosRepository->find($id);
        }
        else{
            $user = new Usuario();
        }
        $form = $this->createForm(UsuarioType::class,$user);
        $form->handleRequest($request);
        try {
             if ($form->isSubmitted() && $form->isValid()) { 
                // 3) Encode the password (you could also do this via Doctrine listener)
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
    
                //3b) Hacemos que $username == $email
                $user->setUsername($user->getEmail()); 

                //3c) $roles
                $user->setRoles(array('ROLE_USER'));
                
                // 4) save the User!
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
    
                return $this->redirectToRoute('login');
             } 
        } catch (\Throwable $th) {
            throw $th;
        }
        
        // replace this example code with whatever you need
        return $this->render('frontal/registro.html.twig',array('form'=>$form->createView()));
    }

    /**
     * @Route("/login/", name="login")
     */
    public function loginAction(Request $request,AuthenticationUtils $authenticationUtils)
    {
            // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('frontal/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

     /**
     * @Route("/admin/userslist/", name="userslist")
     */
    public function usersAction(Request $request)
    {   
        $UsuariosRepository = $this->getDoctrine()->getRepository(Usuario::class);
        $usuarios = $UsuariosRepository->findAll();
        // replace this example code with whatever you need
        return $this->render('frontal/lista_usuarios.html.twig',array('usuarios'=>$usuarios));
    }

       /**
     * @Route("/delete/{username}", name="deleteuser")
     */
    public function deleteUserAction(Request $request,$username=null)
    {
        //Si recibe un id es porque se va a modificar un post existente
        if ($username != null) {
            $UsuariosRepository = $this->getDoctrine()->getRepository(Usuario::class);
            $usuario = $UsuariosRepository->findOneByUsername($username);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($usuario); 
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('userslist');
    }
}