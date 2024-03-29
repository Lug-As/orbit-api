<?php


namespace App\Services\Api\V1\Projects;


use App\Models\Project;
use App\Services\Api\V1\Projects\Handlers\ProjectQueryHandler;
use App\Services\Api\V1\Projects\Resources\ProjectResource;
use App\Services\Api\V1\Projects\Resources\ProjectWithResponsesResource;
use App\Services\Api\V1\Projects\Resources\ProjectsResource;
use App\Services\Api\V1\Telegram\TelegramBot;
use App\Traits\CanWrapInData;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use DB;

class ProjectService
{
    use CanWrapInData;

    /**
     * @var ProjectQueryHandler
     */
    private $projectQueryHandler;

    public function __construct(ProjectQueryHandler $projectQueryHandler)
    {
        $this->projectQueryHandler = $projectQueryHandler;
    }

    public function searchProjects(?array $params = null)
    {
        $projects = $this->projectQueryHandler->handle($this->queryBuilder(), $params)
            ->paginate(10);
        return ProjectsResource::make($projects);
    }

    public function searchUserProjects()
    {
        $projects = $this->queryBuilder()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return ProjectsResource::make($projects);
    }

    public function findProject($id)
    {
        $project = $this->queryBuilder()->findOrFail($id);
        return $this->wrapInData(ProjectResource::make($project));
    }

    public function storeProject(array $data)
    {
        $data['user_id'] = Auth::id();
        $project = DB::transaction(function () use ($data) {
            $project = Project::create($data);
            if (isset($data['ad_types'])) {
                $project->ad_types()->sync($data['ad_types']);
            }
            return $project;
        }, 2);
        TelegramBot::notifyAdminAboutProject($project);
        return $this->wrapInData(ProjectResource::make($project));
    }

    public function updateProject(array $data, $id)
    {
        $project = Project::findOrFail($id);
        $project->update($data);
        if (isset($data['ad_types'])) {
            $project->ad_types()->sync($data['ad_types']);
        }
        return $this->wrapInData(ProjectResource::make($project));
    }

    public function destroyProject($id)
    {
        return Project::whereId($id)->delete();
    }

    /**
     * Return builder with model's relations
     *
     * @return Builder
     */
    protected function queryBuilder(): Builder
    {
        return Project::with(['user', 'ad_types'])->withCount('responses');
    }

    /**
     * Return builder with model's relations
     *
     * @return Builder
     */
    protected function queryBuilderWithResponses(): Builder
    {
        return Project::with(['user', 'ad_types', 'responses']);
    }

    /**
     * @param int $id
     * @return Project|null
     */
    public function getProjectOnlyUserId($id): ?Project
    {
        return Project::findOrFail($id, ['user_id']);
    }
}
