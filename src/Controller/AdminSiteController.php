<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User1;
use App\Form\SiteType;
use App\Service\Pagination;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\ApplicationController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminSiteController extends ApplicationController
{
    /**
     * @Route("/admin/sites/{page<\d+>?1}", name="admin_sites_index")
     */
    public function index(SiteRepository $repo, $page,  Pagination $pagination)
    {
        $pagination->setEntityClass(Site::class)
            ->setCurrentPage($page)
            ->setLabelOrder('user')
            ->setOrder('ASC');

        return $this->render('admin/site/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet de créer un site
     *
     * @Route("/admin/sites/{id}/new", name = "admin_sites_create")
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
     */
    public function create(USer1 $user, Request $request, EntityManagerInterface $manager)
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

            $site->setUser($user);
            //$manager = $this->getDoctrine()->getManager();
            $manager->persist($site);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le site <strong>{$site->getName()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('admin_sites_index');
        }


        return $this->render(
            'admin/site/new.html.twig',
            [
                'form' => $form->createView(),
                'user' => $this->getUser()
            ]
        );
    }

    /**
     * Permet d'afficher le formulaire d'édition d'un site
     *
     * @Route("/admin/sites/{slug}/edit", name="admin_sites_edit")
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

            /*return $this->redirectToRoute('admin_sites_index',[
                'slug' => $site->getSlug()
            ]);*/
        }

        return $this->render('admin/site/edit.html.twig', [
            'form' => $form->createView(),
            'site' => $site,
            'user' => $site->getUser()
        ]);
    }

    /**
     * Permet d'afficher un seul site
     * 
     * @Route("/admin/sites/{slug}", name = "admin_sites_show")
     *
     * @return Response
     */
    //public function show($slug, AdRepository $repo){
    public function show(Site $site)
    {
        //Je récupère le site qui correspond au slug !
        //$ad = $repo->findOneBySlug($slug);

        return $this->render('admin/site/show.html.twig', [
            'site' => $site,
            'user' => $site->getUser()
        ]);
    }

    /**
     * Permet de supprimer un site
     * 
     * @Route("/admin/sites/{id}/delete", name="admin_sites_delete")
     *
     * @param Site $site
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(Site $site, EntityManagerInterface $manager)
    {
        $manager->remove($site);
        $manager->flush();

        $this->addFlash(
            'success',
            "La suppression du Site <strong>{$site->getName()}</strong> a été effectuées avec succès !"
        );

        return $this->redirectToRoute("admin_sites_index");
    }
}
