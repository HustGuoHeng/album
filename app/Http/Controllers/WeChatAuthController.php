<?php
namespace App\Http\Controllers;

use App\Http\Models\WeChatUserInfoModel;
use App\Http\Services\WeChat\Entity\Account;
use App\Http\Services\WeChat\Sdk\Auth\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;


class WeChatAuthController extends Controller
{
    protected $originalId = 'gh_210049cac7b7';

    public function index(Request $request)
    {
        $code  = $request->input('code');
        $state = $request->input('state');

        //从微信获取网页授权access_token、openid、refresh_token并存储
        $account        = new Account($this->originalId);
        $weChatUserInfo = new UserInfo($account);
        $simpleInfo     = $weChatUserInfo->getAuthAccessToken($code);
        echo "<pre>";
        var_dump($simpleInfo);
        $weChatUserInfo->storeAuthAccessToken($simpleInfo['access_token']);
        $weChatUserInfo->storeRefreshToken($simpleInfo['refresh_token']);

        $openId = $simpleInfo['openid'];

        //判断用户是否存在
        $userExists = WeChatUserInfoModel::where('open_id', $openId)
            ->where('original_id', $account->getOriginalId())->get()->toArray();
        var_dump($userExists);
        if (!$userExists) {
            $info               = $weChatUserInfo->getUserInfoByToken($simpleInfo['access_token'], $openId);
            var_dump($info);
            $model              = new WeChatUserInfoModel();
            $model->open_id     = $openId;
            $model->original_id = $account->getOriginalId();
            $model->status      = 0;
            $model->nickname    = isset($info['nickname']) ? $info['nickname'] : '';
            $model->sex         = isset($info['sex']) ? $info['sex'] : 0;
            $model->province    = isset($info['province']) ? $info['province'] : '';
            $model->city        = isset($info['city']) ? $info['city'] : '';
            $model->country     = isset($info['country']) ? $info['country'] : '';
            $model->headimgurl  = isset($info['headimgurl']) ? $info['headimgurl'] : '';
            $model->unionid     = isset($info['unionid']) ? $info['unionid'] : '';
            $saveResult = $model->save();
            $saveStatus = $saveResult ? true : false;
        } else {
            var_dump($userExists);
        }

//        $a = Redis::get('a');
//        echo $a;
//         $redis = new \Redis();
//        $redis->connect('r-bp1f199f80480c64.redis.rds.aliyuncs.com', 6379);
//        $redis->auth('Hl6268695');

    }

}