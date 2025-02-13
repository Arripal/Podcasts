<?php

namespace App\Controller\Account\Podcasts;

use App\Form\UpdatePodcastFormType;
use App\Services\Files\AudioFileService;
use App\Services\Podcasts\PodcastsService;
use App\Services\Router\RouterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdatePodcastController extends AbstractController
{

    public function __construct(private PodcastsService $podcastsService, private EntityManagerInterface $entityManagerInterface, private AudioFileService $fileService, private RouterService $routerService) {}

    #[Route('/app/account/podcasts/update/{identifier}', name: 'app_account_podcasts_update')]
    public function index($identifier, Request $request): Response
    {

        $podcastToUpdate = $this->podcastsService->getOneBy($identifier);

        $form = $this->createForm(UpdatePodcastFormType::class, $podcastToUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            if ($file) {
                $podcastToUpdate->setFile($file);
                $file = $this->fileService->uploadFile($file);
                $podcastToUpdate->setFile($file['filename']);
                $podcastToUpdate->setDuration($file['duration']);
            }

            $podcastToUpdate->setName($form->get('name')->getData());
            $podcastToUpdate->addAuthor($this->getUser());
            $categories = $form->get('categories')->getData();

            foreach ($categories as $categorie) {
                $podcastToUpdate->addCategory($categorie);
            }

            $this->entityManagerInterface->flush();
            $this->addFlash('success', 'Votre podcast a bien été mis à jour.');
            return $this->routerService->generateURL('app_home');
        }

        return $this->render('account/podcasts/update_podcast/index.html.twig', [
            'form' => $form->createView(),
            'podcast' => $podcastToUpdate
        ]);
    }
}
