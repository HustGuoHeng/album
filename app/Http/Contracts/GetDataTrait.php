<?php
namespace App\Contracts;

trait GetDataTrait
{
    /**
     * @var \Redis
     */
    private $redisInstance;

    /**
     * 缓存有效期，默认10分钟
     * @var int
     */
    private $redisExpireTime = 60 * 10;

    /**
     * 是否将空的源数据存储进redis中
     * @var bool
     */
    private $saveEmptyDataInRedis = true;

    /**
     * 设置空的数据在缓存中的占位符
     * @var string
     */
    private $emptyRedisPlaceholder = 'null';

    public function init($redis, $saveEmptyDataInRedis = true)
    {
        $this->setRedisInstance($redis);
        $this->setIsSaveEmptyRedisData($saveEmptyDataInRedis);
    }

    /**
     * 总的获取数据的方法
     * @return array|mixed|null|string
     */
    public function execute()
    {
        $redis     = $this->getRedisInstance();
        $redisData = $this->getDataFromRedis($redis);
        //如果数据为占位符数据，则不存在数据，返回空
        if ($this->checkDataIsRedisPlaceholder($redisData)) {
            return null;
        }

        //如果数据符合规范，则将数据吐出
        $redisDataStatus = $this->checkSourceData($redisData);
        if ($redisDataStatus) {
            return $redisData;
        }

        //从缓存中拿不到数据时，则考虑从源头获取数据
        $data = $this->getSourceData();
        //如果拿到的数据符合规范，则直接返回
        if ($this->checkSourceData($data)) {
            $this->saveSourceData($redis, $data);
            return $data;
        } elseif ($this->saveEmptyDataInRedis) {
            // 如果数据不符合规范，则选择性的将占位符存入到 redis 中
            $data = $this->emptyRedisPlaceholder;
            $this->saveSourceDataToRedis($redis, $data);
        }

        return null;
    }

    /**
     * 获取源数据
     * @return array
     */
    abstract public function getSourceData();

    /**
     * 检测源数据是否合法
     * @param $data
     * @return boolean
     */
    abstract public function checkSourceData($data);


    /**
     * 将数据存入到redis中
     * @return string
     */
    abstract public function getRedisKey();


    /**
     * 检查 redis 中的数据是否为占位符
     * @param $data
     * @return bool
     */
    public function checkDataIsRedisPlaceholder($data)
    {
        if ($this->saveEmptyDataInRedis) {
            if ($data == $this->emptyRedisPlaceholder) {
                return true;
            }
        }
        return false;
    }

    /**
     * 从 redis 中获取数据，默认方法为get，若数据格式有变，可重写该方法
     * @param $redis
     * @return mixed
     */
    public function getDataFromRedis($redis)
    {
        $key  = $this->getRedisKey();
        $data = $redis->get($key);
        return json_decode($data, true);
    }

    /**
     * @param array|string $data 可以直接返回给用户使用的数据
     */
    public function saveSourceDataToRedis($redis, $data)
    {
        $key        = $this->getRedisKey();
        $expireTime = $this->getRedisExpireTime();
        $redis->setex($key, $expireTime, json_encode($data));
    }

    public function saveSourceData($redis, $data)
    {
        $this->saveSourceDataToRedis($redis, $data);
        $this->saveSourceDataToOtherPlace($data);
    }

    /**
     * 预留的存储数据的钩子
     * @param $data
     */
    public function saveSourceDataToOtherPlace($data)
    {

    }
    /**
     * 获取 redis 实例
     * @return \Redis
     */
    public function getRedisInstance()
    {
        return $this->redisInstance;
    }

    /**
     * 设置使用的 redis
     * @param $redis
     */
    public function setRedisInstance($redis)
    {
        $this->redisInstance = $redis;
    }

    /**
     * 获取 redis 缓存失效时间
     * @return int
     */
    public function getRedisExpireTime()
    {
        return $this->redisExpireTime;
    }

    /**
     * 设置缓存失效时间
     * @param $time
     */
    public function setRedisExpireTime($time)
    {
        $time                  = intval($time);
        $this->redisExpireTime = $time > 0 ? $time : $this->redisExpireTime;
    }

    /**
     * 设置是否缓存空数据
     * @param $boolean
     */
    public function setIsSaveEmptyRedisData($boolean)
    {
        if ($boolean) {
            $this->saveEmptyDataInRedis = true;
        } else {
            $this->saveEmptyDataInRedis = false;
        }
    }

    /**
     * 获取空数据的占位符
     * @return string
     */
    public function getEmptyRedisPlaceholder()
    {
        return $this->emptyRedisPlaceholder;
    }
}