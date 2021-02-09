<?php


namespace App\Services\Api\V1\Projects\Handlers;


use App\Handlers\QueryHandler;
use Illuminate\Database\Eloquent\Builder;

class ProjectQueryHandler extends QueryHandler
{
    protected $allowedFilters = [
        'budget_from' => 'int',
        'budget_to' => 'int',
        'type' => 'array',
        'region' => 'array',
        'followers_from' => 'int',
        'followers_to' => 'int',
    ];

    /**
     * @param Builder $queryBuilder
     * @param array|null $params
     * @return Builder
     */
    public function handle(Builder $queryBuilder, ?array $params): Builder
    {
        $queryBuilder = $this->prepareQueryBuilder($queryBuilder);
        if ($params) {
            $queryBuilder = $this->filterQuery($queryBuilder, $params);
            $queryBuilder = $this->sortQuery($queryBuilder);
            $queryBuilder = $this->searchQuery($queryBuilder, $params);
        }
        return $queryBuilder;
    }

    protected function prepareQueryBuilder(Builder $queryBuilder)
    {
        return $queryBuilder->addSelect('projects.*')
            ->distinct('projects.id');
    }

    protected function filterQuery(Builder $queryBuilder, array $params): Builder
    {
        return $this->buildFilterQuery($queryBuilder, $this->extractFilters($params));
    }

    protected function searchQuery(Builder $queryBuilder, array $params): Builder
    {
        return $this->buildSearchQuery($queryBuilder, $this->extractSearchQuery($params));
    }

    protected function sortQuery(Builder $queryBuilder)
    {
        return $queryBuilder->latest()->orderByDesc('id');
    }

    protected function buildSearchQuery(Builder $queryBuilder, string $searchQuery)
    {
        if ($searchQuery) {
            $queryBuilder->where('projects.name', 'LIKE', "%{$searchQuery}%");
        }
        return $queryBuilder;
    }

    protected function buildFilterQuery(Builder $queryBuilder, array $filters)
    {
//        if ($filters['region']) {
//            if (is_array($filters['region'])) {
//                $queryBuilder->whereIn('projects.region_id', $filters['region']);
//            } else {
//                $queryBuilder->where('projects.region_id', $filters['region']);
//            }
//        }
        if ($filters['type']) {
            if (is_array($filters['type'])) {
                $queryBuilder->whereIn('project_ad_type.ad_type_id', $filters['type']);
            } else {
                $this->onlyOneAdType = true;
                $queryBuilder->where('project_ad_type.ad_type_id', $filters['type']);
            }
            $joinAccountAdType = true;
        }
        if ($filters['budget_from']) {
            $queryBuilder->where('project_ad_type.budget', '>=', $filters['budget_from']);
            $joinAccountAdType = true;
        }
        if ($filters['budget_to']) {
            $queryBuilder->where('project_ad_type.budget', '<=', $filters['budget_to']);
            $joinAccountAdType = true;
        }
        if ($filters['followers_from']) {
            $queryBuilder->where('project_ad_type.followers', '>=', $filters['followers_from']);
            $joinAccountAdType = true;
        }
        if ($filters['followers_to']) {
            $queryBuilder->where('project_ad_type.followers', '<=', $filters['followers_to']);
            $joinAccountAdType = true;
        }
        if ($joinAccountAdType) {
            $queryBuilder->join('project_ad_type', 'project_ad_type.project_id', '=', 'projects.id');
        }
        return $queryBuilder;
    }
}
