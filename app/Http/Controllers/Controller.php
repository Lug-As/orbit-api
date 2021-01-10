<?php

namespace App\Http\Controllers;

use App\Resources\BadRequestResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param BadRequestResource|mixed $response
     * @return bool
     */
    protected function isBadRequestResponse($response): bool
    {
        return $response instanceof BadRequestResource;
    }
}
