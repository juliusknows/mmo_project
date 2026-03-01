<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Manager\CommentManager;
use App\Manager\NewsManager;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final readonly class DashboardController
{
    public function __construct(
        private Environment $twig,
        private NewsManager $newsManager,
        private UserManager $userManager,
        private CommentManager $commentManager,
    ) {
    }

    public function showAdminDashboard(): Response
    {
        $latestNews = $this->newsManager->getLatestNews();
        $totalNews = $this->newsManager->getTotal();
        $totalUser = $this->userManager->getTotal();
        $totalComment = $this->commentManager->getTotal();

        $dashboardData = [
            'latestNews' => $latestNews,
            'totalUser' => $totalUser,
            'totalNews' => $totalNews,
            'totalComment' => $totalComment,
        ];

        return new Response($this->twig->render('admin/dashboard.html.twig', [
            'dashboardData' => $dashboardData,
        ]));
    }
}
