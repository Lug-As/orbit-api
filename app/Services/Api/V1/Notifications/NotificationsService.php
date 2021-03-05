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
use DB;
use Illuminate\Http\Resources\Json\JsonResource;

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

    public function __construct(
        RequestService $requestService,
        ResponseService $responseService,
        OfferService $offerService,
        ProjectService $projectService
    )
    {
        $this->requestService = $requestService;
        $this->responseService = $responseService;
        $this->offerService = $offerService;
        $this->projectService = $projectService;
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
            ->paginate();
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
                    $item = $this->responseService->findResponse($notification->id)['data'];
                    break;
            }
            $item = array_merge($item->toArray(request()), [
                'type' => $notification->table,
            ]);
            $out['data'][] = $item;
        }
        return $out;
    }
}

//    SELECT
//        requests.id, requests.created_at, 'requests' AS 'table'
//    FROM
//        requests
//    UNION
//    SELECT
//        offers.id, offers.created_at, 'offers' AS 'table'
//    FROM
//        offers
//    UNION
//    SELECT
//        responses.id, responses.created_at, 'responses' AS 'table'
//    FROM
//        responses
//    UNION
//    SELECT
//        projects.id, projects.created_at, 'projects' AS 'table'
//    FROM
//        projects
//        ORDER BY created_at DESC LIMIT 10 OFFSET 0
