<?php

namespace App\Controller\Account\Podcasts;

use App\Entity\Podcast;
use App\Form\CreatePodcastFormType;
use App\Services\Files\AudioFileService;
use App\Services\Podcasts\PodcastsService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreatePodcastController extends AbstractController
{

    public function __construct(private AudioFileService $fileService, private PodcastsService $podcasts_service) {}

    #[Route('/app/account/podcasts/create', name: 'app_account_podcasts_create')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
    ): Response {

        $empty_podcast = new Podcast();
        $form = $this->createForm(CreatePodcastFormType::class, $empty_podcast);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $podcast = $this->podcasts_service->createPodcast($form, $empty_podcast);

            $entityManagerInterface->persist($podcast);
            $entityManagerInterface->flush();

            $this->addFlash('success', "Le podcast {$podcast->getName()} a été créé.");

            return $this->redirectToRoute('app_home');
        }

        return $this->render('account/podcasts/create/index.html.twig', [
            'form' => $form,
        ]);
    }
}
