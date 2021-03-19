<?php


namespace App\Services\Api\V1\TikTokApi;


use App\Services\Api\V1\TikTokApi\DataObjects\AccountInfo;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Support\Facades\Http;
use Sovit\TikTok\Api as TikTokApi;

class TikTokApiManager
{
    const START_SEPARATOR = '<script id="__NEXT_DATA__" type="application/json" crossorigin="anonymous">';
    const END_SEPARATOR = '</script>';

    /**
     * @param string $name
     * @return AccountInfo|null
     */
    public function loadAccountInfo(string $name)
    {
        $api = new TikTokApi();
        $userInfo = $api->getUser(ltrim($name, '@'));
        if ($userInfo and isset($userInfo->stats->followerCount) and isset($userInfo->stats->heartCount)) {
            $followers = $userInfo->stats->followerCount;
            $likes = $userInfo->stats->heartCount;
            return AccountInfo::make($followers, $likes);
        }
        return $this->argumentError();
    }

    protected function argumentError($msg = '')
    {
        //
        return null;
    }
}
