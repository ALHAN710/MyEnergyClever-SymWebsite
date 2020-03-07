<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * Page d'accueil des annonces affichant toutes les annonces disponibles
     * @Route("/ads", name="ad_index")
     * 
     * @return Response
     */
    public function index(AdRepository $repo)
    {
       // $repo = $this->getDoctrine()->getRepository(Ad::class);

        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
        ]);
    }
     
    /**
     * Permet de créer une annonce
     *
     * @Route("/ads/new", name = "ads_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $ad = new Ad();
/* //Ajout manuel d'une image pour exemple
        $image = new Image();
        $image2 = new Image();

        $image->setUrl('http://placehold.it/400x200')
              ->setCaption('Titre 1');
            
       
        $image2->setUrl('http://placehold.it/400x200')
              ->setCaption('Titre 2');
            
        $ad->addImage($image)
           ->addImage($image2);
*/


        //Permet d'obtenir un constructeur de formulaire
     /* Externaliser la création du formulaire avec la cmd php bin/console make:form

        $form = $this->createFormBuilder($ad)
                     ->add('title')
                     ->add('introduction')
                     ->add('content')
                     ->add('rooms')
                     ->add('price')
                     ->add('coverImage')
                     ->add('save', SubmitType::class, [
                         'label' => 'Créer la nouvelle annonce',
                         'attr' =>[
                             'class' => 'btn btn-primary'
                         ]
                     ])
                     ->getForm();*/
    //  instancier un form externe
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);
        //dump($ad);

    /*    $this->addFlash(
            'success',
            "L'annonce <strong>Test</strong> a bien été enregistré !"
        );
        $this->addFlash(
            'success',
            "Deuxième flash"
        );
        $this->addFlash(
            'danger',
            "Message d'erreur"
        );
    */
        
        if( $form->isSubmitted() && $form->isValid() ){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }

            //$manager = $this->getDoctrine()->getManager();
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }

        
        return $this->render('ad/new.html.twig',[
            'form' => $form->createView()
        ]
        );
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("ads/{slug}/edit", name="ads_edit")
     * 
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager){
     
        //  instancier un form externe
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }

            //$manager = $this->getDoctrine()->getManager();
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont  bien été enregistrées !"
            );

            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig',[
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     * 
     * @Route("/ads/{slug}", name = "ads_show")
     *
     * @return Response
     */
    //public function show($slug, AdRepository $repo){
    public function show(Ad $ad){
        //Je récupère l'annonce qui correspond au slug !
        //$ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig',[
            'ad' => $ad
        ]);

    }

}
