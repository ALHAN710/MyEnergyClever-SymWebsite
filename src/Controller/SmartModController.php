<?php

namespace App\Controller;

use App\Entity\ApproFuel;
use DateTime;
use App\Entity\SmartMod;
use App\Form\ApproType;
use App\Repository\SmartModRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Contrôleur des modules
 * 
 * @Security("user == smartMod.getSite().getUser() or is_granted('ROLE_ADMIN')", message="Vous n'avez pas le droit d'accéder à cette ressource")
 * 
 */
class SmartModController extends ApplicationController
{

    /**
     * Permet d'afficher les graphes d'un module
     *
     * @Route("/smart/mod/{id<\d+>}/graphs", name="show_mod_graphs")
     * 
     * @return Response
     */
    public function showGraph(SmartMod $smartMod)
    {
        return $this->render('smart_mod/showGraph.html.twig', [
            'smartMod' => $smartMod,
            'user' => $smartMod->getSite()->getUser()
        ]);
    }

    /**
     * Permet de mettre à jour les graphes liés aux données d'un module
     *
     * @Route("/update/mod/{id<\d+>}/graphs/",name="update_mod_graphs")
     * 
     * @param [interger] $id
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function updateGraph($id, SmartMod $smartMod, EntityManagerInterface $manager, Request $request): Response
    {
        //$smartModRepo = $this->getDoctrine()->getRepository(SmartModRepository::class);
        //$smartMod = $smartModRepo->find($id);
        //dump($smartModRepo);
        //dump($smartMod->getModType());
        //$temps = DateTime::createFromFormat("d-m-Y H:i:s", "120");
        //dump($temps);
        //die();
        $date        = [];
        $VA          = [];
        $VB          = [];
        $VC          = [];
        $SA          = [];
        $SB          = [];
        $SC          = [];
        $S3ph        = [];
        $kWh         = [];
        $kVarh       = [];
        $dateE       = [];
        $idc         = [];
        $idd         = [];
        $idcRef      = [];
        $iddRef      = [];
        $liters      = [];
        $workingTime = [];
        $workingtime = [];


        $dateparam = $request->get('selectedDate'); // Ex : %2020-03-20%
        //$dat = "2020-02"; //'%' . $dat . '%'
        $dat = substr($dateparam, 0, 8); // Ex : %2020-03
        //dump($dat);
        //die();
        $dat = $dat . '%';


        $Energy = $manager->createQuery("SELECT SUBSTRING(d.dateTime, 1, 10) AS jour, SUM(d.kWh) AS kWh, SUM(d.kVarh) AS kVarh 
                                        FROM App\Entity\DataMod d
                                        JOIN d.smartMod sm 
                                        WHERE d.dateTime LIKE :selDate
                                        AND sm.id = :smartModId
                                        GROUP BY jour
                                        ORDER BY jour ASC
                                                                                
                                        ")
            ->setParameters(array(
                'selDate'      => $dat,
                'smartModId'   => $id
            ))
            ->getResult();
        //dump($Energy);
        //die();
        foreach ($Energy as $d) {
            $dateE[] = $d['jour'];
            $kWh[]   = number_format((float) $d['kWh'], 2, '.', '');
            $kVarh[] = number_format((float) $d['kVarh'], 2, '.', '');
        }


        /*
        SELECT d.dateTime as dat, d.va, d.vb, d.vc, d.sa, d.sb, d.sc, d.s3ph
                                                FROM App\Entity\DataMod d, App\Entity\SmartMod sm 
                                                WHERE d.dateTime LIKE :selDate
                                                AND sm.id = :modId
                                                ORDER BY dat ASC
        */
        if ($smartMod->getModType() == 'GRID') {
            $data = $manager->createQuery("SELECT d.dateTime as dat, d.va, d.vb, d.vc, d.sa, d.sb, d.sc, d.s3ph,
                                        SQRT( (((d.sa*d.sa) + (d.sb*d.sb) + (d.sc*d.sc))/3) - ((d.sa + d.sb + d.sc)/3)*((d.sa + d.sb + d.sc)/3) )*100 AS IDC,
                                        SQRT( (((d.va*d.va) + (d.vb*d.vb) + (d.vc*d.vc))/3) - ((d.va + d.vb + d.vc)/3)*((d.va + d.vb + d.vc)/3) )*100 AS IDD
                                        FROM App\Entity\DataMod d 
                                        JOIN d.smartMod sm
                                        WHERE d.dateTime LIKE :selDate
                                        AND sm.id = :smartModId
                                        ORDER BY dat ASC
                                        
                                        ")
                ->setParameters(array(
                    'selDate'      => $dateparam,
                    'smartModId'   => $id
                ))
                ->getResult();

            /*$Energy = $manager->createQuery("SELECT SUBSTRING(d.dateTime, 1, 10) AS jour, SUM(d.kWh) AS kWh, SUM(d.kVarh) AS kVarh
                                        FROM App\Entity\DataMod d, App\Entity\SmartMod sm WHERE d.dateTime LIKE :selDate
                                        AND sm.id = :modId
                                        GROUP BY jour
                                        ORDER BY jour ASC
                                                                                
                                        ")
                ->setParameters(array(
                    'selDate' => $dateparam,
                    'modId'   => $id
                ))
                ->getResult();*/
            //dump($data);
            foreach ($data as $d) {
                $date[]  = $d['dat']->format('Y-m-d H:i:s');
                $VA[]    = $d['va'];
                $VB[]    = $d['vb'];
                $VC[]    = $d['vc'];
                $SA[]    = $d['sa'];
                $SB[]    = $d['sb'];
                $SC[]    = $d['sc'];
                $S3ph[]  = $d['s3ph'];
                $idc[]   = number_format((float) $d['IDC'], 2, '.', '');
                $idd[]   = number_format((float) $d['IDD'], 2, '.', '');
                //$kWh[]   = $d['kWh'];
                //$kVarh[] = $d['kVarh'];
            }

            foreach ($idc as $i) {
                $idcRef[] = 20;
            }
            foreach ($idd as $i) {
                $iddRef[] = 20;
            }

            /*foreach ($Energy as $d) {
                $dateE[] = $d['jour'];
                $kWh[]   = $d['kWh'];
                $kVarh[] = $d['kVarh'];
            }*/

            //dump($Energy);
            //die();

            return $this->json([
                'code'    => 200,
                'selDate' => $dateparam,
                'date'    => $date,
                'V'       => [$VA, $VB, $VC],
                'S'       => [$SA, $SB, $SC],
                'S3ph'    => $S3ph,
                'dateE'   => $dateE,
                'kWh'     => $kWh,
                'kVarh'   => $kVarh,
                'IDC'     => [$idc, $idcRef],
                'IDD'     => [$idd, $iddRef]
            ], 200);
        } else if ($smartMod->getModType() == 'FUEL') {
            $data = $manager->createQuery("SELECT d.dateTime as dat, d.va, d.vb, d.vc, d.sa, d.sb, d.sc, d.s3ph, d.s3phmax,
                                        SQRT( (((d.sa*d.sa) + (d.sb*d.sb) + (d.sc*d.sc))/3) - ((d.sa + d.sb + d.sc)/3)*((d.sa + d.sb + d.sc)/3) )*100 AS IDC,
                                        SQRT( (((d.va*d.va) + (d.vb*d.vb) + (d.vc*d.vc))/3) - ((d.va + d.vb + d.vc)/3)*((d.va + d.vb + d.vc)/3) )*100 AS IDD
                                        FROM App\Entity\DataMod d
                                        JOIN d.smartMod sm 
                                        WHERE d.dateTime LIKE :selDate
                                        AND sm.id = :smartModId
                                        ORDER BY dat ASC
                                        
                                        ")
                ->setParameters(array(
                    'selDate'      => $dateparam,
                    'smartModId'   => $id
                ))
                ->getResult();

            $fuelData = $manager->createQuery("SELECT SUBSTRING(d.dateTime, 1, 10) AS jour, d.liters, d.workingtime 
                                        FROM App\Entity\DataMod d
                                        JOIN d.smartMod sm 
                                        WHERE d.dateTime LIKE :selDate
                                        AND sm.id = :modId
                                        GROUP BY jour
                                        ORDER BY jour ASC
                                                                                
                                        ")
                ->setParameters(array(
                    'selDate' => $dat,
                    'modId'   => $id
                ))
                ->getResult();

            /*$Energy = $manager->createQuery("SELECT SUBSTRING(d.dateTime, 1, 10) AS jour, SUM(d.kWh) AS kWh, SUM(d.kVarh) AS kVarh
                                        FROM App\Entity\DataMod d, App\Entity\SmartMod sm WHERE d.dateTime LIKE :selDate
                                        AND sm.id = :modId
                                        GROUP BY jour
                                        ORDER BY jour ASC
                                                                                
                                        ")
                ->setParameters(array(
                    'selDate' => $dateparam,
                    'modId'   => $id
                ))
                ->getResult();*/
            //dump($data);
            foreach ($data as $d) {
                $date[]        = $d['dat']->format('Y-m-d H:i:s');
                $VA[]          = $d['va'];
                $VB[]          = $d['vb'];
                $VC[]          = $d['vc'];
                $SA[]          = $d['sa'];
                $SB[]          = $d['sb'];
                $SC[]          = $d['sc'];
                $S3ph[]        = $d['s3ph'];
                $idc[]   = number_format((float) $d['IDC'], 2, '.', '');
                $idd[]   = number_format((float) $d['IDD'], 2, '.', '');
            }

            foreach ($idc as $i) {
                $idcRef[] = 20;
            }
            foreach ($idd as $i) {
                $iddRef[] = 20;
            }

            foreach ($fuelData as $d) {
                $liters[]      = $d['liters'];
                $workingtime[] = $d['workingtime'] / (1000);
            }

            foreach ($workingtime as $WT) {
                $workingTime[] = date("H:i:s", $WT); //DateTime::createFromFormat('H:i:s', $WT);
            }
        }

        /*foreach ($Energy as $d) {
                $dateE[] = $d['jour'];
                $kWh[]   = $d['kWh'];
                $kVarh[] = $d['kVarh'];
            }*/

        //dump($Energy);
        //die();

        return $this->json([
            'code'         => 200,
            'selDate'      => $dateparam,
            'date'         => $date,
            'V'            => [$VA, $VB, $VC],
            'S'            => [$SA, $SB, $SC],
            'S3ph'         => $S3ph,
            'dateE'        => $dateE,
            'kWh'          => $kWh,
            'kVarh'        => $kVarh,
            'CourbeCroisé' => [$workingtime, $liters],
            'IDC'          => [$idc, $idcRef],
            'IDD'          => [$idd, $iddRef]
        ], 200);
    }

    /**
     * Permet d'afficher la page des historiques de carburant du module d'id $id
     *
     * @Route("/smart/mod/{id<\d+>}/fuel/histographs", name="show_fuelMod_histographs")
     * 
     * @param SmartMod $smartMod
     *  @return Response
     */
    public function fuelHisto(SmartMod $smartMod, Request $request, EntityManagerInterface $manager): Response
    {
        /*if (!isset($_SESSION)) {
            session_start();
        }
        $timezone = $_SESSION['time'];
        dump($timezone);
        */
        //dump($smartMod);

        $approFuel = new ApproFuel();
        //Permet d'obtenir un constructeur de formulaire
        // Externaliser la création du formulaire avec la cmd php bin/console make:form

        //  instancier un form externe
        $form = $this->createForm(ApproType::class, $approFuel);
        $form->handleRequest($request);
        //dump($approFuel);

        if ($form->isSubmitted() && $form->isValid()) {

            $approFuel->setSmartMod($smartMod);
            $manager->persist($approFuel);


            //$manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>L'approvisionnement</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('show_fuelMod_histographs', [
                'id' => $smartMod->getId(),
            ]);
        }

        return $this->render('smart_mod/fuelModhistographs.html.twig', [
            'smartMod'           => $smartMod,
            'user'               => $smartMod->getSite()->getUser(),
            'form'               => $form->createView(),
            'critiqFuelStock'    => $smartMod->getCritiqFuelStock()
        ]);
    }

