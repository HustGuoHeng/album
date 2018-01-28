<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class WeChatUserInfoModel extends Model
{
    protected $table = 'wechat_user_info';

    public function addUserInfo($openId, $originalId)
    {
        $model              = new WeChatUserInfoModel();
        $model->open_id     = $openId;
        $model->original_id = $originalId;
        $model->status      = 0;
        $status             = $model->save();
        return $status;
    }
}
