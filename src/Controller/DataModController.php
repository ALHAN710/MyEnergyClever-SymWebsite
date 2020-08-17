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
                ->setVamin($paramJSON['Va'][0])
                ->setVamax($paramJSON['Va'][1])
                ->setVbmin($paramJSON['Vb'][0])
                ->setVbmax($paramJSON['Vb'][1])
                ->setVcmin($paramJSON['Vc'][0])
                ->setVcmax($paramJSON['Vc'][1])
                ->setVa($paramJSON['Veff'][0])
                ->setVb($paramJSON['Veff'][1])
                ->setVc($paramJSON['Veff'][2])
                ->setVab($paramJSON['Vppeff'][0])
                ->setVbc($paramJSON['Vppeff'][1])
                ->setVca($paramJSON['Vppeff'][2])
                ->setPamax($paramJSON['Pa'][0])
                ->setPamoy($paramJSON['Pa'][1])
                ->setPbmax($paramJSON['Pb'][0])
                ->setPbmoy($paramJSON['Pb'][1])
                ->setPcmax($paramJSON['Pc'][0])
                ->setPcmoy($paramJSON['Pc'][1])
                ->setPmax3ph($paramJSON['P3ph'][0])
                ->setPmoy3ph($paramJSON['P3ph'][1])
                ->setQamax($paramJSON['Qa'][0])
                ->setQamoy($paramJSON['Qa'][1])
                ->setQbmax($paramJSON['Qb'][0])
                ->setQbmoy($paramJSON['Qb'][1])
                ->setQcmax($paramJSON['Qc'][0])
                ->setQcmoy($paramJSON['Qc'][1])
                ->setQmax3ph($paramJSON['Q3ph'][0])
                ->setQmoy3ph($paramJSON['Q3ph'][1])
                ->setSamax($paramJSON['Sa'][0])
                ->setSa($paramJSON['Sa'][1])
                ->setSbmax($paramJSON['Sb'][0])
                ->setSb($paramJSON['Sb'][1])
                ->setScmax($paramJSON['Sc'][0])
                ->setSc($paramJSON['Sc'][1])
                ->setS3phmax($paramJSON['S3ph'][0])
                ->setS3ph($paramJSON['S3ph'][1])
                ->setCosamin($paramJSON['Cosa'][0])
                ->setCosamn($paramJSON['Cosa'][1])
                ->setCosbmin($paramJSON['Cosb'][0])
                ->setCosbmn($paramJSON['Cosb'][1])
                ->setCoscmin($paramJSON['Cosc'][0])
                ->setCoscmn($paramJSON['Cosc'][1])
                ->setCosmin3ph($paramJSON['Cos3ph'][0])
                ->setCosmn3ph($paramJSON['Cos3ph'][1])
                ->setFpmna($paramJSON['FP'][0])
                ->setFpmnb($paramJSON['FP'][1])
                ->setFpmnc($paramJSON['FP'][2])
                ->setFpmn3ph($paramJSON['FP'][3])
                ->setKwha($paramJSON['EA'][0])
                ->setKwhb($paramJSON['EA'][1])
                ->setKwhc($paramJSON['EA'][2])
                ->setKWh($paramJSON['EA'][3])
                ->setEra($paramJSON['ER'][0])
                ->setErb($paramJSON['ER'][1])
                ->setErc($paramJSON['ER'][2])
                ->setKVarh($paramJSON['ER'][3])
                ->setThdia($paramJSON['THDi'][0])
                ->setThdib($paramJSON['THDi'][1])
                ->setThdic($paramJSON['THDi'][2])
                ->setThdi3ph($paramJSON['THDi'][3])
                ->setDmoya($paramJSON['Dmoy'][0])
                ->setDmoyb($paramJSON['Dmoy'][1])
                ->setDmoyc($paramJSON['Dmoy'][2])
                ->setDmoy3ph($paramJSON['Dmoy'][3])
                ->setIdmoy($paramJSON['SymI'][0])
                ->setIomoy($paramJSON['SymI'][1])
                ->setVdmoy($paramJSON['SymV'][0])
                ->setVomoy($paramJSON['SymV'][1])
                ->setFdvmoy($paramJSON['Fdv'])
                ->setFdimoy($paramJSON['Fdi'])
                ->setIddmoy($paramJSON['Idd'])
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
