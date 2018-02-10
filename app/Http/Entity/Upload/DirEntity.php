<?php
namespace App\Http\Entity\Upload;

class DirEntity
{
    protected $parentId;

    protected $userId;

    protected $name;

    protected $type = 1;

    protected $thumbnail = '';

    protected $size;

    public function __construct($name, $userId, $parentId)
    {
        $this->setUserId($userId);
        $this->setName($name);
        $this->setParentId($parentId);
        $this->setThumbnail('');
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

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getType()
    {
        return $this->type;
    }
}