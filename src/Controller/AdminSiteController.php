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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    /**
     * Permet de mettre à jour le tableau de bord des sites
     * 
     * @Route("/update/sites/{id<\d+>}/dashboard/",name="update_sites_dashboard")
     *
     * @param [integer] $id
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSiteDashboard($id, EntityManagerInterface $manager, Request $request): JsonResponse
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
        //dump($content);
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
            dump($EA);
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
