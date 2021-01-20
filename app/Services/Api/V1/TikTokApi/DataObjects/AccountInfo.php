<?php


namespace App\Services\Api\V1\TikTokApi\DataObjects;


class AccountInfo
{
    public $followers;
    public $likes;

    public function __construct($followers, $likes)
    {
        $this->followers = $followers;
        $this->likes = $likes;
    }

    public static function make(...$args): self
    {
        return new self(...$args);
    }
}
