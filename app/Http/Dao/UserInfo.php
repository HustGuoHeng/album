<?php
namespace App\Http\Dao;

use App\Http\Models\UserInfoModel;

class UserInfo
{
    public static function getAvailableSpace($userId)
    {
        $model          = new UserInfoModel();
        $spaceInfo      = $model->find($userId);
        $availableSpace = $spaceInfo['total'] - $spaceInfo['used'];
        return $availableSpace > 0 ? intval($availableSpace) : 0;
    }


    public static function cutAvailableSpace($userId, $space)
    {
        $model  = new UserInfoModel();

        $model->where('id', $userId)->increment('used', intval($space));
    }
}