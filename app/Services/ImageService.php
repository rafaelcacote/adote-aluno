<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageService
{
    private const MAX_DIMENSION = 400;

    private const JPEG_QUALITY = 85;

    public function storeAlunoFoto(UploadedFile $file): string
    {
        $source = $this->createImageFromFile($file);
        $resized = $this->resizeToFit($source, self::MAX_DIMENSION, self::MAX_DIMENSION);

        ob_start();
        imagejpeg($resized, null, self::JPEG_QUALITY);
        $contents = ob_get_clean();

        imagedestroy($source);
        imagedestroy($resized);

        if ($contents === false) {
            throw new RuntimeException('Falha ao processar a imagem.');
        }

        $path = 'alunos/'.Str::uuid().'.jpg';
        Storage::disk('public')->put($path, $contents);

        return $path;
    }

    private function createImageFromFile(UploadedFile $file): \GdImage
    {
        $path = $file->getRealPath();
        $mime = $file->getMimeType();

        $image = match ($mime) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            default => false,
        };

        if ($image === false) {
            throw new RuntimeException('Formato de imagem não suportado.');
        }

        return $image;
    }

    private function resizeToFit(\GdImage $source, int $maxWidth, int $maxHeight): \GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);

        $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
        $newWidth = (int) max(1, round($width * $ratio));
        $newHeight = (int) max(1, round($height * $ratio));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        return $canvas;
    }
}
