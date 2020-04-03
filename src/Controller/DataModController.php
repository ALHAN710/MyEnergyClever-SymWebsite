<?php

namespace App\Controller;

use DateTime;
//use DateInterval;
use App\Entity\DataMod;
use App\Entity\SmartMod;
use App\Repository\SmartModRepository;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DataModController extends ApplicationController
{

    /**
     * Permet de surcharger les données des modules dans la BDD
     *
     * @Route("/data/mod/{modId<[a-zA-Z0-9]+>}/add", name="dataMod_add") 
     * 
     * @param SmartMod $smartMod
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return void
     */
    public function dataMod_add($modId, SmartModRepository $smartModRepo, EntityManagerInterface $manager, Request $request)
    {
        //Récupération et vérification des paramètres au format JSON contenu dans la requête
        $paramJSON = $this->getJSONRequest($request->getContent());
        //dump($paramJSON);
        //dump($content);
        //die();

        $dataMod = new DataMod();

        //Recherche du module dans la BDD
        $smartMod = $smartModRepo->findOneBy(['moduleId' => $modId]);


        if ($smartMod != null) { // Test si le module existe dans notre BDD
            //data:{"date": "2020-03-20 12:15:00", "sa": 1.2, "sb": 0.7, "sc": 0.85, "va": 225, "vb": 230, "vc": 231, "s3ph": 2.75, "kWh": 1.02, "kvar": 0.4}
            //dump($smartMod);//Affiche le module
            //die();

            //$date = new DateTime($paramJSON['date']);

            //Récupération de la date dans la requête et transformation en object de type Date au format date SQL
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $paramJSON['date']);
            //dump($date);
            //die();

            //Paramétrage des champs de la nouvelle dataMod aux valeurs contenues dans la requête du module
            $dataMod->setDateTime($date)
                ->setVa($paramJSON['va'])
                ->setVb($paramJSON['vb'])
                ->setVc($paramJSON['vc'])
                ->setSa($paramJSON['sa'])
                ->setSb($paramJSON['sb'])
                ->setSc($paramJSON['sc'])
                ->setS3ph($paramJSON['s3ph'])
                ->setKWh($paramJSON['kWh'])
                ->setKVarh($paramJSON['kvar'])
                ->setSmartMod($smartMod);

            if ($smartMod->getModType() == 'FUEL') {
                $dataMod->setS3phmax($paramJSON['smax'])
                    ->setLiters($paramJSON['liters'])
                    ->setWorkingtime($paramJSON['workingtime']);

                //++++++++++ Calcul du stock actuel ++++++++++

                //Récupération de la date directement inférieure à la nouvelle date(prevDate)
                $prevDate = $manager->createQuery("SELECT MAX(d.dateTime) AS prevDate
                                      FROM App\Entity\DataMod d
                                      JOIN d.smartMod sm
                                      WHERE d.dateTime < :Dat 
                                      AND sm.id = :modId
                                    ")
                    ->setParameters(array(
                        'Dat' => $date,
                        'modId' => $smartMod->getId()
                    ))
                    ->getResult();
                //dump($prevDate);
                //die();

                //Récupération du stock à la date prevDate
                $prevStock = $manager->createQuery("SELECT d.stock 
                                        FROM App\Entity\DataMod d
                                        JOIN d.smartMod sm 
                                        WHERE d.dateTime = :prevDate
                                        AND sm.id = :modId
                                                                              
                                        ")
                    ->setParameters(array(
                        'prevDate' => $prevDate[0]['prevDate'],
                        'modId'   => $smartMod->getId()
                    ))
                    ->getResult();
                //dump($prevStock);

                //Récupération des appro compris prevDate et actualDate
                $sumAppro = $manager->createQuery("SELECT SUM(d.quantity) AS sumAppro
                                        FROM App\Entity\ApproFuel d
                                        JOIN d.smartMod sm 
                                        WHERE d.addAt <= :Dat AND d.addAt >= :initDate
                                        AND sm.id = :modId
                                                                              
                                        ")
                    ->setParameters(array(
                        'Dat'     => $date,
                        'initDate' => $prevDate[0]['prevDate'],
                        'modId'    => $smartMod->getId()
                    ))
                    ->getResult();
                //dump($sumAppro);
                //die();

                //Récupération de la conso
                $conso = $paramJSON['liters'];
                //dump($conso);

                //Calcul du stock actuel
                $stock = $prevStock[0]['stock'] + $sumAppro[0]['sumAppro'] - $conso;
                //dump($stock);
                //die();

                $dataMod->setStock($stock);
            }

            //dump($dataMod);
            //die();
            //Insertion de la nouvelle dataMod dans la BDD
            $manager->persist($dataMod);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'received' => $paramJSON

            ], 200);
        }
        return $this->json([
            'code' => 403,
            'message' => "SmartMod don't exist",
            'received' => $paramJSON

        ], 403);
    }
}
