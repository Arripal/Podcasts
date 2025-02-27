<?php

namespace App\Controller\Account\Podcasts;

use App\Entity\Podcast;
use App\Form\CreatePodcastFormType;
use App\Services\Files\AudioFileService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreatePodcastController extends AbstractController
{

    public function __construct(private AudioFileService $fileService) {}

    #[Route('/app/account/podcasts/create', name: 'app_account_podcasts_create')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
    ): Response {

        $podcast = new Podcast();
        $form = $this->createForm(CreatePodcastFormType::class, $podcast);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('file')->getData();
            $podcast->setName($form->get('name')->getData());
            $podcast->setFile($file);
            $podcast->addAuthor($this->getUser());

            $description = $form->get('description')->getData();
            if ($description) {
                $podcast->setDescription($description);
            }

            $categories = $form->get('categories')->getData();
            foreach ($categories as $categorie) {
                $podcast->addCategory($categorie);
            }
            $podcast->setCreatedAt(new DateTimeImmutable('now'));

            $file = $this->fileService->uploadFile($file);

            $podcast->setFile($file['filename']);
            $podcast->setDuration($file['duration']);

            $entityManagerInterface->persist($podcast);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('account/podcasts/create/index.html.twig', [
            'form' => $form,
        ]);
    }
}
