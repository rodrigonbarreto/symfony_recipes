<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('sweet');
        $manager->persist($category);

        $category2 = new Category();
        $category2->setName('salty');

        $manager->persist($category2);
        $manager->flush();
    }
}
