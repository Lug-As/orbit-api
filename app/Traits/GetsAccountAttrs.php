<?php


namespace App\Traits;


use App\Services\Api\V1\Files\FileService;

trait GetsAccountAttrs
{
    public function getNameAttribute($data)
    {
        return '@' . $data;
    }

    public function getImageAttribute($data)
    {
        return $data ?
            request()->getSchemeAndHttpHost() . '/' . FileService::UPLOAD_DIR . "/$data"
            : null;
    }
}
