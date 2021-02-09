<?php


namespace App\Services\Api\V1\Accounts\Handlers;


use App\Handlers\QueryHandler;
use Illuminate\Database\Eloquent\Builder;

class AccountQueryHandler extends QueryHandler
{
    protected $allowedFilters = [
        'price_from' => 'int',
        'price_to' => 'int',
        'topic' => 'array',
        'type' => 'array',
        'age' => 'array',
        'region' => 'array',
        'followers_from' => 'int',
        'followers_to' => 'int',
        'likes_from' => 'int',
        'likes_to' => 'int',
    ];

    protected $allowedSorts = [
        'price',
        'followers',
        'likes',
    ];

    protected $onlyOneAdType = false;

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
            $queryBuilder = $this->sortQuery($queryBuilder, $params);
            $queryBuilder = $this->searchQuery($queryBuilder, $params);
        }
        return $this->postpareQueryBuilder($queryBuilder);
    }

    protected function filterQuery(Builder $queryBuilder, array $params): Builder
    {
        return $this->buildFilterQuery($queryBuilder, $this->extractFilters($params));
    }

    protected function sortQuery(Builder $queryBuilder, array $params): Builder
    {
        return $this->buildSortQuery($queryBuilder, $this->extractSortParams($params));
    }

    protected function searchQuery(Builder $queryBuilder, array $params): Builder
    {
        return $this->buildSearchQuery($queryBuilder, $this->extractSearchQuery($params));
    }

    /**
     * @param Builder $queryBuilder
     * @param array $filters
     * @return Builder
     */
    protected function buildFilterQuery(Builder $queryBuilder, array $filters): Builder
    {
        $joinAccountAdType = false;
        if ($filters['topic']) {
            $queryBuilder->join('account_topic', 'account_topic.account_id', '=', 'accounts.id');
            if (is_array($filters['topic'])) {
                $queryBuilder->whereIn('account_topic.topic_id', $filters['topic']);
            } else {
                $queryBuilder->where('account_topic.topic_id', $filters['topic']);
            }
        }
        if ($filters['age']) {
            $queryBuilder->join('account_age', 'account_age.account_id', '=', 'accounts.id');
            if (is_array($filters['age'])) {
                $queryBuilder->whereIn('account_age.age_id', $filters['age']);
            } else {
                $queryBuilder->where('account_age.age_id', $filters['age']);
            }
        }
        if ($filters['region']) {
            if (is_array($filters['region'])) {
                $queryBuilder->whereIn('accounts.region_id', $filters['region']);
            } else {
                $queryBuilder->where('accounts.region_id', $filters['region']);
            }
        }
        if ($filters['type']) {
            if (is_array($filters['type'])) {
                $queryBuilder->whereIn('account_ad_type.ad_type_id', $filters['type']);
            } else {
                $this->onlyOneAdType = true;
                $queryBuilder->where('account_ad_type.ad_type_id', $filters['type']);
            }
            $joinAccountAdType = true;
        }
        if ($filters['price_from']) {
            $queryBuilder->where('account_ad_type.price', '>=', $filters['price_from']);
            $joinAccountAdType = true;
        }
        if ($filters['price_to']) {
            $queryBuilder->where('account_ad_type.price', '<=', $filters['price_to']);
            $joinAccountAdType = true;
        }
        if ($filters['followers_from']) {
            $queryBuilder->where('account_ad_type.followers', '>=', $filters['followers_from']);
            $joinAccountAdType = true;
        }
        if ($filters['followers_to']) {
            $queryBuilder->where('account_ad_type.followers', '<=', $filters['followers_to']);
            $joinAccountAdType = true;
        }
        if ($filters['likes_from']) {
            $queryBuilder->where('account_ad_type.likes', '>=', $filters['likes_from']);
            $joinAccountAdType = true;
        }
        if ($filters['likes_to']) {
            $queryBuilder->where('account_ad_type.likes', '<=', $filters['likes_to']);
            $joinAccountAdType = true;
        }
        if ($joinAccountAdType) {
            $queryBuilder->join('account_ad_type', 'account_ad_type.account_id', '=', 'accounts.id');
        }
        return $queryBuilder;
    }

    protected function buildSortQuery(Builder $queryBuilder, array $sortParams)
    {
        $sort = $sortParams['sort'];
        if ($sort) {
            $direction = $sortParams['direction'];
            if ($sort === 'price') {
                if ($this->onlyOneAdType) {
                    $queryBuilder->addSelect("account_ad_type.{$sort}");
                    $queryBuilder->orderBy("account_ad_type.{$sort}", $direction);
                }
            } else {
                $queryBuilder->orderBy("accounts.{$sort}", $direction);
            }
        }
        return $queryBuilder;
    }

    protected function buildSearchQuery(Builder $queryBuilder, string $searchQuery)
    {
        if ($searchQuery) {
            $queryBuilder
                ->addSelect('users.name')
                ->join('users', 'accounts.user_id', '=', 'users.id')
                ->where(function ($query) use ($searchQuery) {
                    $query->where('accounts.title', 'LIKE', "%{$searchQuery}%")
                        ->orWhere('users.name', 'LIKE', "%{$searchQuery}%");
                });
        }
        return $queryBuilder;
    }

    protected function prepareQueryBuilder(Builder $queryBuilder)
    {
        return $queryBuilder->addSelect([
            'accounts.id', 'accounts.title', 'accounts.image', 'accounts.user_id', 'accounts.created_at',
        ])
            ->distinct('accounts.id');
    }

    protected function postpareQueryBuilder(Builder $queryBuilder)
    {
        return $queryBuilder->latest()->orderByDesc('accounts.id');
    }
}
