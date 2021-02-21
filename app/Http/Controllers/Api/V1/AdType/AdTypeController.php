<?php

namespace App\Http\Controllers\Api\V1\AdType;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\AdTypes\AdTypeService;
use Illuminate\Http\Request;

class AdTypeController extends Controller
{
    /**
     * @var AdTypeService
     */
    private $adTypeService;

    public function __construct(AdTypeService $adTypeService)
    {
        $this->adTypeService = $adTypeService;
    }

    public function index()
    {
        return response()->json($this->adTypeService->searchAdTypes());
    }
}
