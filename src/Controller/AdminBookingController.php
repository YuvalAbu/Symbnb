<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page}", name="admin_bookings_index", requirements={"page": "\d+"})
     */
    public function index(BookingRepository $repo, $page = 1, PaginationService $pagination)
    {

        $pagination->setEntityClass(Booking::class)
                    ->setCurrentPage($page);

        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Display edit booking
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     * 
     * @param Booking $booking
     * @return Response
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $from = $this->createForm(AdminBookingType::class, $booking);

        $from->handleRequest($request);

        if($from->isSubmitted() && $from->isValid()){
            //SI on le met à 0 elle sera considérer comme vide et donc vu qu'on a une focnction qui calcule le prix s'il est vide, le prix sera recalculer automatiquement
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation de <strong>n°{$booking->getBooker()->getFullName()}</strong> a bien été modifié !"
            );

            return $this->redirectToRoute('admin_bookings_index');

        }

        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $from->createView()
        ]);
    }

    /**
     * Allow to delete an booking
     *
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     * @param Booking $booking
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Booking $booking, EntityManagerInterface $manager)
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation de <strong>{$booking->getBooker()->getFullName()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_bookings_index');
    }
}
