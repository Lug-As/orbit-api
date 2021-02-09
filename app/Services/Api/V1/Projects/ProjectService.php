<?php


namespace App\Services\Api\V1\Projects;


use App\Models\Project;
use App\Services\Api\V1\Projects\Resources\ProjectResource;
use App\Services\Api\V1\Projects\Resources\ProjectWithResponsesResource;
use App\Services\Api\V1\Projects\Resources\ProjectsResource;
use App\Traits\CanWrapInData;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class ProjectService
{
    use CanWrapInData;

    public function searchProjects()
    {
        $projects = $this->queryBuilder()
            ->latest()
            ->orderByDesc('id')
            ->paginate(10);
        return ProjectsResource::make($projects);
    }

    public function findProject($id)
    {
        $project = $this->queryBuilderWithResponses()->findOrFail($id);
        return $this->wrapInData(ProjectWithResponsesResource::make($project));
    }

    public function storeProject(array $data)
    {
        $data['user_id'] = Auth::id();
        $project = Project::create($data);
        return $this->wrapInData(ProjectResource::make($project));
    }

    public function updateProject(array $data, $id)
    {
        $project = Project::findOrFail($id);
        $project->update($data);
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
