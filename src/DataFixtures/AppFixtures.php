<?php

namespace App\DataFixtures;

use DateTime;
use DateInterval;
use App\Entity\Ad;
use App\Entity\ApproFuel;
//use Cocur\Slugify\Slugify;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\User1;
use App\Entity\DataMod;
use App\Entity\SmartMod;
use App\Service\DateTimeGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    //Constructeur pour utiliser la fonction d'encodage de mot passe
    //encodePassword($entity, $password)
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $dateTimeGenerator = new DateTimeGenerator();
        //$slugify = new Slugify();
        $nb = 0;
        $genders = ['male', 'female'];
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User1();
        $date = new DateTime(date('Y-m-d H:i:s'));
        $adminUser->setEnterpriseName('Clever Electric Company Ltd')
            ->setEmail('alhadoumpascal@gmail.com')
            ->setFirstName('Pascal')
            ->setLastName('ALHADOUM')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->setCreatedAt($date)
            ->addUserRole($adminRole)
            ->setVerified(true)
            ->setPhoneNumber($faker->phoneNumber)
            ->setCountryCode('+237');
        $manager->persist($adminUser);

        $date = new DateTime(date('Y-m-d H:i:s'));
        $adminUser2 = new User1();
        $adminUser2->setEnterpriseName('Clever Electric Company Ltd')
            ->setFirstName('Cabrel')
            ->setLastName('MBAKAM')
            ->setEmail('cabrelmbakam@gmail.com')
            ->setHash($this->encoder->encodePassword($adminUser2, 'password'))
            ->setCountryCode('+237')
            ->setPhoneNumber('690304593')
            ->setCreatedAt($date)
            ->setVerified(true)
            ->addUserRole($adminRole);

        $manager->persist($adminUser2);

        //Nous gérons les utilisateurs
        $users = [];
        //$genres = ['male', 'female'];
        $modTypes = ['FUEL', 'GRID'];
        $subscriptionTypes = ['MT', 'Tertiary', 'Residential'];
        $instaTypes = ['MONOPHASE', 'TRiPHASE'];

        $userSATC = new User1();
        $date = new DateTime(date('Y-m-d H:i:s'));
        $userSATC->setEnterpriseName('SATC SARL')
            ->setFirstName('Martin')
            ->setLastName('BOYOMO')
            ->setEmail('martinboyomo@satc.com')
            ->setHash($this->encoder->encodePassword($userSATC, 'password'))
            ->setCreatedAt($date)
            ->setVerified(true)
            ->setPhoneNumber($faker->phoneNumber)
            ->setCountryCode('+237');
        $manager->persist($userSATC);

        for ($i = 1; $i <= 3; $i++) {
            //$user = new User();
            $user = new User1();

            //$genre = $faker->randomElement($genres);

            //$picture = 'https://randomuser.me/api/portraits/';
            //$pictureId = $faker->numberBetween(1, 99) . '.jpg';

            //$picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');
            $date = new DateTime(date('Y-m-d H:i:s'));

            /*$user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>'. join('</p><p>',$faker->paragraphs(3)) . '</p>')
                 ->setHash($hash)
                 ->setPicture($picture);*/
            $user->setEnterpriseName($faker->unique()->company)
                ->setEmail($faker->unique()->companyEmail)
                ->setFirstName($faker->unique()->firstName($faker->randomElement($genders)))
                ->setLastName($faker->unique()->lastName)
                ->setCreatedAt($date)
                ->setHash($hash)
                ->setVerified(true)
                ->setPhoneNumber($faker->phoneNumber)
                ->setCountryCode('+237');

            $manager->persist($user);
            $users[] = $user;
        }

        //Nous gérons les annonces
        /*for($i = 1; $i <= 30; $i++){
            $ad = new Ad();

            $title = $faker->sentence();
          //  $slug = $slugify->slugify($title);
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
           // ->setSlug($slug)
            ->setCoverImage($coverImage)
            ->setIntroduction($introduction)
            ->setContent($content)
            ->setPrice(mt_rand(40, 200))
            ->setRooms(mt_rand(1, 5))
            ->setAuthor($user);
            
            for($j = 1; $j <= mt_rand(2, 5); $j++){
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                      ->setCaption($faker->sentence())
                      ->setAd($ad);

                $manager->persist($image);
            }

            $manager->persist($ad);
        }*/

        $smartMods = [];
        //Nous gérons les Sites

        //Site de Douala du client SATC
        $siteDouala = new Site();

        $siteDouala->setName('Usine de Douala')
            ->setSubscription('MT')
            ->setPsous(245)
            ->setUser($userSATC);
        $tabgridMod = ['Livraison ENEO', 'Départ Atelier de pointerie', 'Départ Atelier de chaudronnerie', 'Départ Atelier de laminage', 'Départ Bloc administratif'];
        $tabfuelMod = ['Livraison Groupe Electrogène'];
        //Site Douala GRID MODULES
        foreach ($tabgridMod as $gridModName) {

            $smartMod = new SmartMod();
            $modType = 'GRID';
            $instaType = $faker->randomElement($instaTypes);
            $nameMod = $gridModName;

            $smartMod->setModuleId($faker->unique()->randomNumber($nbDigits = 8, $strict = false))
                ->setInstallationType($instaType)
                ->setSite($siteDouala)
                ->setModType($modType)
                ->setModName($nameMod);

            $manager->persist($smartMod);
            $smartMods[] = $smartMod;
        }

        //Site Douala FUEL MODULES
        foreach ($tabfuelMod as $fuelModName) {

            $smartMod = new SmartMod();
            $modType = 'FUEL';
            $instaType = $faker->randomElement($instaTypes);
            $nameMod = $fuelModName;

            $smartMod->setModuleId($faker->unique()->randomNumber($nbDigits = 8, $strict = false))
                ->setInstallationType($instaType)
                ->setSite($siteDouala)
                ->setModType($modType)
                ->setModName($nameMod);


            $critiqFuelStock = $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120);

            $smartMod->setCritiqFuelStock($critiqFuelStock);


            $manager->persist($smartMod);
            $smartMods[] = $smartMod;
        }
        $manager->persist($siteDouala);

        //Site de Garoua du client SATC
        $siteGaroua = new Site();

        $siteGaroua->setName('Agence commercial Garoua')
            ->setSubscription('Tertiary')
            ->setPsous(66)
            ->setUser($userSATC);
        $tabgridMod = ['Livraison ENEO'];
        $tabfuelMod = ['Livraison Groupe Electrogène'];
        //Site Garoua GRID MODULES
        foreach ($tabgridMod as $gridModName) {

            $smartMod = new SmartMod();
            $modType = 'GRID';
            $instaType = $faker->randomElement($instaTypes);
            $nameMod = $gridModName;

            $smartMod->setModuleId($faker->unique()->randomNumber($nbDigits = 8, $strict = false))
                ->setInstallationType($instaType)
                ->setSite($siteGaroua)
                ->setModType($modType)
                ->setModName($nameMod);

            $manager->persist($smartMod);
            $smartMods[] = $smartMod;
        }

        //Site Garoua FUEL MODULES
        foreach ($tabfuelMod as $fuelModName) {

            $smartMod = new SmartMod();
            $modType = 'FUEL';
            $instaType = $faker->randomElement($instaTypes);
            $nameMod = $fuelModName;

            $smartMod->setModuleId($faker->unique()->randomNumber($nbDigits = 8, $strict = false))
                ->setInstallationType($instaType)
                ->setSite($siteGaroua)
                ->setModType($modType)
                ->setModName($nameMod);


            $critiqFuelStock = $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120);

            $smartMod->setCritiqFuelStock($critiqFuelStock);


            $manager->persist($smartMod);
            $smartMods[] = $smartMod;
        }
        $manager->persist($siteGaroua);


        for ($i = 1; $i <= 10; $i++) {
            $site = new Site();

            $name = $faker->unique()->region;
            $user = $users[mt_rand(0, count($users) - 1)];

            //  $slug = $slugify->slugify($title);
            $site->setName($name)
                ->setSubscription($faker->randomElement($subscriptionTypes))
                ->setPsous($faker->randomFloat($nbMaxDecimals = 1, $min = 2.2, $max = 240))
                ->setUser($user);

            for ($j = 1; $j <= mt_rand(2, 4); $j++) {
                $smartMod = new SmartMod();
                $modType = $faker->randomElement($modTypes);
                $instaType = $faker->randomElement($instaTypes);
                $nameMod = $faker->unique()->streetName;

                $smartMod->setModuleId($faker->unique()->randomNumber($nbDigits = 8, $strict = false))
                    ->setInstallationType($instaType)
                    ->setSite($site)
                    ->setModType($modType)
                    ->setModName($nameMod);

                if ($modType == 'FUEL') {
                    $critiqFuelStock = $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 120);

                    $smartMod->setCritiqFuelStock($critiqFuelStock);
                }

                $manager->persist($smartMod);
                $smartMods[] = $smartMod;
            }

            $manager->persist($site);
        }

        //$date = new DateTime();
        // Génération de fausses données pour chaque module sur une année
        $Year = 2020;
        $month = 4;
        $day = 1;
        $nbDay = 10;
        $nbYear = 1;

        $date_array = [];
        /*$date = new DateTime($Year . '-' . $month . '-' . $day . ' 00:00:00');
        $date = new DateTime('2020-02-01 00:00:00');
        $date->format('Y-m-d H:i:s');
        $dat = new DateTime();
        $date_array = [];
        */

        //$date_array = $dateTimeGenerator->getArrayDateTime();
        for ($i = 0; $i < 2; $i++) {
            $months = $month + $i;
            for ($j = 1; $j <= $nbDay; $j++) {
                for ($h = 0; $h < 24; $h++) {
                    for ($m = 0; $m < 60; $m += 15) { //'P0DT0H15M0S'
                        $date = new DateTime($Year . '-' . $months . '-' . $j . ' ' . $h . ':' . $m . ':00');
                        $date->format('Y-m-d H:i:s');

                        $date_array[] = $date;
                    }
                }
            }
        }
        foreach ($smartMods as $smartMod) {
            //for ($a = 1; $a <= 1; $a++) {
            //for ($j = 1; $j <= 10; $j++) {
            //for ($h = 0; $h < 24; $h++) {
            //for ($m = 1; $m <= 2; $m++) {
            $approTab = [];
            $prevStock = $faker->randomFloat($nbMaxDecimals = 2, $min = 10, $max = 40);
            foreach ($date_array as $dat) {

                $dataMod = new DataMod();
                /*$date = new DateTime($faker->unique()
                                ->dateTimeBetween($startDate = '2020-02-01 00:00:00', $endDate = '2020-02-11 23:59:59', $timezone = 'Africa/Douala')
                                ->format('Y-m-d H:i:s'));
                $date = new DateTime(
                    $faker->dateTimeInInterval($startDate = '-1 month', $interval = '+ 1 days', $timezone = 'Africa/Douala')
                        ->format('Y-m-d H:i:s')
                );*/
                /*$date = $dat;
                            $date->add(new DateInterval('PT15M0S'))
                                ->format('Y-m-d H:i:s');*/
                //$dat = new DateTime($faker->unique()->randomElement($date_array)->format('Y-m-d H:i:s'));
                //$dat = new DateTime($faker->randomElement($date_array)->format('Y-m-d H:i:s'));
                $va = $faker->randomFloat($nbMaxDecimals = 2, $min = 190, $max = 240);
                $vb = $faker->randomFloat($nbMaxDecimals = 2, $min = 200, $max = 240);
                $vc = $faker->randomFloat($nbMaxDecimals = 2, $min = 180, $max = 240);

                $kWh = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2);
                $kvar = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2);

                $sa = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2);
                $sb = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2);
                $sc = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2);
                $s3ph = $sa + $sb + $sc;

                $dataMod->setDateTime($dat)
                    ->setVa($va)
                    ->setVb($vb)
                    ->setVc($vc)
                    ->setSa($sa)
                    ->setSb($sb)
                    ->setSc($sc)
                    ->setS3ph($s3ph)
                    ->setKWh($kWh)
                    ->setKVarh($kvar)
                    ->setSmartMod($smartMod);

                if ($smartMod->getModType() == 'FUEL') {
                    $liter = $faker->randomFloat($nbMaxDecimals = 50, $min = 0, $max = 100);
                    if ($liter > 0) $workTime = $faker->numberBetween($min = 60000, $max = 3600000);
                    else $workTime = 0;
                    $smax = $faker->randomFloat($nbMaxDecimals = 2, $min = $s3ph, $max = $s3ph + 10);

                    for ($Nappro = 0; $Nappro < mt_rand(0, 1); $Nappro++) {
                        $addAt = new DateTime($dat->format('Y') . '-' . $dat->format('m') . '-' . $dat->format('d') . ' ' . $dat->format('H') . ':' . ((int) ($dat->format('i')) + $Nappro) . ':00');
                        $addAt->format('Y-m-d H:i:s');
                        $quantity = $faker->randomFloat($nbMaxDecimals = 2, $min = 20, $max = 30);
                        $approTab[] = $quantity;

                        $appro = new ApproFuel();

                        $appro->setAddAt($addAt)
                            ->setQuantity($quantity)
                            ->setSmartMod($smartMod);

                        $manager->persist($appro);
                    }

                    $sumAppro = array_sum($approTab);
                    $conso = $liter;
                    $stock = $prevStock + $sumAppro - $conso;
                    $prevStock = $stock;

                    $dataMod->setLiters($liter)
                        ->setWorkingtime($workTime)
                        ->setS3phmax($smax)
                        ->setStock($stock);
                }

                $manager->persist($dataMod);
            }
            //    }
            //  }
            //}
            //}
        }

        $manager->flush();
    }
}
