<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Route;
use Symfony\Flex\Response;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/list", name="app_category_list")
     */
    public function list(BookRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $categories = $repository->fetchCategories();

        return $this->render('category/list.html.twig', ['categories' => $categories]);
    }
}