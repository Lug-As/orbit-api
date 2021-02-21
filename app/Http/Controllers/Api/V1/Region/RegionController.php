<?php

namespace App\Http\Controllers\Api\V1\Region;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\Regions\RegionService;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * @var RegionService
     */
    private $regionService;

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    public function index()
    {
        return response()->json($this->regionService->searchRegions());
    }
}
