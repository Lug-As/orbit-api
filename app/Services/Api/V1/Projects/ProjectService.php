<?php


namespace App\Services\Api\V1\Projects;


use App\Models\Project;
use App\Services\Api\V1\Projects\Resources\ProjectResource;
use App\Services\Api\V1\Projects\Resources\ProjectsResource;
use App\Traits\CanWrapInData;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class ProjectService
{
    use CanWrapInData;

    public function searchProjects()
    {
        return ProjectsResource::make($this->queryBuilder()->paginate(10));
    }

    public function findProject($id)
    {
        return $this->wrapInData(ProjectResource::make($this->queryBuilder()->findOrFail($id)));
    }

    public function storeProject(array $data)
    {
        $data['user_id'] = Auth::id();
        $project = Project::create($data);
        return $this->wrapInData(ProjectResource::make($project));
    }

    public function updateProject(array $data, int $id)
    {
        $project = Project::findOrFail($id);
        $project->update($data);
        return $this->wrapInData(ProjectResource::make($project));
    }

    public function destroyProject(int $id)
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
