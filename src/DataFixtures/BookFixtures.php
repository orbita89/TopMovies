<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookToBookFormat;
use App\Entity\BookFormat;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Faker\Factory;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $android = $this->getReference(BookCategoriesFixer::CATEGORY_ANDROID, BookCategory::class);
        $devices = $this->getReference(BookCategoriesFixer::CATEGORY_DEVICES, BookCategory::class);

        $ebook = $this->getReference(BookFormatFixtures::FORMAT_EBOOK, BookFormat::class);
        $print = $this->getReference(BookFormatFixtures::FORMAT_PRINT, BookFormat::class);

        for ($i = 1; $i <= 10; $i++) {
            $book = (new Book())
                ->setTitle($faker->sentence(3))
                ->setSlug($faker->slug)
                ->setMeap($faker->boolean)
                ->setIsbn($faker->numberBetween(1, 9999999999))
                ->setCreatedAt(new \DateTimeImmutable('2023-01-01'))
                ->setDescription($faker->paragraph)
                ->setAuthors([$faker->name])
                ->setCategories(new ArrayCollection([$android, $devices]))
                ->setImage($faker->imageUrl(640, 480, 'books', true));

            $manager->persist($book);


            for ($j = 0; $j < random_int(2, 5); $j++) {
                $review = (new Review())
                    ->setBook($book)
                    ->setAuthor($faker->name())
                    ->setContent($faker->sentence())
                    ->setRating($faker->numberBetween(1, 5));
                $manager->persist($review);
            }

            // Привязка форматов к книге
            $bookFormat1 = (new BookToBookFormat())
                ->setBook($book)
                ->setBookFormat($ebook)
                ->setPrice($faker->randomFloat(2, 5, 50));

            $bookFormat2 = (new BookToBookFormat())
                ->setBook($book)
                ->setBookFormat($print)
                ->setPrice($faker->randomFloat(2, 10, 100));

            $manager->persist($bookFormat1);
            $manager->persist($bookFormat2);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BookCategoriesFixer::class,
            BookFormatFixtures::class,
        ];
    }
}

//Chatgpt: Создай мне fixtures по этим entity добавь каждое поле без исключений используй faker
