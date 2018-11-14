<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BooksRepository;
use App\Entity\Books;

class BookController extends AbstractController
{
    /**
     * @var BooksRepository
     */
    private $repository;

    /**
     * 
     */
    public function __construct(BooksRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route( "/book", name="book.index" )
     * @return Response
     */
    public function index():Response
    {
        $books = $this->repository->findAllOrder();
        
        return $this->render( 'book/index.html.twig', [
            'current_menu' => 'book'
        ]);
    }

    /**
     * @Route( "/book/{id}", name="book.show" )
     * @return Response
     */
    public function show($id):Response
    {
        $book = $this->repository->find( $id );
        
        return $this->render( 'book/show.html.twig', [
            'book' => $book,
            'current_menu' => 'book'
        ]);
    }
}