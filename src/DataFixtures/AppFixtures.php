<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\DataMod;
use Faker\Factory;
//use Cocur\Slugify\Slugify;
use App\Entity\Role;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\User1;
use App\Entity\SmartMod;
use DateTime;
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
        //$slugify = new Slugify();

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User1();
        $adminUser->setEnterpriseName('Clever Electric Company Ltd')
            ->setEmail('alhadoumpascal@gmail.com')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->addUserRole($adminRole);
        $manager->persist($adminUser);

        //Nous gérons les utilisateurs
        $users = [];
        //$genres = ['male', 'female'];
        $modTypes = ['FUEL', 'GRID'];
        $instaTypes = ['MONOPHASE', 'TRiPHASE'];

        for ($i = 1; $i <= 5; $i++) {
            //$user = new User();
            $user = new User1();

            //$genre = $faker->randomElement($genres);

            //$picture = 'https://randomuser.me/api/portraits/';
            //$pictureId = $faker->numberBetween(1, 99) . '.jpg';

            //$picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            /*$user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>'. join('</p><p>',$faker->paragraphs(3)) . '</p>')
                 ->setHash($hash)
                 ->setPicture($picture);*/
            $user->setEnterpriseName($faker->unique()->company)
                ->setEmail($faker->unique()->companyEmail)
                ->setHash($hash);

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
        for ($i = 1; $i <= 5; $i++) {
            $site = new Site();

            $name = $faker->unique()->region;

            //  $slug = $slugify->slugify($title);


            $site->setName($name)
                ->setUser($user);

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $smartMod = new SmartMod();
                $user = $users[mt_rand(0, count($users) - 1)];
                $modType = $faker->randomElement($modTypes);
                $instaType = $faker->randomElement($instaTypes);
                $nameMod = $faker->unique()->streetName;

                $smartMod->setModuleId($faker->unique()->randomNumber($nbDigits = 8, $strict = false))
                    ->setInstallationType($instaType)
                    ->setSite($site)
                    ->setModType($modType)
                    ->setModName($nameMod);

                $manager->persist($smartMod);
                $smartMods[] = $smartMod;
            }

            $manager->persist($site);
        }

        //$date = new DateTime();
        // Génération de fausses données pour chaque module sur une année
        foreach ($smartMods as $smartMod) {
            for ($a = 1; $a <= 1; $a++) {
                for ($j = 1; $j <= 10; $j++) {
                    for ($h = 0; $h < 24; $h++) {
                        for ($m = 1; $m <= 12; $m++) {
                            $dataMod = new DataMod();
                            $date = new DateTime($faker->unique()
                                ->dateTimeBetween($startDate = '2020-02-01 00:00:00', $endDate = '2020-02-10 23:59:59', $timezone = 'Africa/Douala')
                                ->format('Y-m-d H:i:s'));
                            $va = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 240);
                            $vb = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 240);
                            $vc = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 240);

                            $kWh = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2);
                            $kvar = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2);

                            $sa = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2);
                            $sb = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2);
                            $sc = $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 2);
                            $s3ph = $sa + $sb + $sc;

                            $dataMod->setDateTime($date)
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

                            $manager->persist($dataMod);
                        }
                    }
                }
            }
        }

        $manager->flush();
    }
}
