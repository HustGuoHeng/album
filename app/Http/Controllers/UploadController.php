<?php
namespace App\Http\Controllers;

use App\Http\Entity\Upload\Image;
use App\Http\Exception\ImageUploadException;
use App\Http\Exception\UserException;
use App\Http\Models\VirtualDiskModel;
use App\Http\Dao\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

class UploadController extends Controller
{
    public function image(Request $request)
    {
        $callback = $request->input('callback');
        try {
            $this->uploadImage($request);
        } catch (UserException $e) {
            return response()->jsonp($callback, [
                'status' => 1,
                'msg'    => $e->getMessage()
            ]);
        } catch (ImageUploadException $e) {
            return response()->jsonp($callback, [
                'status' => 2,
                'msg'    => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return response()->jsonp($callback, [
                'status' => 0,
                'msg'    => $e->getMessage()
            ]);
        }

        return response()->jsonp($callback, [
            'status' => 1,
            'msg'    => '上传成功'
        ]);
    }

    private function uploadImage(Request $request)
    {
        $this->auth($request);
        $image = $this->getImageEntity('image', $request);
        $this->saveImageInfo($image);
    }

    private function saveImageInfo(Image $image)
    {
        DB::beginTransaction();
        try {
            Space::cutAvailableSpace($image->getUserId(), $image->getSize());
            $availableSpace = Space::getAvailableSpace($image->getUserId());
            if ($availableSpace <= 0) {
                throw new UserException('用户空间不足');
            }
            $this->saveImageInfoToDataBase($image);
            $this->saveImageInfoToDisk($image);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }


    private function saveImageInfoToDataBase(Image $image)
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
            throw new Exception('数据库添加用户数据失败');
        }
    }

    private function saveImageInfoToDisk(Image $image)
    {
        $image->move();
    }


    private function getUserId(Request $request)
    {
        $userId = $request->session()->get('userId', 0);
        return $userId;
    }

    private function auth(Request $request)
    {
        $userId = $this->getUserId($request);
        if (!$userId) {
            throw new UserException('异常用户');
        }
    }

    private function getImageEntity($file, Request $request)
    {
        $image = new Image($request->file($file),
            $request->input('name'),
            $request->input('parentId'),
            $this->getUserId($request)
        );
        return $image;
    }


}