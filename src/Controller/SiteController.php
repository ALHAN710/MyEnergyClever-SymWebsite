<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\Site;
use App\Entity\User1;
use App\Form\SiteType;
use App\Repository\SmartModRepository;
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

        /*return $this->render('site/show.html.twig', [
            'site' => $site,
            'user' => $site->getUser()
        ]);*/

        return $this->render('site/dashboard.html.twig', [
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
    public function updateSiteDashboard($id, Site $site, EntityManagerInterface $manager, Request $request, SmartModRepository $smartModRepo): JsonResponse
    {
        $EA       = [];
        $ER       = [];
        $Cost     = [];
        $Cosphi   = [];
        $Smax     = [];
        $Smoy     = [];
        $Liters   = [];
        $WorkTime = [];

        //Tableau de données du mois passé
        $precEA       = [];
        $precER       = [];
        $precCost     = [];
        $precCosphi   = [];
        $precSmax     = [];
        $precSmoy     = [];
        $precLiters   = [];
        $precWorkTime = [];

        $paramJSON = $this->getJSONRequest($request->getContent());
        //dump($paramJSON['selectedDate']);
        //dump($request->getContent());
        //die();
        $maxDate        = 0;
        $dat            = $paramJSON['selectedDate'];
        $dataGrid       = [];
        $dataFuel       = [];
        $precDataGrid   = [];
        $precDataFuel   = [];
        $tabEA_grid     = [];
        $prectabEA_grid = [];
        $tabEA_fuel     = [];
        $prectabEA_fuel = [];
        $subscription   = '';
        $Psous          = 0;
        if (!empty($paramJSON['selectedgridId'])) {
            $smartMod = $smartModRepo->findOneBy(['id' => $paramJSON['selectedgridId']]);
            $subscription = $smartMod->getSite()->getSubscription();
            $Psous = $smartMod->getSite()->getPsous();
        } else {
            $smartMod = $smartModRepo->findOneBy(['id' => $paramJSON['selectedfuelId']]);
            $subscription = $smartMod->getSite()->getSubscription();
            $Psous = $smartMod->getSite()->getPsous();
        }
        if (!empty($paramJSON['gridIds'])) {
            foreach ($paramJSON['gridIds'] as $gridId) {

                $dataGrid['' . $gridId . ''] = $manager->createQuery("SELECT SUM(d.kWh) AS EA, SUM(d.kVarh) AS ER, MAX(d.dateTime) AS DateTimeMax,
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

                $maxDate = $dataGrid['' . $gridId . ''][0]['DateTimeMax'];
                //dump($maxDate);
                if ($maxDate == null) {
                    $dateStr = str_replace("%", "", $dat);
                    $maxDate = $dateStr;
                }

                $date = new DateTime($maxDate);
                //dump($date);
                $interval = new DateInterval('P1M'); //P10D P1M
                $date->sub($interval);
                //dump($date);

                //Récupération des données du mois passé
                $precDataGrid['' . $gridId . ''] = $manager->createQuery("SELECT SUM(d.kWh) AS EA, SUM(d.kVarh) AS ER,
                                            MAX(d.s3ph) AS Smax, AVG(d.s3ph) AS Smoy, SUM(d.kWh) / SQRT( (SUM(d.kWh)*SUM(d.kWh)) + (SUM(d.kVarh)*SUM(d.kVarh)) ) AS Cosphi
                                            FROM App\Entity\DataMod d
                                            JOIN d.smartMod sm 
                                            WHERE d.dateTime <= :selDate
                                            AND sm.id = :modId
                                                                                                                                
                                            ")
                    ->setParameters(array(
                        'selDate' => $date->format('Y-m-d H:i:s'),
                        'modId'   => $gridId
                    ))
                    ->getResult();
                //dump($precDataGrid['' . $gridId . '']);
                $precEA['' . $gridId . '']     = number_format((float) $precDataGrid['' . $gridId . ''][0]['EA'], 2, '.', ' ');
                $precER['' . $gridId . '']     = number_format((float) $precDataGrid['' . $gridId . ''][0]['ER'], 2, '.', ' ');
                $precSmax['' . $gridId . '']   = number_format((float) $precDataGrid['' . $gridId . ''][0]['Smax'], 2, '.', ' ');
                $precSmoy['' . $gridId . '']   = number_format((float) $precDataGrid['' . $gridId . ''][0]['Smoy'], 2, '.', ' ');
                $precCosphi['' . $gridId . ''] = number_format((float) $precDataGrid['' . $gridId . ''][0]['Cosphi'], 2, '.', ' ');

                //Calcul des dépenses énergétiques
                if ($paramJSON['selectedgridId'] == $gridId) {
                    //$smartMod = $smartModRepo->findOneBy(['id' => $gridId]);
                    //$const = 1.192;
                    //$subscription = $smartMod->getSite()->getSubscription();
                    //if ($subscription == 'MT') {
                    //$Psous = $smartMod->getSite()->getPsous();
                    $tabEA_grid['' . $gridId . ''] = $manager->createQuery("SELECT d.kWh AS EA, d.dateTime AS DAT
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
                    //dump($tabEA_grid['' . $gridId . '']);

                    //Récupération des données du mois passé
                    $prectabEA_grid['' . $gridId . ''] = $manager->createQuery("SELECT d.kWh AS EA, d.dateTime AS DAT
                                            FROM App\Entity\DataMod d
                                            JOIN d.smartMod sm 
                                            WHERE d.dateTime LIKE :selDate
                                            AND sm.id = :modId                                                                              
                                            ")
                        ->setParameters(array(
                            'selDate' => $date->format('Y-m-d H:i:s'),
                            'modId'   => $gridId
                        ))
                        ->getResult();
                    //$EAHp = [];
                    //$EAP = [];
                    /*foreach ($tabHpEA['' . $gridId . ''] as $key => $value) {
                            $strhp1_ = '00:00:00';
                            $strhp2_ = '17:45:00';
                            $strhp3_ = '23:15:00';
                            $strhp4_ = '23:45:00';
                            $strp1_  = '18:00:00';
                            $strp2_  = '23:00:00';
                            //dump($value);
                            $str = (string) $tabHpEA['' . $gridId . ''][$key]['DAT']->format('Y-m-d');
                            $strHp1 = $str . ' ' . $strhp1_;
                            $strHp2 = $str . ' ' . $strhp2_;
                            $strHp3 = $str . ' ' . $strhp3_;
                            $strHp4 = $str . ' ' . $strhp4_;

                            $datHp1 = new DateTime($strHp1);
                            $datHp2 = new DateTime($strHp2);
                            $datHp3 = new DateTime($strHp3);
                            $datHp4 = new DateTime($strHp4);
                            if (($value['DAT'] >= $datHp1 && $value['DAT'] <= $datHp2) || ($value['DAT'] >= $datHp3 && $value['DAT'] <= $datHp4)) {
                                $EAHp[] = $value['EA'];
                            } else {
                                $EAP[] = $value['EA'];
                            }
                            $Cost['' . $gridId . ''] = array_sum($EAHp) * 60 + array_sum($EAP) * 85 + $Psous * 3700;
                        }*/
                    //} 
                    /*else if ($smartMod->getSite()->getSubscription() == 'Tertiary') {
                        //fdfr
                        if ($EA['' . $gridId . ''] <= 110) {
                            $Cost['' . $gridId . ''] = $EA['' . $gridId . ''] * 84 * $const;
                        } else if ($EA['' . $gridId . ''] > 110 && $EA['' . $gridId . ''] <= 400) {
                            $Cost['' . $gridId . ''] = $EA['' . $gridId . ''] * 92 * $const;
                        } else if ($EA['' . $gridId . ''] > 400) {
                            $Cost['' . $gridId . ''] = $EA['' . $gridId . ''] * 99 * $const;
                        }
                    } else if ($smartMod->getSite()->getSubscription() == 'Residential') {
                        if ($EA['' . $gridId . ''] <= 110) {
                            $Cost['' . $gridId . ''] = $EA['' . $gridId . ''] * 50 * $const;
                        } else if ($EA['' . $gridId . ''] > 110 && $EA['' . $gridId . ''] <= 400) {
                            $Cost['' . $gridId . ''] = $EA['' . $gridId . ''] * 79 * $const;
                        } else if ($EA['' . $gridId . ''] > 400 && $EA['' . $gridId . ''] <= 800) {
                            $Cost['' . $gridId . ''] = $EA['' . $gridId . ''] * 94 * $const;
                        } else if ($EA['' . $gridId . ''] > 800) {
                            $Cost['' . $gridId . ''] = $EA['' . $gridId . ''] * 99 * $const;
                        }
                    }*/
                }
            }
            //strpos(string, find, start)
            //strrpos() - Finds the position of the last occurrence of a string inside another string (case-sensitive)
            //$date_ = str_replace("%", "", $dat);
            //dump($date_);
            //dump($paramJSON['selectedgridId']);
            //dump($precDataGrid['101']);
            //(string)$diff->format('%R%a');

            /*$strhp1 = '17:45:00';
            $strhp2 = '00:00:00';
            $strhp3 = '23:15:00';
            $strhp4 = '23:45:00';*/

            //$str = (string) $tabHpEA['101'][1]['DAT']->format('Y-m-d');
            //$str = $str . ' ' . $strhp1;
            //dump($str);
            //dump($tabHpEA['101']);
            //$dat1 = new DateTime($str);
            //dump($dat1 > $tabHpEA['101'][0]['DAT']);
            //$date = new DateTime($maxDate);
            //$interval = new DateInterval('P1M');

            //$date->sub($interval);

            //dump($maxDate);
            //dump($date->format('Y-m-d H:i:s'));
            //die();
        }

        if (!empty($paramJSON['fuelIds'])) {
            foreach ($paramJSON['fuelIds'] as $fuelId) {

                $dataFuel['' . $fuelId . ''] = $manager->createQuery("SELECT SUM(d.kWh) AS EA, SUM(d.kVarh) AS ER, MAX(d.dateTime) AS DateTimeMax,
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

                $maxDate = $dataFuel['' . $fuelId . ''][0]['DateTimeMax'];
                if ($maxDate == null) {
                    $dateStr = str_replace("%", "", $dat);
                    $maxDate = $dateStr;
                }
                $date = new DateTime($maxDate);
                $interval = new DateInterval('P1M'); //P10D P1M
                $date->sub($interval);

                $precDataFuel['' . $fuelId . ''] = $manager->createQuery("SELECT SUM(d.kWh) AS EA, SUM(d.kVarh) AS ER, MAX(d.dateTime) AS DateTimeMax,
                                            Sum(d.liters) AS Liters, SUM(d.workingtime) AS WorkingTime, SUM(d.kWh) / SQRT( (SUM(d.kWh)*SUM(d.kWh)) + (SUM(d.kVarh)*SUM(d.kVarh)) ) AS Cosphi
                                            FROM App\Entity\DataMod d
                                            JOIN d.smartMod sm 
                                            WHERE d.dateTime <= :selDate
                                            AND sm.id = :modId
                                                                                                                                
                                            ")
                    ->setParameters(array(
                        'selDate' => $date->format('Y-m-d H:i:s'),
                        'modId'   => $fuelId
                    ))
                    ->getResult();
                $precEA['' . $fuelId . '']       = number_format((float) $precDataFuel['' . $fuelId . ''][0]['EA'], 2, '.', ' ');
                $precER['' . $fuelId . '']       = number_format((float) $precDataFuel['' . $fuelId . ''][0]['ER'], 2, '.', ' ');
                $precLiters['' . $fuelId . '']   = number_format((float) $precDataFuel['' . $fuelId . ''][0]['Liters'], 2, '.', ' ');
                $precWorkTime['' . $fuelId . ''] = date("H:i:s", $precDataFuel['' . $fuelId . ''][0]['WorkingTime'] / 1000);
                $precCosphi['' . $fuelId . '']   = number_format((float) $precDataFuel['' . $fuelId . ''][0]['Cosphi'], 2, '.', ' ');
                $maxDate = $precDataFuel['' . $fuelId . ''][0]['DateTimeMax'];

                //Calcul des dépenses énergétiques
                if ($paramJSON['selectedfuelId'] == $fuelId) {
                    //$smartMod = $smartModRepo->findOneBy(['id' => $fuelId]);
                    //$const = 1.192;
                    $workTime = date("H:i:s", $dataFuel['' . $fuelId . ''][0]['WorkingTime'] / 1000);
                    $precworkTime = date("H:i:s", $precDataFuel['' . $fuelId . ''][0]['WorkingTime'] / 1000);
                    //$subscription = $smartMod->getSite()->getSubscription(); 
                    //if ($subscription == 'MT') {
                    //$Psous = $smartMod->getSite()->getPsous();
                    $tabEA_fuel['' . $fuelId . ''] = $manager->createQuery("SELECT d.kWh AS EA, d.dateTime AS DAT
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
                    //dump($tabEA_fuel['' . $fuelId . '']);

                    //Récupération des données du mois passé
                    $prectabEA_fuel['' . $fuelId . ''] = $manager->createQuery("SELECT d.kWh AS EA, d.dateTime AS DAT
                                            FROM App\Entity\DataMod d
                                            JOIN d.smartMod sm 
                                            WHERE d.dateTime LIKE :selDate
                                            AND sm.id = :modId                                                                              
                                            ")
                        ->setParameters(array(
                            'selDate' => $date->format('Y-m-d H:i:s'),
                            'modId'   => $fuelId
                        ))
                        ->getResult();
                    //}
                }
            }

            /*$maxDate = $dataGrid['' . $gridId . ''][0]['DateTimeMax'];
            $date = new DateTime($maxDate);
            $interval = new DateInterval('P5D'); //P10D P1M
            $date->sub($interval);*/
            //dump($date->format('Y-m-d'));
            //dump($date);
            //dump($maxDate);

            //dump($dataFuel);
            //dump($EA);
            //dump($dataFuel['365'][0]['WorkingTime']);
            /*foreach ($WorkTime as $WT) {
                $WorkTime[] = date("H:i:s", $WT); //DateTime::createFromFormat('H:i:s', $WT);
            }*/
            //die();
        }

        //dump($precDataGrid['' . $gridId . '']);
        return $this->json([
            //'code'        => 200,
            'EA'              => $EA,
            'ER'              => $ER,
            'Cos'             => $Cosphi,
            'Smax'            => $Smax,
            'Smoy'            => $Smoy,
            'Liters'          => $Liters,
            'WorkingTime'     => $WorkTime,
            'precEA'          => $precEA,
            'precER'          => $precER,
            'precCos'         => $precCosphi,
            'precSmax'        => $precSmax,
            'precSmoy'        => $precSmoy,
            'precLiters'      => $precLiters,
            'precWorkingTime' => $precWorkTime,
            'tabEA_grid'      => $tabEA_grid['' . $paramJSON['selectedgridId'] . ''],
            'prectabEA_grid'  => $prectabEA_grid['' . $paramJSON['selectedgridId'] . ''],
            'tabEA_fuel'      => $tabEA_fuel['' . $paramJSON['selectedfuelId'] . ''],
            'prectabEA_fuel'  => $prectabEA_fuel['' . $paramJSON['selectedfuelId'] . ''],
            'subscription'    => $subscription,
            'Psous'           => $Psous,
            'workTime'        => $workTime,
            'precworkTime'    => $precworkTime
        ], 200);
    }
}
