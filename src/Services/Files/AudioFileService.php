<?php

namespace App\Services\Files;

use App\Entity\Podcast;
use getID3;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


class AudioFileService
{

    private $getID3;

    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $sluggerInterface,
    ) {
        $this->getID3 = new getID3();
    }

    public function uploadFile(UploadedFile $file)
    {

        $baseFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->sluggerInterface->slug($baseFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->targetDirectory, $newFilename);
        } catch (FileException $e) {
            throw $e;
        }


        $filePath = $this->targetDirectory . '/' . $newFilename;
        $duration = $this->getDurationInSeconds($filePath);

        return [
            'filename' => $newFilename,
            'duration' => $duration
        ];
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function removeAudioFile(Podcast $podcast)
    {
        $filePath = $this->targetDirectory . '/' . $podcast->getFile();

        if (!file_exists($filePath) or !is_file($filePath)) {
            throw new RuntimeException("Impossible de supprimer le fichier demandé. Il n'existe pas ou n'est pas valide.");
        }

        unlink($filePath);
    }

    private function getDurationInSeconds($filePath)
    {
        $durationInfos = $this->getID3->analyze($filePath);

        return $durationInfos['playtime_seconds'] ?? 0;
    }
}
