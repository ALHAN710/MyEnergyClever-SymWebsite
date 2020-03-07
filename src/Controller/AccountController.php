<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\User1;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\Registration1Type;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     * 
     * @Route("/login2", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig',[
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     * 
     * @Route("/login", name="account_login2")
     */
    public function login2(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login2.html.twig',[
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/logout", name = "account_logout")
     * 
     * @return void
     */
    public function logout(){}


    /**
     * Permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
       //$user = new User(); 
       $user = new User1(); 

       //$form = $this->createForm(RegistrationType::class, $user);
       $form = $this->createForm(Registration1Type::class, $user);

       $form->handleRequest($request);

       if( $form->isSubmitted() && $form->isValid() ){
           $hash = $encoder->encodePassword($user, $user->getHash());
           $user->setHash($hash);

           $manager->persist($user);
           $manager->flush();

           $this->addFlash(
               'success',"Compte utilisateur crée. Veuillez vous connecter !"
           );

           return $this->redirectToRoute('account_login2');
       }
       return $this->render('account/registration.html.twig', [
           'form' => $form->createView()
       ]); 
    }

    /**
     * Permet d'afficher et de traiter le formulaire de modification de profil
     *
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager){
        $user = $this->getUser();
        dump($user);
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les Modifications du profil ont été enregistrées avec succès"
            );

        }

        return $this->render('account/profile.html.twig',[
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     * 
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager){
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //1. Vérifier que le oldpassword soit le même que celui de l'user
            if( !password_verify($passwordUpdate->getNewPassword(), $user->getHash()) ){
                //Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe saisi n'est pas votre mot de passe actuel"));
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                
                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié"
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig',[
            'form' => $form->createView(),
            'user' => $user
        ]);

    }
}
