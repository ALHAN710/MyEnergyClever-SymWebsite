<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\User1;
use App\Form\SiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contrôleur des Sites
 * 
 * @Security("user == site.getUser() or is_granted('ROLE_ADMIN')", message="Vous n'avez pas le droit d'accéder à cette ressource")
 * 
 */
class SiteController extends ApplicationController
{
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


    /**
     * Permet de mettre à jour le tableau de bord des sites
     * 
     * @Route("/update/sites/{id<\d+>}/dashboard/",name="update_sites_dashboard")
     * 
     *
     * @param [integer] $id
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSiteDashboard($id, Site $site, EntityManagerInterface $manager, Request $request): JsonResponse
    {
        $EA       = [];
        $ER       = [];
        $Cosphi   = [];
        $Smax     = [];
        $Smoy     = [];
        $Liters   = [];
        $WorkTime = [];

        $paramJSON = $this->getJSONRequest($request->getContent());
        //dump($paramJSON['selectedDate']);
        //dump($request->getContent());
        //die();
        $dat = $paramJSON['selectedDate'];
        $dataGrid = [];
        $dataFuel = [];
        if (!empty($paramJSON['gridIds'])) {
            foreach ($paramJSON['gridIds'] as $gridId) {

                $dataGrid['' . $gridId . ''] = $manager->createQuery("SELECT SUM(d.kWh) AS EA, SUM(d.kVarh) AS ER, 
                                            MAX(d.s3ph) AS Smax, AVG(d.s3ph) AS Smoy, SUM(d.kWh) / SQRT( (SUM(d.kWh)*SUM(d.kWh)) + (SUM(d.kVarh)*SUM(d.kVarh)) ) AS Cosphi
                                            FROM App\Entity\DataMod d
                                            JOIN d.smartMod sm 
                                            WHERE d.dateTime LIKE :selDate
                                            AND sm.id = :modId
                                                                                                                                
                                            ")
                    ->setParameters(array(
                        'selDate' => $dat,
                        'modId'   => $gridId
                    ))
                    ->getResult();
                $EA['' . $gridId . '']     = number_format((float) $dataGrid['' . $gridId . ''][0]['EA'], 2, '.', ' ');
                $ER['' . $gridId . '']     = number_format((float) $dataGrid['' . $gridId . ''][0]['ER'], 2, '.', ' ');
                $Smax['' . $gridId . '']   = number_format((float) $dataGrid['' . $gridId . ''][0]['Smax'], 2, '.', ' ');
                $Smoy['' . $gridId . '']   = number_format((float) $dataGrid['' . $gridId . ''][0]['Smoy'], 2, '.', ' ');
                $Cosphi['' . $gridId . ''] = number_format((float) $dataGrid['' . $gridId . ''][0]['Cosphi'], 2, '.', ' ');
            }

            //dump($dataGrid['363'][0]['Cosphi']);
            //die();
        }

        if (!empty($paramJSON['fuelIds'])) {
            foreach ($paramJSON['fuelIds'] as $fuelId) {

                $dataFuel['' . $fuelId . ''] = $manager->createQuery("SELECT SUM(d.kWh) AS EA, SUM(d.kVarh) AS ER, 
                                            Sum(d.liters) AS Liters, SUM(d.workingtime) AS WorkingTime, SUM(d.kWh) / SQRT( (SUM(d.kWh)*SUM(d.kWh)) + (SUM(d.kVarh)*SUM(d.kVarh)) ) AS Cosphi
                                            FROM App\Entity\DataMod d
                                            JOIN d.smartMod sm 
                                            WHERE d.dateTime LIKE :selDate
                                            AND sm.id = :modId
                                                                                                                                
                                            ")
                    ->setParameters(array(
                        'selDate' => $dat,
                        'modId'   => $fuelId
                    ))
                    ->getResult();
                $EA['' . $fuelId . '']       = number_format((float) $dataFuel['' . $fuelId . ''][0]['EA'], 2, '.', ' ');
                $ER['' . $fuelId . '']       = number_format((float) $dataFuel['' . $fuelId . ''][0]['ER'], 2, '.', ' ');
                $Liters['' . $fuelId . '']   = number_format((float) $dataFuel['' . $fuelId . ''][0]['Liters'], 2, '.', ' ');
                $WorkTime['' . $fuelId . ''] = date("H:i:s", $dataFuel['' . $fuelId . ''][0]['WorkingTime'] / 1000);
                $Cosphi['' . $fuelId . '']   = number_format((float) $dataFuel['' . $fuelId . ''][0]['Cosphi'], 2, '.', ' ');
            }

            //dump($dataFuel);
            //dump($EA);
            //dump($dataFuel['365'][0]['WorkingTime']);
            /*foreach ($WorkTime as $WT) {
                $WorkTime[] = date("H:i:s", $WT); //DateTime::createFromFormat('H:i:s', $WT);
            }*/
            //die();
        }

        return $this->json([
            //'code'        => 200,
            'EA'          => $EA,
            'ER'          => $ER,
            'Cos'         => $Cosphi,
            'Smax'        => $Smax,
            'Smoy'        => $Smoy,
            'Liters'       => $Liters,
            'WorkingTime' => $WorkTime
        ], 200);
    }
}
