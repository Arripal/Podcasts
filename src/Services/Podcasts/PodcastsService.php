<?php

namespace App\Services\Podcasts;

use App\Entity\Podcast;
use App\Repository\PodcastRepository;
use App\Services\Files\AudioFileService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;


class PodcastsService
{
    public function __construct(private PodcastRepository $podcastRepository, private EntityManagerInterface $entityManagerInterface, private AudioFileService $audioFileService) {}

    public function getAll(int $userId)
    {

        return $this->podcastRepository->findBy(['id' => $userId]) ?? null;
    }

    public function getOneBy($podcastId)
    {
        return $this->podcastRepository->findOneBy(['id' => $podcastId]) ?? null;
    }

    public function isUserOwningPodcast($podcastId, Collection $currentUserPodcasts)
    {
        $result = array_filter($currentUserPodcasts->toArray(), function ($podcast) use ($podcastId) {
            return $podcast->getId() == $podcastId;
        });

        return !empty($result);
    }

    public function removePodcast($podcastId)
    {

        $existingPodcast = $this->entityManagerInterface->getRepository(Podcast::class)->find($podcastId);

        $this->audioFileService->removeAudioFile($existingPodcast);
        $this->entityManagerInterface->remove($existingPodcast);
        $this->entityManagerInterface->flush();
    }
}
