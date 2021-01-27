<?php


namespace App\Services\Api\V1\AdTypes\Transformer;


class AdTypesTransformer
{
    public static function transform(array $ad_types): array
    {
        $out = [];
        foreach ($ad_types as $ad_type) {
            if (isset($ad_type['id'])) {
                if (isset($ad_type['price'])) {
                    $out[$ad_type['id']] = [
                        'price' => intval($ad_type['price']),
                    ];
                } else {
                    $out[] = intval($ad_type['id']);
                }
            } else {
                throw new \InvalidArgumentException('Incorrect argument format', 500);
            }
        }
        return $out;
    }
}