    /**
     * Permet d'actualiser les graphes des historiques d'appro et de stock
     *
     * @Route("/smart/mod/{id<\d+>}/fuel/updatehistographs", name="update_fuelMod_histographs")
     * 
     * @param [type] $id
     * @param SmartMod $smartMod
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return JsonResponse
     */
    public function updatehistographs($id, SmartMod $smartMod, EntityManagerInterface $manager, Request $request): JsonResponse
    {
        $appro     = [];
        $approDate = [];
        $stock     = [];
        $stockDate = [];

        $paramJSON = $this->getJSONRequest($request->getContent());
        //dump($paramJSON['selectedDate']);
        //dump($content);
        //die();
        $dateparam = $paramJSON['selectedDate'];
        //$dateparam = $request->get('selectedDate'); // Ex : %2020-03-20%
        //$dat = "2020-02"; //'%' . $dat . '%'
        //$dat = substr($dateparam, 0, 8); // Ex : %2020-03
        dump($dateparam);
        //die();
        //$dat = $dat . '%';


        $approData = $manager->createQuery("SELECT appro.addAt, appro.quantity 
                                        FROM App\Entity\ApproFuel appro
                                        JOIN appro.smartMod sm 
                                        WHERE appro.addAt LIKE :selDate
                                        AND sm.id = :smartModId
                                        ORDER BY appro.addAt ASC   
                                        ")
            ->setParameters(array(
                'selDate'      => $dateparam,
                'smartModId'   => $id
            ))
            ->getResult();
        //dump($approData);
        //die();
        foreach ($approData as $d) {
            $approDate[] = $d['addAt']->format('Y-m-d H:i:s');
            $appro[]     = number_format((float) $d['quantity'], 2, '.', '');
        }

        $stockData = $manager->createQuery("SELECT d.dateTime, d.stock 
                                        FROM App\Entity\DataMod d
                                        JOIN d.smartMod sm 
                                        WHERE d.dateTime LIKE :selDate
                                        AND sm.id = :smartModId
                                        ORDER BY d.dateTime ASC                 
                                        ")
            ->setParameters(array(
                'selDate'      => $dateparam,
                'smartModId'   => $id
            ))
            ->getResult();
        dump($approData);
        //die();
        foreach ($stockData as $d) {
            $stockDate[] = $d['dateTime']->format('Y-m-d H:i:s');
            $stock[]     = number_format((float) $d['stock'], 2, '.', '');
        }
        return $this->json([
            'code'                => 200,
            'selDate'             => $dateparam,
            'CourbeAppro'         => [
                'date'  => $approDate,
                'appro' => $appro
            ],
            'CourbeStock'         => [
                'date'  => $stockDate,
                'stock' => $stock
            ],

        ], 200);
    }
}
