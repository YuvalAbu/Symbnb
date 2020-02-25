<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Service\PaginationService;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page}", name="admin_comments_index", requirements={"page": "\d+"})
     */
    public function index(CommentRepository $repo, $page = 1, PaginationService $pagination)
    {
        $pagination->setEntityClass(Comment::class)
                    ->setCurrentPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Display edit comment
     *
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     * 
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $from = $this->createForm(AdminCommentType::class, $comment);

        $from->handleRequest($request);

        if($from->isSubmitted() && $from->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire <strong>n°{$comment->getId()}</strong> a bien été modifié !"
            );

            return $this->redirectToRoute('admin_comments_index');

        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $from->createView()
        ]);
    }

    /**
     * Allow to delete an comment
     *
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de <strong>{$comment->getAuthor()->getFullName()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_comments_index');
    }
} 
