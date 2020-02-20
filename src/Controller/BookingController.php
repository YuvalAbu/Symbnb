<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends AbstractController
{
    /**
     * @Route("/annonces/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Annonce $annonce, Request $request, EntityManagerInterface $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
     
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $booking->setBooker($user)
                    ->setAnnonce($annonce);

            if (!$booking->isBookableDates()) {
                $this->addFlash(
                    'warning',
                    'Les dates que vous avez séléctionnez ne peuvent être réserver, elle sont déja prise !'
                );
            }else{
                $manager->persist($booking);
                $manager->flush();
                return $this->redirectToRoute('booking_show', ['id' => $booking->getId(), 'withAlert' => true]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView()
        ]);
    }

    /**
     * Display a book
     *
     * @Route("/booking/{id}", name="booking_show")
     * 
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking)
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }
}
