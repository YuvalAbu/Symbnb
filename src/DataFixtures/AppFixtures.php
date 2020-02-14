<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        for ($i=1; $i <= 30 ; $i++) {             
            $annonce = new Annonce();

            $idImage = [10, 1000, 101, 1015, 1016, 1018, 1019, 1021, 1022, 1021, 1037, 1038, 1036, 1041, 1043];

            $randIdImage = $idImage[array_rand($idImage, 2)[0]];

            $title = $faker->sentence();
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $annonce->setTitle($title)
                ->setCoverImage('https://picsum.photos/id/'.$randIdImage.'/1000/350')
                ->setIntro($faker->paragraph(2))
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5));

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