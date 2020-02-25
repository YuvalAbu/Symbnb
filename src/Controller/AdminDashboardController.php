<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard_index")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {

        $stats          = $statsService->getStats();
        $bestAnnonces   = $statsService->getAnnoncesStats('DESC');
        $worstAnnonces  = $statsService->getAnnoncesStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAnnonces' => $bestAnnonces, 
            'worstAnnonces' => $worstAnnonces, 
        ]);
    }
}
