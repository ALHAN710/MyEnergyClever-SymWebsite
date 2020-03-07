<?php

namespace App\Controller;

use DateTime;
use Faker\Factory;
use App\Entity\User1;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager)
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
        /*$data = $manager->createQuery("SELECT d.dateTime, sm.id
                                       FROM App\Entity\DataMod d WHERE d.dateTime <= '2020-02-01 01:59:59' 
                                       AND (SELECT sm.id FROM App\Entity\SmartMod sm WHERE sm.id = 211) = 211
                                       JOIN d.smartMod sm
                                     ")
            ->getResult();
        dump($data);
        die();*/
        /*$faker = Factory::create('fr_FR');
        //dump($faker->dateTime($max = 'now', $timezone = 'Africa/Lagos'));
        //dump($faker->dateTimeThisYear($max = 'now', $timezone = 'Africa/Lagos'));
        $dateTwo = new DateTime('2014-04-07 17:30:15');
        $dateOne = new DateTime('2013-04-07 16:45:15');
        //'2020-12-31 23:59:59';'2020-01-01 00:00:00'
        $date = $faker->unique()->dateTimeBetween($startDate = $dateOne, $endDate = $dateTwo, $timezone = 'Africa/Douala');
        dump($dateTwo);
        dump($date->format('Y-m-d H:i:s'));*/

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => compact('nbusers', 'nbsites', 'nbmod', 'nbgrid', 'nbfuel'),
        ]);
    }
}
