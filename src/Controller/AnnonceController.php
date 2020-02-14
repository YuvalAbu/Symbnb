<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AnnonceController extends AbstractController
{
    /**
     * list all Annonces
     * 
     * @Route("/annonces", name="annonces_index")
     * 
     * @return Response
     */
    public function index(AnnonceRepository $repo)
    {
        $annonces = $repo->findAll();

        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces
        ]);
    }

    /**
     * Create one Annonce
     * 
     * @Route("/annonces/new", name="annonces_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {

        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($annonce->getImages() as $image) {
                $image->setAnnonce($annonce);
                $manager->persist($image);
            }

            $manager->persist($annonce);
            $manager->flush();

            //On peut mettre le nom qu'on veut (success ou toto...)
            $this->addFlash(
                'success',
                "L'annonce <strong>{$annonce->getTitle()}</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('annonces_show', [
                'slug' => $annonce->getSlug()
            ]);
        }

        return $this->render('annonce/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit an Annonce
     * 
     * @Route("/annonces/{slug}/edit", name="annonces_edit")
     * 
     * @return Response
     */
    public function edit(Annonce $annonce, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($annonce->getImages() as $image) {
                $image->setAnnonce($annonce);
                $manager->persist($image);
            }

            $manager->persist($annonce);
            $manager->flush();

            //On peut mettre le nom qu'on veut (success ou toto...)
            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$annonce->getTitle()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('annonces_show', [
                'slug' => $annonce->getSlug()
            ]);
        }

        return $this->render('annonce/edit.html.twig', [
            'form' => $form->createView(),
            'annonce' => $annonce
        ]);
    }

    /**
     * Display one Annonce
     * 
     * @Route("/annonces/{slug}", name="annonces_show")
     * 
     * @return Response
     */
    public function show(Annonce $annonce)
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce
        ]);
    }
}
 