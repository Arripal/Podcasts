<?php

namespace App\Controller\Account\Podcasts;

use App\Services\Podcasts\PodcastsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailsPodcastController extends AbstractController
{
    #[Route('/app/account/podcasts/details/{identifier}', name: 'app_account_podcasts_details')]
    public function getPodcastDetails($identifier, PodcastsService $podcastsService): Response
    {
        $errorMessage = '';
        $podcastDetails = $podcastsService->getOneBy($identifier);

        if ($podcastDetails == null or count($podcastDetails) == 0) {
            $errorMessage = 'Oups. Impossible d\'afficher les informations concernant le podcast. Réessayer ultérieurement.';
        }

        return $this->render('account/podcasts/details_podcast/index.html.twig', [
            'podcast_details' => $podcastDetails,
            'error_message' => $errorMessage
        ]);
    }
}
