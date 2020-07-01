<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
   /**
    * @Route("/inscription", name="security_registration")
    */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        // Pour insérer dans la table SOL User,nous devons instancier un objet issu de l'Entity User
        //L'entité User reflète la table SQL User 
        $user = new User;

        $form = $this->createForm(RegistrationType::class, $user);

        dump($request);

        $form->handleRequest($request);

        // Si le formulaire a bien été validé (isSubmitted) et que les setteurs de $user sont correctement remplie,alor on ne tre dans le IF
        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            
            // On transmet le MDP encodé au setteur de l'objet User
            $user->setPassword($hash);
            $user->setRoles(["ROLE_USER"]);
           
            $manager->persist($user); // on prépare l'insertion
            $manager->flush();        // on execute la requete d'insertion

            //Message utilisateur
            $this->addFlash('success', 'Félicitation !! Vous être maintenant insctit, vous pouvez vous connecter');

            // // On redirige parès inscription vers la page de
            return $this->redirectToRoute('security_login');

        }

        return $this->render('security/registration.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        // permet de recupérer le dernier username (email) que l'internaute a saisie dans le formulaire de connexion en cas d'errerur de
        //   conexion
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig',[
            'last_username' => $lastUsername, // on envoi le message d'erreur et le dernier email saisie sur le template
            'error' => $error
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        //Cette méthode ne retourne rien , il nous suffit d'avoir une route pour la deconnexion
    }

}
