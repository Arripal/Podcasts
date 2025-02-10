<?php

namespace App\Services\Podcasts;

use App\Entity\Podcast;
use App\Repository\PodcastRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class PodcastsService
{
    public function __construct(private PodcastRepository $podcastRepository, private EntityManagerInterface $entityManagerInterface) {}

    public function getAll(int $userId)
    {

        return $this->podcastRepository->findBy(['id' => $userId]) ?? null;
    }

    public function getOneBy($podcastId)
    {
        return $this->podcastRepository->findOneBy(['id' => $podcastId]) ?? null;
    }

    public function isUserOwningPodcast($podcastId, array $currentUserPodcasts)
    {
        $result = array_filter($currentUserPodcasts, function ($podcast) use ($podcastId) {
            return $podcast->getId() == $podcastId;
        });

        return !empty($result);
    }

    public function removePodcast($podcastId)
    {
        $entityRepo = $this->entityManagerInterface->getRepository(Podcast::class);
        $existingPodcast = $entityRepo->find($podcastId);

        $this->entityManagerInterface->remove($existingPodcast);
        $this->entityManagerInterface->flush();
    }
}
