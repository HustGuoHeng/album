<?php
namespace App\Http\Services\WeChat\Libraries;

use App\Http\Services\WeChat\Entity\Account;
use App\Http\Services\WeChat\Sdk\WXBizMsgCrypt;
use Illuminate\Support\Facades\Log;

class Input
{
    /**
     * @var Account
     */
    protected static $account;


    /**
     * 判断是否是安全模式
     * @return bool
     */
    public static function isSafeMode()
    {
        if (isset($_GET['encrypt_type']) && $_GET['encrypt_type'] == 'aes') {
            return true;
        }
        return false;
    }

    /**
     * @return
     */
    public static function getInput()
    {
        return file_get_contents("php://input");
    }


    public function handleInputInfo($postStr)
    {
        $result               = array();
        $postObj              = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $result['ToUserName'] = XMLHelper::SimpleXMLObjectToString($postObj->ToUserName);

        self::setAccount($result['ToUserName']);

        if (self::isSafeMode()) {
            $crypt = new WXBizMsgCrypt(self::$account->getToken(), self::$account->getEncodingAesKey(), self::$account->getAppId());
            $msg   = '';
            $errorCode = $crypt->decryptMsg($_GET['msg_signature'], $_GET['timestamp'], $_GET['nonce'], $postStr, $msg);
            if ($errorCode == 0) {
                $postStr = $msg;
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            } else {
                Log::info('加密信息解密错误');
                die();
            }
        }

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

    public static function setAccount($originalId)
    {
        if (!self::$account) {
            self::$account = new Account($originalId);
        }
    }

    public static function getAccount()
    {
        return self::$account;
    }
}