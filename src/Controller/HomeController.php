<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Repository\BooksRepository;
use App\Entity\Books;

class HomeController extends AbstractController
{    
    /**
     * Display home page with latest books
     * @param BooksRepository $repository
     * @return Response
     */
    public function index(BooksRepository $repository):Response
    {
        $books = $repository->findLatest();
        
        return $this->render( 'pages/home.html.twig', [
            'books' => $books
        ] );
    }
}