<?php


namespace App\Traits;


use App\Services\Api\V1\Files\FileService;

trait GetsAccountAttrs
{
    public function getImageAttribute($data)
    {
        return $data ? $this->formatImage($data) : null;
    }

    protected function formatImage($src)
    {
        return request()->getSchemeAndHttpHost() . '/' . FileService::UPLOAD_DIR . "/$src";
    }
}
