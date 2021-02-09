<?php


namespace App\Handlers;


class QueryHandler
{
    protected $allowedFilters;
    protected $allowedSorts;

    protected function extractFilters(array $params): array
    {
        $extracted = [];
        foreach ($this->allowedFilters as $allowedFilter => $type) {
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
        if (isset($params['sort']) and in_array($params['sort'], $this->allowedSorts)) {
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
}
