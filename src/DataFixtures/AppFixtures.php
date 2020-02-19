<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use Faker\Factory;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder= $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Yuval')
                  ->setLastName('Abudarham')
                  ->setEmail('yuval@abu.fr')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('https://randomuser.me/api/portraits/lego/0.jpg')
                  ->setIntro($faker->sentence())
                  ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);


        // Nous gérons les utilisateurs
        $users = [];

        for ($i=0; $i <=10 ; $i++) { 
            $user = new User();

            $picture = 'https://randomuser.me/api/portraits/lego/';
            $pictureId = $faker->numberBetween(0, 9) . '.jpg';
            $picture .= $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName())
                 ->setLastName($faker->lastName())
                 ->setEmail($faker->email)
                 ->setIntro($faker->sentence())
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->setHash($hash)
                 ->setPicture($picture);
            
            $manager->persist($user);
            $users[] = $user;
        }

        // Nous gérons les annonces
        for ($i=1; $i <= 30 ; $i++) {             
            $annonce = new Annonce();

            $idImage = [10, 1000, 101, 1015, 1016, 1018, 1019, 1021, 1022, 1021, 1037, 1038, 1036, 1041, 1043];

            $randIdImage = $idImage[array_rand($idImage, 2)[0]];

            $title = $faker->sentence();
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) -1)];

            $annonce->setTitle($title)
                ->setCoverImage('https://picsum.photos/id/'.$randIdImage.'/1000/350')
                ->setIntro($faker->paragraph(2))
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);

            for ($j=1; $j <= mt_rand(2,5) ; $j++) { 
                $image = new Image();

                $randIdImage2 = $idImage[array_rand($idImage, 4)[0]];

                $image->setUrl('https://picsum.photos/id/'.$randIdImage2.'/1000/600')
                      ->setCaption($faker->sentence())
                      ->setAnnonce($annonce);

                $manager->persist($image);
            }

            $manager->persist($annonce);
        }

        $manager->flush();
    }
}