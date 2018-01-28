<?php
namespace App\Http\Services\WeChat\Sdk\Auth;

use App\Http\Services\WeChat\Entity\Account;
use Illuminate\Support\Facades\Cookie;
use Curl\Curl;

class UserInfo
{
    private $code;

    private $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function getAuthAccessToken($code)
    {
        $appId     = $this->account->getAppId();
        $appSecret = $this->account->getAppSecret();
        $url       = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appId .
            "&secret=" . $appSecret . "&code=" . $code . "&grant_type=authorization_code";

        $curl = new Curl();
        $curl->get($url);
        $response = $curl->response;
        $response = json_decode($response);
        return $response;
    }

    public function getUserInfoByToken($authAccessToken, $openId)
    {
        $url  = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $authAccessToken .
            '&openid=' . $openId . '&lang=zh_CN';
        $curl = new Curl();
        $curl->get($url);
        $response = $curl->response;
        $response = json_decode($response);
        return $response;
    }

    public function storeAuthAccessToken($token)
    {
        Cookie::queue('authAccessToken', $token, 7000);
    }

    public function getStoreAuthAccessToken()
    {
        return Cookie::get('authAccessToken');
    }

    public function storeRefreshToken($token)
    {
        Cookie::queue('authRefreshToken', $token, 60 * 60 * 24 * 30 - 120);
    }

    public function getStoreRefreshToken()
    {
        return Cookie::get('authRefreshToken');
    }
}