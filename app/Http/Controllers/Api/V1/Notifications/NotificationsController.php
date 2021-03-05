<?php

namespace App\Http\Controllers\Api\V1\Notifications;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\Notifications\NotificationsService;
use Auth;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * @var NotificationsService
     */
    private $notificationsService;

    public function __construct(NotificationsService $notificationsService)
    {
        $this->notificationsService = $notificationsService;
    }

    public function index()
    {
        if (!Auth::user()->is_admin) {
            abort(404);
        }
        return response()->json($this->notificationsService->searchNotifications());
    }

    public function ownIndex()
    {
        //
    }
}
