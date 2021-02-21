<?php

namespace App\Http\Controllers\Api\V1\Age;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\Ages\AgeService;
use Illuminate\Http\Request;

class AgeController extends Controller
{
    /**
     * @var AgeService
     */
    private $ageService;

    public function __construct(AgeService $ageService)
    {
        $this->ageService = $ageService;
    }

    public function index()
    {
        return response()->json($this->ageService->searchAges());
    }
}
