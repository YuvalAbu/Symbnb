<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAnnonceController extends AbstractController
{
    /**
     * @Route("/admin/annonces", name="admin_annonces_index")
     */
    public function index(AnnonceRepository $repo)
    {
        return $this->render('admin/annonce/index.html.twig', [
            'annonces' => $repo->findAll()
        ]);
    }

    /**
     * Display edit form
     *
     * @Route("/admin/annonces/{id}/edit", name="admin_annonces_edit")
     * 
     * @param Annonce $annonce
     * @return Response
     */
    public function edit(Annonce $annonce, Request $request, EntityManagerInterface $manager)
    {
        $from = $this->createForm(AnnonceType::class, $annonce);

        $from->handleRequest($request);

        if($from->isSubmitted() && $from->isValid()){
            $manager->persist($annonce);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été enregistrée !"
            );

        }

        return $this->render('admin/annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $from->createView()
        ]);
    }

    /**
     * Allow to delete an annonce
     *
     * @Route("/admin/annonces/{id}/delete", name="admin_annonces_delete")
     * @param Annonce $annonce
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Annonce $annonce, EntityManagerInterface $manager)
    {
        if (count($annonce->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "L'annonce <strong>{$annonce->getTitle()}</strong> possède des réservations, vous ne pouvez donc pas la supprimer !"
            );
        } else {
            $manager->remove($annonce);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été supprimée !"
            );
        }
        

        return $this->redirectToRoute('admin_annonces_index');
    }
}
