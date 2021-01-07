<?php


namespace App\Traits;


trait CanWrapInData
{
    protected function wrapInData($rawData)
    {
        return [
            'data' => $rawData,
        ];
    }
}
