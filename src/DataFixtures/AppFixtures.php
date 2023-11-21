<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $image1 = new Image();
        $image1->setUrl("http://image1.com");
        $manager->persist($image1);

        $image2 = new Image();
        $image2->setUrl("http://image2.com");
        $manager->persist($image2);

        $image3 = new Image();
        $image3->setUrl("http://image3.com");
        $manager->persist($image3);

        $manager->flush();
    }
}
