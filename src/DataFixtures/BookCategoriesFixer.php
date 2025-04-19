<?php

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoriesFixer extends Fixture
{

    public const CATEGORY_ANDROID = 'android';
    public const CATEGORY_DEVICES = 'devices';
    /**
     * Loads a set of predefined book categories into the database.
     *
     * This method creates several BookCategory entities with specified titles
     * and slugs, persists them using the provided ObjectManager, and then
     * flushes the changes to the database.
     *
     * @param ObjectManager $manager The ObjectManager instance used to persist entities.
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $categories = [
            self::CATEGORY_ANDROID => (new BookCategory())->setTitle('Android')->setSlug("Android"),
            self::CATEGORY_DEVICES => (new BookCategory())->setTitle('Devices')->setSlug("Devices")
        ];


        foreach ($categories as $category) {
            $manager->persist($category);
        }


        $manager->persist((new BookCategory())->setTitle('Алгоритмы')->setSlug("алгоритм"));
        $manager->flush();

        foreach ($categories as $code => $category) {
            $this->addReference($code, $category);
        }
    }
}
