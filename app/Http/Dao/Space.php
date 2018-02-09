<?php
namespace App\Http\Dao;

use App\Http\Models\SpaceModel;

class Space
{
    public static function getAvailableSpace($userId)
    {
        $model          = new SpaceModel();
        $spaceInfo      = $model->where('user_id', $userId)
            ->find(1);
        $availableSpace = $spaceInfo['total'] - $spaceInfo['used'];
        return $availableSpace > 0 ? intval($availableSpace) : 0;
    }


    public static function cutAvailableSpace($userId, $space)
    {
        $model  = new SpaceModel();

        $model->where('user_id', $userId)->increment('used', intval($space));
    }
}