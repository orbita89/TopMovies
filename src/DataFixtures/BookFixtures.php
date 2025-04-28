<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $android = $this->getReference(BookCategoriesFixer::CATEGORY_ANDROID, BookCategory::class);

        $devices = $this->getReference(BookCategoriesFixer::CATEGORY_DEVICES, BookCategory::class);

        $book = (new Book())
            ->setTitle('Java')
            ->setSlug('java')
            ->setMeap(false)
            ->setIsbn('1234567890')
            ->setDescription('Описание')
            ->setAuthors(['Иван Иванов'])
            ->setCreatedAt(new \DateTimeImmutable('2023-01-01'))
            ->setCategories(new ArrayCollection([$android, $devices]))
            ->setImage(
                'https://images.unsplash.com/photo-1518791841217-8f162f1e6016?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=800&q=60'
            );

        $manager->persist($book);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BookCategoriesFixer::class
        ];
    }
}
