<?php

namespace App\Http\Controllers;

use App\Http\Libraries\XMLHelper;
use App\Http\Services\WeChat\Entity\Account;
use App\Http\Services\WeChat\Response\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeChatDockingController extends Controller
{
    public $token = "HelloWorld";

    public function index(Request $request)
    {
        if (isset($_GET['echostr']) && isset($_GET['nonce']) && isset($_GET['timestamp']) && isset($_GET['signature'])) {
            $this->valid($request->input('echostr'));
        } else {
            $this->responseMsg();
        }
    }

    //第一次验证地址
    public function valid($echo)
    {
        if ($this->checkSignature()) {
            echo $echo;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];

        $token  = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function responseMsg()
    {
        echo 'a';
        $postStr        = file_get_contents("php://input");
        $postStr        = '<xml>
    <ToUserName><![CDATA[gh_ed6a859a29c6]]></ToUserName>
    <Encrypt><![CDATA[jX6rnwkHfKIouIAQFbvw67nuNLOlc1X9P1pqyMomCfs77wTUZPSaLMQJAiuVMpE/2tGNK2mKF1GiOOcyKpw/9YyTr+56gQIYXbrSJ0PphyAF1cStlH+kquhTUunZNjHoi5sNt40h2zA0ptVQf/Il5DB0ddtGDsnLjFcajL44Zl1o/87IlHe7savjKfadl06u053YW65t3JwWcMRbXlJjVtrAaisYNQJZ77t+925BFNgIXQ24Ygh1tS2dE560Fkq5OoxBk7cds/LicDu03Qx0a3F8dqvXrpFNO27fl1vFNeCE7XneqRYi7qbidxfcW3JVxWulF7LHK3V9SAlzfjOaFRXfrUKHftCyYoUMArj9bDNYrDZHzhgrLSa/S1At2JarvwlRluSfuL4Jx288HPSnaMCpWUtYUQ3gU5zLRaJZrqA=]]></Encrypt>
</xml>';
        $weChatPostInfo = $this->handleWeChatPostStr($postStr);
        $openId         = $weChatPostInfo['FromUserName'];
        $originalId     = $weChatPostInfo['ToUserName'];

        $account = new Account(XMLHelper::SimpleXMLObjectToString($originalId));

        $text = new Text($account);
        echo $text->to($openId)->content('hello world')->response();
        exit();
    }

    protected function handleWeChatPostStr($postStr)
    {
        $result                 = array();
        echo 'a';
        var_dump($postStr);
        $postObj                = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $result['MsgType']      = $postObj->MsgType;
        $result['FromUserName'] = $postObj->FromUserName;
        $result['ToUserName']   = $postObj->ToUserName;
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


}
