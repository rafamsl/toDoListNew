<?php

namespace App\DataFixtures;

use App\Entity\ToDo;
use App\Factory\ToDoFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ToDoFactory::createMany(10);
        $manager->flush();
    }
}
