<?php

namespace App\Services\Files;

use getID3;

class AudioFileDurationSetterService
{
    private $getID3;

    public function __construct()
    {
        $this->getID3 = new getID3();
    }

    public function getDurationInSeconds($filePath)
    {
        $durationInfos = $this->getID3->analyze($filePath);

        return $durationInfos['playtime_seconds'] ?? 0;
    }
}
