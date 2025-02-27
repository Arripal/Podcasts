<?php

namespace App\Controller\Search;

use App\Services\Podcasts\PodcastsService;
use App\Services\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{

    public function __construct(private UserService $userService, private PodcastsService $podcastsService) {}

    #[Route('app/search', name: 'app_search')]
    public function searchData(Request $request): Response
    {
        $results = [
            'users' => [],
            'podcasts' => [],
        ];

        $searchValue = $request->get('query');

        if ($searchValue) {

            $users = $this->userService->searchCorrespondingUsers($searchValue);
            $podcasts = $this->podcastsService->searchCorrespondingPodcasts($searchValue);

            $currentUser = $this->userService->getAuthenticatedUser();

            $users = array_filter($users, function ($user) use ($currentUser) {
                return $user->getId() !== $currentUser->getId();
            });

            $results = [
                'users' => $users,
                'podcasts' => $podcasts
            ];
        }

        return $this->render('search/index.html.twig', [
            'searchValue' => $searchValue,
            'results' => $results
        ]);
    }
}
