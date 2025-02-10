<?php

namespace App\Services\Files;

use App\Services\Files\AudioFileDurationSetterService;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class AudioFileUploaderService
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $sluggerInterface,
        private AudioFileDurationSetterService $durationSetter
    ) {}

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
        $duration = $this->durationSetter->getDurationInSeconds($filePath);

        return [
            'filename' => $newFilename,
            'duration' => $duration
        ];
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
