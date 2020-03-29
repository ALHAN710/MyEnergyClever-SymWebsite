<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use Faker\Factory;
use App\Entity\User1;
use App\Service\DateTimeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, DateTimeGenerator $dateTimeGenerator)
    {
        $nbusers = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\User1 u')->getSingleScalarResult();
        //dump($nbusers);

        $nbsites = $manager->createQuery('SELECT COUNT(s) 
                                          FROM App\Entity\User1 u 
                                          JOIN u.sites as s')
            ->getSingleScalarResult();
        //dump($nbsites);

        $nbmod = $manager->createQuery('SELECT COUNT(m)
                                          FROM App\Entity\SmartMod m
                                        ')
            ->getSingleScalarResult();
        //dump($nbmod);
        $nbgrid = $manager->createQuery("SELECT COUNT(g)
                                          FROM App\Entity\SmartMod g WHERE g.modType = 'GRID'
                                        ")
            ->getSingleScalarResult();
        //dump($nbgrid);

        $nbfuel = $manager->createQuery("SELECT COUNT(f)
                                          FROM App\Entity\SmartMod f WHERE f.modType = 'FUEL'
                                        ")
            ->getSingleScalarResult();
        //dump($nbfuel);
        /*$data = $manager->createQuery("SELECT d.dateTime, d.smartMod
                                       FROM App\Entity\DataMod d WHERE d.dateTime <= '2020-02-01 01:59:59' 
                                       ORDER BY d.dateTime ASC
                                        
                                     ")
            ->getResult();*/

        /*$data = $manager->createQuery("SELECT d.dateTime as dat, d.va, d.vb, d.vc, d.sa, d.sb, d.sc, d.s3ph, d.kWh, d.kVarh
                                       FROM App\Entity\DataMod d, App\Entity\SmartMod sm WHERE d.dateTime <= '2020-02-01 01:30:59' 
                                       AND sm.id = 211
                                       ORDER BY dat ASC
                                     ")
            ->getResult();
        dump($data);
        die();*/
        /*$faker = Factory::create('fr_FR');
        //dump($faker->dateTime($max = 'now', $timezone = 'Africa/Lagos'));
        //dump($faker->dateTimeThisYear($max = 'now', $timezone = 'Africa/Lagos'));*/
        //$dateTwo = new DateTime('2014-04-07 17:30:15');
        //$dateOne = new DateTime('2013-04-07 16:45:15');
        /*$Year = 2020;
        $month = 2;
        $day = 1;
        $date = new DateTime($Year . '-' . $month . '-' . $day . ' 00:00:00');
        dump($date->format('Y-m-d H:i:s'));
        $date->add(new DateInterval('PT5M0S'));
        dump($date->format('Y-m-d H:i:s'));*/
        /*$i = 0;
        for ($a = 1; $a <= 1; $a++) {
            for ($j = 1; $j <= 10; $j++) {
                for ($h = 0; $h < 24; $h++) {
                    for ($m = 1; $m <= 4; $m++) {
                        $date->add(new DateInterval('PT15M0S'))
                            ->format('Y-m-d H:i:s');
                        $i++;
                    }
                }
                dump($date->format('Y-m-d H:i:s'));
            }
        }*/
        //$array = $dateTimeGenerator->getArrayDateTime();
        //dump($array[0]);
        //dump($i);
        //die();

        //'2020-12-31 23:59:59';'2020-01-01 00:00:00'
        //$date = $faker->unique()->dateTimeBetween($startDate = $dateOne, $endDate = $dateTwo, $timezone = 'Africa/Douala');
        //dump($dateTwo);
        //dump($date->format('Y-m-d H:i:s'));

        /*$Energy = $manager->createQuery("SELECT SUBSTRING(d.dateTime, 1, 10) as jour, SUM(d.kWh) as kWh, SUM(d.kVarh) as kVarh
                                       FROM App\Entity\DataMod d, App\Entity\SmartMod sm WHERE d.dateTime LIKE :selDate
                                       AND sm.id = :modId
                                       GROUP BY jour
                                       ORDER BY jour ASC
                                                                              
                                     ")
            ->setParameters(array(
                'selDate' => '%2020-02%',
                'modId'   => 336
            ))
            ->getResult();*/

        //dump($Energy);
        //die();

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => compact('nbusers', 'nbsites', 'nbmod', 'nbgrid', 'nbfuel'),
        ]);
    }
}
