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
        $postStr        = file_get_contents("php://input");
        $weChatPostInfo = $this->handleWeChatPostStr($postStr);
        $openId         = $weChatPostInfo['FromUserName'];
        $originalId     = $weChatPostInfo['ToUserName'];

        $account        = new Account($originalId);

        $text = new Text($account);
        $response = $text->to($openId)->content('hello world')->response();
        return response($response);
    }

    protected function handleWeChatPostStr($postStr)
    {
        $result                 = array();
        $postObj                = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $result['MsgType']      = $postObj->MsgType;
        $result['FromUserName'] = XMLHelper::SimpleXMLObjectToString($postObj->FromUserName);
        $result['ToUserName']   = XMLHelper::SimpleXMLObjectToString($postObj->ToUserName);
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
