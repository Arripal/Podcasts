<?php

namespace App\Controller\Account\Podcasts;

use App\Repository\PodcastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailsPodcastController extends AbstractController
{
    public function __construct(private PodcastRepository $podcastRepository) {}

    #[Route('/app/account/podcasts/details/{identifier}', name: 'app_account_podcasts_details', methods: ['GET'])]
    public function getPodcastDetails($identifier): Response
    {
        $podcast = $this->podcastRepository->findOneBy(['id' => $identifier]);

        if ($podcast === null) {
            $this->addFlash('error', 'Impossible d\'afficher les informations concernant le podcast. Réessayer ultérieurement.');
            return $this->redirectToRoute('app_account_podcasts');
        }

        return $this->render('account/podcasts/details_podcast/index.html.twig', [
            'podcast' => $podcast,
        ]);
    }
}
