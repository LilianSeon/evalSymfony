<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $list = ['peinture', 'dessin', 'sculpture'];

        for ($i = 0; $i < count($list); $i++){
            $category = new Category();
            $category->setName($list[$i]);
            $this->addReference("categorie$i", $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
