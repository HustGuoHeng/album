<?php
namespace App\Http\Services\Upload;

use App\Http\Entity\Upload\DirEntity;
use App\Http\Models\VirtualDiskModel;

class DirService
{
    public static function saveDir(DirEntity $dir)
    {
        self::saveInfoToDatabase($dir);
    }

    private static function saveInfoToDatabase(DirEntity $dir)
    {
        $model            = new VirtualDiskModel();
        $model->parent_id = $dir->getParentId();
        $model->user_id   = $dir->getUserId();
        $model->name      = $dir->getName();
        $model->save_name = '';
        $model->type      = $dir->getType();
        $model->thumbnail = $dir->getThumbnail();
        $model->path      = '';
        $model->size      = 0;
        $status           = $model->save();
        if (!$status) {
            throw new \Exception('数据库添加用户数据失败');
        }
    }
}