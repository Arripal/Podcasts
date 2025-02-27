<?php

namespace App\Services\Podcasts;

use App\Entity\Podcast;
use App\Entity\User;
use App\Repository\PodcastRepository;
use App\Services\Files\AudioFileService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;


class PodcastsService
{
    public function __construct(private PodcastRepository $podcastRepository, private EntityManagerInterface $entityManagerInterface, private AudioFileService $audioFileService) {}

    public function getOneBy(array $params): ?Podcast
    {
        return $this->podcastRepository->findOneBy($params);
    }

    public function isUserOwningPodcast($podcastId, Collection $currentUserPodcasts)
    {
        $result = array_filter($currentUserPodcasts->toArray(), function ($podcast) use ($podcastId) {
            return $podcast->getId() == $podcastId;
        });

        return !empty($result);
    }

    public function removePodcast(array $params)
    {

        $existingPodcast = $this->getOneBy($params);

        if (!$existingPodcast) {
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
}
