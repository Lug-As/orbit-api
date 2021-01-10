<?php

namespace App\Http\Controllers;

use App\Resources\ValidationErrorsResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function isValidationErrorResponse($response)
    {
        return $response instanceof ValidationErrorsResource;
    }
}
