<?php


namespace App\Services\Api\V1\Files;


use File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileService
{
    public const UPLOAD_DIR = 'uploads';
    protected const FILENAME_CHARACTERS = 12;

    public function upload(UploadedFile $image)
    {
        $filename = $this->getFilename($image);
        $this->saveImage($image, $filename);
        return $filename;
    }

    public function delete(string $filename)
    {
        $file = public_path(FileService::UPLOAD_DIR . '/' . $filename);
        if (File::exists($file)) {
            return File::delete($file);
        }
        return true;
    }

    /**
     * @param UploadedFile $image
     * @return string|null
     */
    protected function getFilename(UploadedFile $image)
    {
        $filename = null;
        while (!$filename or $this->filenameIsTaken($filename)) {
            $filename = $this->generateRandomFilename($image->clientExtension());
        }
        return $filename;
    }

    /**
     * @param UploadedFile $image
     * @param string $filename
     */
    protected function saveImage(UploadedFile $image, string $filename)
    {
        return $image->move(self::UPLOAD_DIR, $filename);
    }

    protected function generateRandomFilename(string $ext): string
    {
        return Str::random(self::FILENAME_CHARACTERS) . ".$ext";
    }

    /**
     * @param string $filename
     * @return bool
     */
    protected function filenameIsTaken($filename): bool
    {
        return file_exists(self::UPLOAD_DIR . '/' . $filename);
    }
}
