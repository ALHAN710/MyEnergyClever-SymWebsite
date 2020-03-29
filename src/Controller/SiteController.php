<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User1;
use App\Form\SiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contrôleur des Sites
 * 
 * @Security("is_granted('ROLE_USER') and (user == site.getUser() or is_granted('ROLE_ADMIN'))", message="Vous n'avez pas le droit d'accéder à cette ressource")
 * 
 */
class SiteController extends AbstractController
{
    /** 
     * @Route("/site", name="site")
     */
    public function index()
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }

    /**
     * Permet de créer un site
     *
     * @Route("/sites/new", name = "sites_create")
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $site = new Site();

        //Permet d'obtenir un constructeur de formulaire
        // Externaliser la création du formulaire avec la cmd php bin/console make:form

        //  instancier un form externe
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);
        //dump($site);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($site->getSmartMods() as $smartMod) {
                $smartMod->setSite($site);
                $manager->persist($smartMod);
            }

            //$manager = $this->getDoctrine()->getManager();
            $manager->persist($site);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le site <strong>{$site->getName()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('sites_show', [
                'slug' => $site->getSlug()
            ]);
        }


        return $this->render(
            'site/new.html.twig',
            [
                'form' => $form->createView(),
                'user' => $this->getUser()
            ]
        );
    }

    /**
     * Permet d'afficher le formulaire d'édition d'un site
     *
     * @Route("sites/{slug}/edit", name="sites_edit")
     * @Security("is_granted('ROLE_ADMIN')", message = "Vous n'avez pas le droit d'accéder à cette ressource")
     * 
     * @return Response
     */
    public function edit(Site $site, Request $request, EntityManagerInterface $manager)
    {

        //  instancier un form externe
        $form = $this->createForm(SiteType::class, $site);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($site->getSmartMods() as $smartMod) {
                $smartMod->setSite($site);
                $manager->persist($smartMod);
            }

            //$manager = $this->getDoctrine()->getManager();
            $manager->persist($site);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications du Site <strong>{$site->getName()}</strong> ont  bien été enregistrées !"
            );

            return $this->redirectToRoute('sites_show', [
                'slug' => $site->getSlug()
            ]);
        }

        return $this->render('site/edit.html.twig', [
            'form' => $form->createView(),
            'site' => $site,
            'user' => $site->getUser()
        ]);
    }

    /**
     * Permet d'afficher un seul site
     * 
     * @Route("/sites/{slug}", name = "sites_show")
     *
     * @return Response
     */
    //public function show($slug, AdRepository $repo){
    public function show(Site $site)
    {
        //Je récupère le site qui correspond au slug !
        //$ad = $repo->findOneBySlug($slug);

        return $this->render('site/show.html.twig', [
            'site' => $site,
            'user' => $site->getUser()
        ]);
    }

    public function delete(Site $site, EntityManagerInterface $manager)
    {
        $manager->remove($site);
        $manager->flush();

        return $this->redirectToRoute("homepage");
    }
}
