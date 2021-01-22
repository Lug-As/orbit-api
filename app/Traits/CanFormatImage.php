<?php


namespace App\Traits;


use App\Services\Api\V1\Files\FileService;

trait CanFormatImage
{
    protected function formatImage($src)
    {
        return request()->getSchemeAndHttpHost() . '/' . FileService::UPLOAD_DIR . "/$src";
    }
}
