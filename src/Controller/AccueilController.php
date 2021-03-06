<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccueilController extends AbstractController
{

    /**
     * Page d'accueil de bienvenue
     * @Route("/index/{prenom}/age/{age}", name="hello")
     * @Route("/hello")
     * @return void
     */
    public function hello($prenom = "Anonymous", $age = 0)
    {
        //return new Response("Hello " . $prenom . ", vous avez " . $age . " ans");
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom, 'age' => $age
            ]
        );
    }

    /**
     * Undocumented function
     *@Route("/Accueil", name="pageAccueil")
     * @return void
     */
    public function Accueil()
    {
        $userName = "PASCAL ALHAN";
        $prenomTab = ['PASCAL' => 27, 'NAOMI' => 26, 'CABREL' => 26];
        return $this->render(
            "accueil.html.twig",
            [
                'userName' => $userName, 'age' => 18,
                'prenomTab' => $prenomTab
            ]
        );
    }


    /**
     * Page d'accueil de l'application
     * 
     * @Route("/", name="homepage")
     * 
     */
    public function home(AuthenticationUtils $utils)
    {

        $user = $this->getUser();
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        if ($user !== NULL) {
            /*return $this->render('home.html.twig',[
                'user' => $user
            ]);*/
            //dump($user->getSites()[0]->getSlug());
            //die();
            /*return $this->forward('App\Controller\SiteController::show', [
                'site'  => $user->getSites()[0],

            ]);*/
            if ($user->getRoles()[0] === 'ROLE_ADMIN') {
                return $this->redirectToRoute('admin_dashboard');
            }
            return $this->redirectToRoute('sites_show', [
                'slug' => $user->getSites()[0]->getSlug()
            ]);
        } else {
            return $this->render('account/login2.html.twig', [
                'hasError' => $error !== null,
                'username' => $username
            ]);
        }
    }
}
