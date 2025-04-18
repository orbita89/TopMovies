<?php

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoriesFixer extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $manager->persist((new BookCategory())->setTitle('Война и мир1')->setSlug("война_ми1"));
        $manager->persist((new BookCategory())->setTitle('Война и мир2')->setSlug("война_ми2"));
        $manager->persist((new BookCategory())->setTitle('Война и мир3')->setSlug("война_ми3"));
        $manager->persist((new BookCategory())->setTitle('Алгоритмы')->setSlug("алгоритм"));

        $manager->flush();
    }
}
