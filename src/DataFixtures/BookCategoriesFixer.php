<?php

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoriesFixer extends Fixture
{
    public const CATEGORY_ANDROID = 'category_android';
    public const CATEGORY_DEVICES = 'category_devices';

    public function load(ObjectManager $manager): void
    {
        $android = (new BookCategory())
            ->setTitle('Android')
            ->setSlug('android');
        $devices = (new BookCategory())
            ->setTitle('Devices')
            ->setSlug('devices');

        $manager->persist($android);
        $manager->persist($devices);

        // Пример третьей категории без ссылки
        $manager->persist(
            (new BookCategory())->setTitle('Алгоритмы')->setSlug('algorithms')
        );

        $manager->flush();

        // Добавляем ссылки для последующего использования
        $this->addReference(self::CATEGORY_ANDROID, $android);
        $this->addReference(self::CATEGORY_DEVICES, $devices);
    }
}
