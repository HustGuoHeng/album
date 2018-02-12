<?php
namespace App\Http\Controllers;

use App\Http\Models\UserInfoModel;
use App\Http\Models\VirtualDiskModel;
use App\Http\Models\WeChatUserInfoModel;
use App\Http\Services\WeChat\Entity\Account;
use App\Http\Services\WeChat\Sdk\Auth\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\Exception;


class WeChatAuthController extends Controller
{
    protected $originalId = 'gh_210049cac7b7';

    public function index(Request $request)
    {
        try {
            $code  = $request->input('code');
            $state = $request->input('state');

            //从微信获取网页授权access_token、openid、refresh_token并存储
            $account        = new Account($this->originalId);
            $weChatUserInfo = new UserInfo($account);
            $simpleInfo     = $weChatUserInfo->getAuthAccessToken($code);
            $weChatUserInfo->storeAuthAccessToken($simpleInfo['access_token']);
            $weChatUserInfo->storeRefreshToken($simpleInfo['refresh_token']);

            $openId = $simpleInfo['openid'];

            //判断用户是否存在
            $dbUserInfo = WeChatUserInfoModel::where('open_id', $openId)
                ->where('original_id', $account->getOriginalId())->get()->toArray();
            if (!$dbUserInfo) {
                $info       = $weChatUserInfo->getUserInfoByToken($simpleInfo['access_token'], $openId);
                $dbUserInfo = $this->createAndGetWeChatUser($openId, $account, $info);
            } else if (empty($dbUserInfo['nickname'])) {
                $info = $weChatUserInfo->getUserInfoByToken($simpleInfo['access_token'], $openId);
                $this->updateWeChatInfo($openId, $account, $info);
                $dbUserInfo = array_merge($dbUserInfo, $info);
            }
        } catch (\Exception $e) {
            echo '页面不见啦……';
            exit();
        }

        $userId = $dbUserInfo['id'];
        session(['userId' => $userId]);

        $name = $dbUserInfo['nickname'];

        $parentId = 0;
        $data     = $this->getDisplayFiles($userId, $parentId);

        return view('album/index', compact('data', 'name', 'userId'));
    }

    public function path(Request $request, $id)
    {
        $userId  = $request->session()->get('userId', 0);
        $data    = $this->getDisplayFiles($userId, $id);
        $dirInfo = $this->getDisplayDirInfo($userId, $id);
        return view('album/path', compact('userId', 'data', 'dirInfo'));
    }

    private function getDisplayFiles($userId, $parentId)
    {
        $model = new VirtualDiskModel();
        $data  = $model->where('user_id', $userId)
            ->where('parent_id', $parentId)
            ->get()->toArray();
        return $data;
    }

    private function getDisplayDirInfo($userId, $id)
    {
        $model = new VirtualDiskModel();
        $data  = $model->where('user_id', $userId)->find($id)->toArray();
        return $data;
    }

    private function createAndGetWeChatUser($openId, Account $account, $info)
    {
        DB::beginTransaction();
        try {
            $dbUserInfo = $this->createAndReturnNewWeChatUser($openId, $account, $info);
            if (!Storage::disk('upload')->exists($dbUserInfo['id'])) {
                Storage::disk('upload')->makeDirectory($dbUserInfo['id'], 777);
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
        return $dbUserInfo ? $dbUserInfo : [];
    }

    public function createAndReturnNewWeChatUser($openId, Account $account, $info)
    {
        $model              = new WeChatUserInfoModel();
        $model->open_id     = $openId;
        $model->original_id = $account->getOriginalId();
        $model->status      = 0;
        $model->nickname    = $info['nickname'];
        $model->sex         = isset($info['sex']) ? $info['sex'] : 0;
        $model->province    = isset($info['province']) ? $info['province'] : '';
        $model->city        = isset($info['city']) ? $info['city'] : '';
        $model->country     = isset($info['country']) ? $info['country'] : '';
        $model->headimgurl  = isset($info['headimgurl']) ? $info['headimgurl'] : '';
        $model->unionid     = isset($info['unionid']) ? $info['unionid'] : '';
        $model->user_id     = $this->createAndGetWeChatUserId();
        $model->save();
        return $model->toArray();
    }

    private function createAndGetWeChatUserId()
    {
        $model            = new UserInfoModel();
        $model->user_type = 1;
        $model->used      = 0;
        $model->total     = 1024 * 1024 * 1014 * 1;//暂定1G
        $model->save();
        return $model->id;
    }

    private function updateWeChatInfo($openId, Account $account, $info)
    {
        WeChatUserInfoModel::where('open_id', $openId)
            ->where('original_id', $account->getOriginalId())
            ->update([
                'nickname'   => $info['nickname'],
                'sex'        => isset($info['sex']) ? $info['sex'] : 0,
                'province'   => isset($info['province']) ? $info['province'] : '',
                'city'       => isset($info['city']) ? $info['city'] : '',
                'country'    => isset($info['country']) ? $info['country'] : '',
                'headimgurl' => isset($info['headimgurl']) ? $info['headimgurl'] : '',
                'unionid'    => isset($info['unionid']) ? $info['unionid'] : ''
            ]);
    }
}