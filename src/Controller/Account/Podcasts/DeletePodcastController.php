<?php

namespace App\Controller\Account\Podcasts;

use App\Services\Podcasts\PodcastsService;
use App\Services\User\UserService;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeletePodcastController extends AbstractController
{

    public function __construct(private PodcastsService $podcastsService, private UserService $userService) {}

    #[Route('/app/account/podcasts/delete/{identifier}', name: 'app_account_podcasts_delete')]
    public function deletePodcast($identifier): Response
    {

        try {
            $currentUser = $this->userService->getAuthenticatedUser();
            $this->podcastsService->removePodcast(['id' => $identifier, 'author' => $currentUser]);
            $this->addFlash('success', 'Le podcast a bien été supprimé.');

            return  $this->redirectToRoute('app_account_podcasts');
        } catch (RuntimeException) {

            $this->addFlash('error', 'Impossible de supprimer le podcast.');

            return  $this->redirectToRoute('app_account_podcasts', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
