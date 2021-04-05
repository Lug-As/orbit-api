<?php


namespace App\Services\Api\V1\Notifications;


use App\Models\Offer;
use App\Models\Project;
use App\Models\Request;
use App\Models\Response;
use App\Services\Api\V1\Offers\OfferService;
use App\Services\Api\V1\Projects\ProjectService;
use App\Services\Api\V1\Requests\RequestService;
use App\Services\Api\V1\Responses\ResponseService;
use Auth;
use DB;
use Illuminate\Http\Request as HttpRequest;
use InvalidArgumentException;

class NotificationsService
{
    /**
     * @var RequestService
     */
    private $requestService;
    /**
     * @var ResponseService
     */
    private $responseService;
    /**
     * @var OfferService
     */
    private $offerService;
    /**
     * @var ProjectService
     */
    private $projectService;
    /**
     * @var HttpRequest
     */
    private $httpRequest;

    public function __construct(
        RequestService $requestService,
        ResponseService $responseService,
        OfferService $offerService,
        ProjectService $projectService,
        HttpRequest $httpRequest
    )
    {
        $this->requestService = $requestService;
        $this->responseService = $responseService;
        $this->offerService = $offerService;
        $this->projectService = $projectService;
        $this->httpRequest = $httpRequest;
    }

    public function searchNotifications()
    {
        $offers = Offer::select([
            'id',
            'created_at',
            DB::raw("'offers' AS 'table'"),
        ]);
        $responses = Response::select([
            'id',
            'created_at',
            DB::raw("'responses' AS 'table'"),
        ]);
        $projects = Project::select([
            'id',
            'created_at',
            DB::raw("'projects' AS 'table'"),
        ]);
        $notifications = Request::select([
            'id',
            'created_at',
            DB::raw("'requests' AS 'table'"),
        ])
            ->from(Request::whereChecked(0))
            ->union($offers)
            ->union($responses)
            ->union($projects)
            ->latest()
            ->paginate(15);
        $out = $notifications->toArray();
        $out['data'] = [];
        foreach ($notifications as $notification) {
            switch ($notification->table) {
                case 'requests':
                    $item = $this->requestService->findRequest($notification->id)['data'];
                    break;
                case 'offers':
                    $item = $this->offerService->findOffer($notification->id)['data'];
                    break;
                case 'projects':
                    $item = $this->projectService->findProject($notification->id)['data'];
                    break;
                case 'responses':
                    $item = $this->responseService->findResponseWithAdvertiser($notification->id)['data'];
                    break;
            }
            $item = array_merge($item->toArray($this->httpRequest), [
                'type' => $notification->table,
            ]);
            $out['data'][] = $item;
        }
        return $out;
    }

    public function searchUserNotifications()
    {
        $user = Auth::user();
        $requests = Request::select([
            'id',
            'created_at',
            DB::raw("'requests' AS 'table'"),
        ])
            ->from(
                Request::whereChecked(1)
                    ->where('user_id', $user->id)
            );
        $offers = Offer::select([
            'id',
            'created_at',
            DB::raw("'offers' AS 'table'"),
        ])
            ->from(
                Offer::whereIn('account_id',
                    $user->accounts->map(function ($item) {
                        return $item->id;
                    })
                )
            );
        $notifications = Response::select([
            'id',
            'created_at',
            DB::raw("'responses' AS 'table'"),
        ])
            ->from(
                Response::whereIn('project_id',
                    $user->projects->map(function ($item) {
                        return $item->id;
                    })
                )
            )
            ->union($offers)
            ->union($requests)
            ->latest()
            ->paginate(10);
        return $this->resolveUserNotificationEntities($notifications);
    }

    protected function resolveUserNotificationEntities($notifications)
    {
        $out = $notifications->toArray();
        $out['data'] = [];
        foreach ($notifications as $notification) {
            switch ($notification->table) {
                case 'offers':
                    $item = $this->offerService->findOffer($notification->id)['data'];
                    break;
                case 'responses':
                    $item = $this->responseService->findResponse($notification->id)['data'];
                    break;
                case 'requests':
                    $item = $this->requestService->findRequest($notification->id)['data'];
                    break;
                default:
                    throw new InvalidArgumentException('Invalid table name: ' . $notification->table);
            }
            $item = array_merge($item->toArray($this->httpRequest), [
                'type' => $notification->table,
            ]);
            $out['data'][] = $item;
        }
        return $out;
    }
}
