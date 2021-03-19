<?php


namespace App\Services\Api\V1\TikTokApi;


use App\Services\Api\V1\TikTokApi\DataObjects\AccountInfo;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Support\Facades\Http;

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
        $response = $this->requestInfo($name);
        if ($response->ok()) {
            $userInfo = $this->parseUserInfo($response);
            if ($userInfo and isset($userInfo['stats']['followerCount']) and isset($userInfo['stats']['heartCount'])) {
                $followers = $userInfo['stats']['followerCount'];
                $likes = $userInfo['stats']['heartCount'];
                return AccountInfo::make($followers, $likes);
            }
            return $this->argumentError();
        }
        return $this->apiError();
    }

    protected function requestInfo($name)
    {
        return Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.83 YaBrowser/20.9.0.928 Yowser/2.5 Safari/537.36',
        ])
            ->get("https://www.tiktok.com/{$name}?user_agent=");
    }

    protected function parseUserInfo(HttpResponse $response)
    {
        $body = $response->body();
        $props = $this->getStringBetween($body, self::START_SEPARATOR, self::END_SEPARATOR);
        $json = json_decode($props, true);
        if ($json and isset($json['props']['pageProps']['userInfo'])) {
            return $json['props']['pageProps']['userInfo'];
        }
        return null;
    }

    protected function getStringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
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
