<?php


namespace App\Services\Api\V1\TikTokApi;


use App\Services\Api\V1\TikTokApi\DataObjects\AccountInfo;
use Illuminate\Support\Facades\Http;

class TikTokApiManager
{
    /**
     * @param string $name
     * @return AccountInfo|null
     */
    public function loadAccountInfo(string $name)
    {
        $name = urlencode($name);
        $response = Http::get("https://www.tiktok.com/node/share/user/{$name}?user_agent=");
        if ($response->ok()) {
            $json = $response->json();
            if ($json and isset($json['userInfo'])) {
                $userInfo = $json['userInfo'];
                if ($userInfo and isset($userInfo['stats']['followerCount']) and isset($userInfo['stats']['heartCount'])) {
                    $followers = $userInfo['stats']['followerCount'];
                    $likes = $userInfo['stats']['heartCount'];
                    return AccountInfo::make($followers, $likes);
                }
                return $this->argumentError();
            }
        }
        return $this->apiError();
    }

    protected function apiError($msg = '')
    {
        //
        return null;
    }

    protected function argumentError($msg = '')
    {
        //
        return null;
    }
}
