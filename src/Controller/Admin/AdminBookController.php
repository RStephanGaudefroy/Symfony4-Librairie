<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\BooksRepository;
use App\Entity\Books;
use App\Form\BookType;

class AdminBookController extends AbstractController
{
    /**
     * @var BookRepository
     */
    private $repository;
    
     /**
     * @var ObjectManager
     */
    private $em;
    
    /**
     * 
     */
    public function __construct( BooksRepository $repository,ObjectManager $em )
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route( "/admin", name="admin.book.index" )
     * @return Response
     */
    public function index()
    {
        $books = $this->repository->findAllOrder();

        return $this->render( 'admin/book/index.html.twig', compact( 'books' ) );
    }

    /**
     * @Route( "/admin/new", name="admin.book.new" )
     * @return Response
     */
    public function new(Request $request)
    {
        
        $book = new Books();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        
        if ( $form->isSubmitted() && $form->isValid() )
        {
            $this->em->persist( $book );
            $this->em->flush();
            $this->addflash( 'success', 'Enregistrement effectué avec succés' );
            return $this->redirectToRoute( 'admin.book.index' );
        }

         return $this->render( 'admin/book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ] );        
    }

    /**
     * @Route( "/admin/{id}", name="admin.book.edit" )
     * @return Response
     */
    public function edit(Books $book, Request $request)
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() )
        {
            $this->em->flush();
            $this->addflash( 'success', 'Modification effectuée avec succés' );
            return $this->redirectToRoute( 'admin.book.index' );
        }

        return $this->render( 'admin/book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ] );
    }

    /**
     * @Route( "/admin/delete/{id}", name="admin.book.delete" )
     * @return Response
     */
    public function delete(Books $book, Request $request)
    {
        if ( $this->isCsrfTokenValid( 'delete' . $book->getId(), $request->get( '_token' ) ) )
        {
            $this->em->remove( $book );
            $this->em->flush();
            $this->addflash( 'success', 'Suppression effectuée avec succés' );
        }
        
        return $this->redirectToRoute( 'admin.book.index' );
    }

}