<?php

namespace App\DataFixtures;

use App\Entity\BookFormat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFormatFixtures extends Fixture
{
    public const FORMAT_EBOOK = 'book_format_ebook';
    public const FORMAT_PRINT = 'book_format_print';

    public function load(ObjectManager $manager): void
    {
        $ebook = (new BookFormat())
            ->setTitle('eBook')
            ->setDescription('Electronic version of the book')
            ->setComment('Instant access');

        $print = (new BookFormat())
            ->setTitle('Print')
            ->setDescription('Physical copy')
            ->setComment('Ships in 3-5 days');

        $manager->persist($ebook);
        $manager->persist($print);

        // Сохраняем ссылки для дальнейшего использования
        $this->addReference(self::FORMAT_EBOOK, $ebook);
        $this->addReference(self::FORMAT_PRINT, $print);

        $manager->flush();
    }
}
