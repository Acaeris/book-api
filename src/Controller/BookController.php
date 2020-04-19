<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookController extends AbstractController
{
    /**
     * @Route("/book/author/{author}", name="app_book_by_author")
     */
    public function byAuthor(string $author, BookRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $books = $repository->fetchByAuthor($author);

        return new Response(
            $this->renderView('book/list.html.twig', ['books' => $books]),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/book/category/{category}", name="app_book_by_category")
     */
    public function byCategory(string $category, BookRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $books = $repository->fetchByCategory($category);

        return new Response(
            $this->renderView('book/list.html.twig', ['books' => $books]),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/book/author/{author}/category/{category}", name="app_book_by_author_and_category")
     * @Route("/book/category/{category}/author/{author}", name="app_book_by_category_and_author")
     */
    public function byAuthorAndCategory(string $author, string $category, BookRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $books = $repository->fetchByAuthorAndCategory($author, $category);

        return new Response(
            $this->renderView('book/list.html.twig', ['books' => $books]),
            Response::HTTP_OK,
            [
                'Content-type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/book/create", name="app_book_create")
     */
    public function create(Request $request, BookRepository $repository, ValidatorInterface $validator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $book = $repository->create(
            $request->request->get('isbn'),
            $request->request->get('title'),
            $request->request->get('author'),
            $request->request->get('category'),
            $request->requset->get('price')
        );

        $errors = $validator->validate($book);

        if (count($errors) === 0) {
            $repository->store($book);

            return new Response(
                $this->renderView('book/list.html.twig', ['books' => [$book]]),
                Response::HTTP_CREATED,
                [
                    'Content-type' => 'application/json'
                ]
            );
        }

        return new Response(
            $this->renderView('book/create-error.html.twig', ['errors' => (string) $errors]),
            Response::HTTP_BAD_REQUEST,
            [
                'Content-type' => 'application/json'
            ]
        );
    }
}