<?php
namespace App\Http\Entity\Upload;

use App\Http\Exception\ImageUploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageEntity
{
    protected $parentId;

    protected $userId;

    protected $name;

    protected $saveName;

    protected $type = 2;

    protected $thumbnail = '';

    protected $size;

    protected $extension;

    /**
     * @var UploadedFile
     */
    protected $imageFile;

    public function __construct(\SplFileInfo $file, $newName, $userId, $parentId)
    {
        $this->setImageFile($file);
        $this->setUserId($userId);
        $this->setParentId($parentId);
        $this->setName($newName);
        $this->createThumbnail();
        $this->checkFileIsAllowUpload();
    }

    public function checkFileIsAllowUpload()
    {
        $this->checkImageSize();
        $this->checkImageExtension();
    }

    public function checkImageSize()
    {
        if ($this->imageFile->getSize() > 5 * 1024 * 1024) {
            throw new ImageUploadException('图片大小超过限制5M');
        }
    }

    public function checkImageExtension()
    {
        if (!in_array($this->imageFile->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new ImageUploadException('错误的图片格式');
        }
    }

    public function getParentId()
    {
        return $this->parentId;
    }

    public function setParentId($id)
    {
        $this->parentId = intval($id) > 0 ? intval($id) : 0;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($id)
    {
        $this->userId = intval($id);
    }

    public function createThumbnail()
    {
        $this->thumbnail = '';
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setImageFile($file)
    {
        $this->imageFile = $file;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getSaveName()
    {
        if (!$this->saveName) {
            $this->setSaveName();
        }
        return $this->saveName;
    }

    private function setSaveName()
    {
        $name           = $this->getName();
        $tempName       = $this->imageFile->getFilename();
        $this->saveName = md5($name . $tempName . microtime()) . '.' . $this->imageFile->getClientOriginalExtension();
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getPath()
    {
        return '/' . $this->userId;
    }


    public function move()
    {
        $this->imageFile->move(
            env('UPLOAD_IMAGE_SAVE_PATH') . $this->getPath(),
            $this->getSaveName()
        );
    }
}

