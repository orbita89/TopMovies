<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    public function __construct(private BookRepository $bookRepository, private EntityManagerInterface $entityManager)
    {
    }

    /*
         *
         * Тестовое добавление
         *
        #[Route('/newBooks', name: 'books')]
        public function addBook(): Response
        {
            $book = new Book();
            $book->setTitle('Love and death');

            $this->entityManager->persist($book);
            $this->entityManager->flush();

            return new Response();
        }*/

    #[Route('/', name: 'help')]
    public function root(): Response
    {
        $book = $this->bookRepository->findAll();

        return $this->json($book);
    }
}
