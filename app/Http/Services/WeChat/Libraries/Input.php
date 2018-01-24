<?php
namespace App\Http\Services\WeChat\Libraries;

use App\Http\Services\WeChat\Entity\Account;
use App\Http\Services\WeChat\Sdk\Crypt\WXBizMsgCrypt;
use Illuminate\Support\Facades\Log;

class Input
{
    /**
     * @var array
     */
    protected static $inputInfo;


    public static function getInputInfo()
    {
        if (!self::$inputInfo) {
            $postStr         = self::getPostString();
            self::$inputInfo = self::handleInputInfo($postStr);
        }
        return self::$inputInfo;
    }

    public static function getInputOriginalId()
    {
        $info = self::getInputInfo();
        Log::info($info);
        return isset($info['ToUserName']) ? $info['ToUserName'] : '';
    }

    public static function getInputOpenId()
    {
        $info = self::getInputInfo();
        return isset($info['FromUserName']) ? $info['FromUserName'] : '';
    }


    /**
     * 判断是否是安全模式
     * @return bool
     */
    public static function isSafeMode()
    {
        if (self::getEncryptType() !== null) {
            return true;
        }
        return false;
    }

    /**
     * 获取get中的encrypt_type
     * @return null
     */
    public static function getEncryptType()
    {
        if (isset($_GET['encrypt_type'])) {
            return $_GET['encrypt_type'];
        }
        return null;
    }

    public static function getTimeStamp()
    {
        return $_GET['timestamp'];
    }

    public static function getNonce()
    {
        return $_GET['nonce'];
    }

    public static function getMsgSignature()
    {
        return $_GET['msg_signature'];
    }

    /**
     * @return
     */
    protected static function getPostString()
    {
        return file_get_contents("php://input");
    }


    protected static function getObjFromPostString($postStr)
    {
        return simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    protected static function handleInputInfo($postStr)
    {
        $result                 = array();
        $postObj                = self::getPostObj($postStr);
        $result['MsgType']      = $postObj->MsgType;
        $result['FromUserName'] = XMLHelper::SimpleXMLObjectToString($postObj->FromUserName);
        //文本消息
        if ($result['MsgType'] == 'text') {
            $result['Content'] = trim($postObj->Content);
        } else {
            $result['Content'] = '';
        }
        //事件
        if ($result['MsgType'] == 'event') {
            $result['Event'] = $postObj->Event;
        } else {
            $result['Event'] = '';
        }
        //增加CLICK事件触发
        if ($result['Event'] == 'CLICK') {
            $result['EventKey'] = $postObj->EventKey;
        } else {
            $result['EventKey'] = '';
        }
        return $result;
    }

    protected static function getPostObj($postStr)
    {
        $postObj              = self::getObjFromPostString($postStr);
        $result['ToUserName'] = XMLHelper::SimpleXMLObjectToString($postObj->ToUserName);

        $account = new Account($result['ToUserName']);
        if (self::isSafeMode()) {
            $crypt     = new WXBizMsgCrypt($account->getToken(), $account->getEncodingAesKey(), $account->getAppId());
            $msg       = '';
            $errorCode = $crypt->decryptMsg(self::getMsgSignature(), self::getTimeStamp(), self::getNonce(), $postStr, $msg);
            if ($errorCode == 0) {
                $postStr = $msg;
                $postObj = self::getObjFromPostString($postStr);
            } else {
                Log::info('加密信息解密错误');
                die();
            }
        }
        return $postObj;
    }
}