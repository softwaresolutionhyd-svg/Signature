<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

final class ProductImageService
{
    private const OUTPUT_SIZE = 512;

    public function storeSquare(UploadedFile $file): string
    {
        if (extension_loaded('gd')) {
            try {
                return $this->storeProcessedSquare($file);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $this->storeOriginal($file);
    }

    public function delete(?string $path): void
    {
        if ($path === null || trim($path) === '') {
            return;
        }

        Storage::disk('public')->delete($path);
    }

    private function storeProcessedSquare(UploadedFile $file): string
    {
        $contents = $this->makeSquareJpeg($file);
        $name = 'products/'.Str::uuid()->toString().'.jpg';
        Storage::disk('public')->put($name, $contents);

        return $name;
    }

    private function storeOriginal(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $ext = 'jpg';
        }

        $name = 'products/'.Str::uuid()->toString().'.'.$ext;

        Storage::disk('public')->put($name, file_get_contents($file->getRealPath() ?: '') ?: '');

        return $name;
    }

    private function makeSquareJpeg(UploadedFile $file): string
    {
        $source = $this->loadImage($file);
        if ($source === false) {
            throw new RuntimeException('Unable to read the uploaded image.');
        }

        $width = imagesx($source);
        $height = imagesy($source);
        if ($width <= 0 || $height <= 0) {
            imagedestroy($source);
            throw new RuntimeException('Invalid image dimensions.');
        }

        $size = min($width, $height);
        $srcX = (int) max(0, floor(($width - $size) / 2));
        $srcY = (int) max(0, floor(($height - $size) / 2));

        $square = imagecreatetruecolor(self::OUTPUT_SIZE, self::OUTPUT_SIZE);
        if ($square === false) {
            imagedestroy($source);
            throw new RuntimeException('Unable to prepare image canvas.');
        }

        $white = imagecolorallocate($square, 255, 255, 255);
        imagefilledrectangle($square, 0, 0, self::OUTPUT_SIZE, self::OUTPUT_SIZE, $white);

        imagecopyresampled(
            $square,
            $source,
            0,
            0,
            $srcX,
            $srcY,
            self::OUTPUT_SIZE,
            self::OUTPUT_SIZE,
            $size,
            $size
        );

        imagedestroy($source);

        ob_start();
        imagejpeg($square, null, 88);
        imagedestroy($square);
        $binary = ob_get_clean();

        if ($binary === false || $binary === '') {
            throw new RuntimeException('Unable to encode product image.');
        }

        return $binary;
    }

    /** @return \GdImage|resource|false */
    private function loadImage(UploadedFile $file)
    {
        $path = $file->getRealPath();
        if ($path === false || ! is_readable($path)) {
            return false;
        }

        $mime = strtolower((string) $file->getMimeType());

        return match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
    }
}
