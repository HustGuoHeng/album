<?php
namespace App\Http\Services\Upload;

use App\Http\Entity\Upload\ImageEntity;
use Illuminate\Support\Facades\DB;
use App\Http\Dao\UserInfo;
use App\Http\Exception\UserException;
use App\Http\Models\VirtualDiskModel;


class ImageService
{
    public static function saveInfo(ImageEntity $image)
    {
        DB::beginTransaction();
        try {
            UserInfo::cutAvailableSpace($image->getUserId(), $image->getImageFile()->getSize());
            $availableSpace = UserInfo::getAvailableSpace($image->getUserId());
            if ($availableSpace <= 0) {
                throw new UserException('用户空间不足'.$availableSpace);
            }
            self::saveInfoToDataBase($image);
            self::saveInfoToDisk($image);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }
    private static function saveInfoToDataBase(ImageEntity $image)
    {
        $model            = new VirtualDiskModel();
        $model->parent_id = $image->getParentId();
        $model->user_id   = $image->getUserId();
        $model->name      = $image->getName();
        $model->save_name = $image->getSaveName();
        $model->type      = $image->getType();
        $model->thumbnail = $image->getThumbnail();
        $model->path      = $image->getPath();
        $model->size      = $image->getImageFile()->getSize();
        $status           = $model->save();
        if (!$status) {
            throw new \Exception('数据库添加用户数据失败');
        }
    }

    private function saveInfoToDisk(ImageEntity $image)
    {
        $image->move();
    }

}