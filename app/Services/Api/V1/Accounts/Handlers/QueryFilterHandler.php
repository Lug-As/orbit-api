<?php


namespace App\Services\Api\V1\Accounts\Handlers;


use Illuminate\Database\Eloquent\Builder;

class QueryFilterHandler
{
    const ALLOWED_FILTERS = [
        'price_from' => 'int',
        'price_to' => 'int',
        'topic' => 'array',
        'type' => 'array',
        'followers_from' => 'int',
        'followers_to' => 'int',
    ];

    const ALLOWED_SORTS = [
        'price',
        'followers',
    ];

    protected $onlyOneAdType = false;

    /**
     * @param Builder $queryBuilder
     * @param array|null $params
     * @return Builder
     */
    public function filter(Builder $queryBuilder, ?array $params): Builder
    {
        if (is_array($params)) {
            $queryBuilder = $this->prepareQueryBuilder($queryBuilder);
            $queryBuilder = $this->filterQuery($queryBuilder, $params);
            $queryBuilder = $this->sortQuery($queryBuilder, $params);
            $queryBuilder = $this->searchQuery($queryBuilder, $params);
            $queryBuilder = $this->postpareQueryBuilder($queryBuilder);
        }
        return $queryBuilder;
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

    protected function extractFilters(array $params): array
    {
        $extracted = [];
        foreach (self::ALLOWED_FILTERS as $allowedFilter => $type) {
            if (key_exists($allowedFilter, $params)) {
                $original = $params[$allowedFilter];
                if ($type === 'int') {
                    $value = intval($original);
                }
                if ($type === 'array') {
                    $value = $this->extractArrayType($original);
                }
            } else {
                $value = false;
            }
            $extracted[$allowedFilter] = $value;
        }
        return $extracted;
    }

    protected function extractArrayType($var)
    {
        if (is_string($var)) {
            $var = trim($var, ',');
            if (strpos($var, ',') !== false) {
                $var = explode(',', $var);
                $var = array_map('intval', $var);
            } else {
                $var = intval($var);
            }
            return $var;
        }
        return false;
    }

    protected function extractSortParams(array $params): array
    {
        $sort = false;
        $direction = 'asc';
        if (isset($params['sort']) and in_array($params['sort'], self::ALLOWED_SORTS)) {
            $sort = $params['sort'];
        }
        if (isset($params['dir']) and strcasecmp($params['dir'], 'desc') === 0) {
            $direction = 'desc';
        }
        $extracted['sort'] = $sort;
        $extracted['direction'] = $direction;
        return $extracted;
    }

    protected function extractSearchQuery(array $params): string
    {
        return isset($params['q']) && is_string($params['q']) && mb_strlen($params['q']) <= 25
            ? $params['q']
            : '';
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
        if ($joinAccountAdType) {
            $queryBuilder->join('account_ad_type', 'account_ad_type.account_id', '=', 'accounts.id');
        }
        return $queryBuilder;
    }

    protected function buildSortQuery(Builder $queryBuilder, array $sortParams)
    {
        $sort = $sortParams['sort'];
        $direction = $sortParams['direction'];
        if ($sort === 'price' and $this->onlyOneAdType) {
            $queryBuilder->addSelect('account_ad_type.price');
            $queryBuilder->orderBy('account_ad_type.price', $direction);
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
                    $query->where('accounts.name', 'LIKE', "%{$searchQuery}%")
                        ->orWhere('users.name', 'LIKE', "%{$searchQuery}%");
                });
        }
        return $queryBuilder;
    }

    protected function prepareQueryBuilder(Builder $queryBuilder)
    {
        return $queryBuilder->distinct();
    }

    protected function postpareQueryBuilder(Builder $queryBuilder)
    {
        return $queryBuilder->addSelect(['accounts.*']);
    }
}
//
//    SELECT DISTINCT
//        `accounts`.*
//    FROM
//        `accounts`
//    JOIN `account_ad_type` ON `account_ad_type`.`account_id` = `accounts`.`id`
//    JOIN `account_topic` ON `account_topic`.`account_id` = `accounts`.`id`
//    WHERE
//        `account_ad_type`.`price` > 2000 AND `account_ad_type`.`price` < 4000 AND `account_ad_type`.`ad_type_id` IN(2, 3, 4) AND `account_topic`.`topic_id` IN(1, 2, 4)
//
//    <-------------------------------------------------------------------------------------------->
//
//    Если выбран один вид рекламы и сортировка по цене
//
//    SELECT DISTINCT
//        `accounts`.*,
//        `account_ad_type`.`price`
//    FROM
//        `accounts`
//    JOIN `account_ad_type` ON `account_ad_type`.`account_id` = `accounts`.`id`
//    JOIN `account_topic` ON `account_topic`.`account_id` = `accounts`.`id`
//    WHERE
//        `account_ad_type`.`price` > 2000 AND `account_ad_type`.`price` < 4000 AND `account_ad_type`.`ad_type_id` = 2 AND `account_topic`.`topic_id` IN(1, 2, 4)
//    ORDER BY
//        `account_ad_type`.`price` ASC
//
