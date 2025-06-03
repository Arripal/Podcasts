<?php

namespace App\Services\Podcasts;

use App\Entity\Podcast;
use App\Repository\PodcastRepository;
use App\Services\Files\AudioFileService;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Form;

class PodcastsService
{
    public function __construct(private PodcastRepository $podcastRepository, private EntityManagerInterface $entityManagerInterface, private AudioFileService $audioFileService, private Security $security) {}

    public function isUserOwningPodcast($podcastId, Collection $currentUserPodcasts)
    {
        $result = array_filter($currentUserPodcasts->toArray(), function ($podcast) use ($podcastId) {
            return $podcast->getId() == $podcastId;
        });

        return !empty($result);
    }

    public function removePodcast(string $identifier)
    {

        $existingPodcast = $this->podcastRepository->find($identifier);

        if (!$existingPodcast || !$existingPodcast->getAuthor()->contains($this->security->getUser())) {
            return null;
        }

        $this->audioFileService->removeAudioFile($existingPodcast);
        $this->entityManagerInterface->remove($existingPodcast);
        $this->entityManagerInterface->flush();
    }

    public function searchCorrespondingPodcasts($searchValue): array
    {
        return $this->entityManagerInterface->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\Podcast', 'p')
            ->where('LOWER(p.name) LIKE LOWER(:term)')
            ->setParameter('term', '%' . $searchValue . '%')
            ->getQuery()
            ->getResult();
    }

    public function createPodcast(Form $form, Podcast $podcast): Podcast
    {

        $file = $form->get('file')->getData();

        $podcast->setName($form->get('name')->getData())
            ->setFile($file)
            ->addAuthor($this->security->getUser());

        $description = $form->get('description')->getData();
        if ($description) {
            $podcast->setDescription($description);
        }

        $categories = $form->get('categories')->getData();

        foreach ($categories as $categorie) {
            $podcast->addCategory($categorie);
        }

        $podcast->setCreatedAt(new DateTimeImmutable('now'));

        $file = $this->audioFileService->uploadFile($file);

        $podcast->setFile($file['filename']);
        $podcast->setDuration($file['duration']);

        return $podcast;
    }
}
