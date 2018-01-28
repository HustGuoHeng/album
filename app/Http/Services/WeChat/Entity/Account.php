<?php
namespace App\Http\Services\WeChat\Entity;

use Illuminate\Support\Facades\Log;

class Account
{
    public static $accounts;

    /**
     * @var array
     */
    protected $signalAccount;

    public function __construct($originalId)
    {
        $this->setAccounts();
        $this->init($originalId);
    }

    public function init($originalId)
    {
        $this->originalId    = $originalId;
        $this->signalAccount = self::$accounts[$originalId] ? self::$accounts[$originalId] : [];
        if (empty($this->signalAccount)) {
            Log::info('未能获取' . $originalId . '公众号信息！');
        }
    }

    public function setAccounts()
    {
        if (self::$accounts) {
            return true;
        }

        static::$accounts = [
            'gh_ed6a859a29c6' => [
                'appId'          => 'wxcb6a5e0cf35139ba',
                'appSecret'      => 'd3adcd2d988e3cd8a6ed08e627230697',
                'originalId'     => 'gh_ed6a859a29c6',
                'token'          => 'HelloWorld',
                'encodingAesKey' => '8vbJ6stgBT4dYB6YjbvfMXCe2qrSOMt48cLKnpsg5iR'
            ],
            'gh_210049cac7b7' => [
                'appId'      => 'wx9ab7c2957c9596f0',
                'appSecret'  => '0fc5d93883310be593c79b9274b4d158',
                'originalId' => 'gh_210049cac7b7'
            ]
        ];
        return true;
    }

    public function getAppId()
    {
        return $this->signalAccount['appId'];
    }

    public function getAppSecret()
    {
        return $this->signalAccount['appSecret'];
    }

    public function getToken()
    {
        return $this->signalAccount['token'];
    }

    public function getEncodingAesKey()
    {
        return $this->signalAccount['encodingAesKey'];
    }

    public function getOriginalId()
    {
        return $this->signalAccount['originalId'];
    }

}