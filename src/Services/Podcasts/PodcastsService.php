<?php

namespace App\Services\Podcasts;

use App\Repository\PodcastRepository;
use RuntimeException;

class PodcastsService
{

    public function __construct(private PodcastRepository $podcastRepository) {}

    public function getAll(int $userId)
    {
        try {
            return $this->podcastRepository->findBy(['id' => $userId]);
        } catch (RuntimeException) {
            return null;
        }
    }
}
