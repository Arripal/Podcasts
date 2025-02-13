<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class StringTransformer implements DataTransformerInterface
{
    public function transform($filePath): mixed
    {
        $absolutePath = $_SERVER['DOCUMENT_ROOT'] . 'uploads/Audio/' . $filePath;

        if (!$filePath || !file_exists($absolutePath)) {
            return null;
        }

        return new File($absolutePath);
    }

    public function reverseTransform($file): mixed
    {
        return $file;
    }
}
