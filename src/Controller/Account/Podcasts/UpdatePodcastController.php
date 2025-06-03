<?php

namespace App\Controller\Account\Podcasts;

use App\Form\UpdatePodcastFormType;
use App\Repository\PodcastRepository;
use App\Services\Podcasts\PodcastsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdatePodcastController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManagerInterface, private PodcastRepository $podcastRepository, private PodcastsService $podcastsService) {}

    #[Route('/app/account/podcasts/update/{identifier}', name: 'app_account_podcasts_update', methods: ['GET', 'POST'])]
    public function updatePodcast($identifier, Request $request): Response
    {

        $podcastToUpdate = $this->podcastRepository->findOneBy(['id' => $identifier]);

        $form = $this->createForm(UpdatePodcastFormType::class, $podcastToUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->podcastsService->updatePodcast($form, $podcastToUpdate);
            $this->addFlash('success', 'Votre podcast a bien été mis à jour.');
            return $this->redirectToRoute('app_account_podcasts');
        }

        return $this->render('account/podcasts/update_podcast/index.html.twig', [
            'form' => $form,
            'podcast' => $podcastToUpdate
        ]);
    }
}
