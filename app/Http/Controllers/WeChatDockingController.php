<?php

namespace App\Http\Controllers;

use App\Http\Services\WeChat\Libraries\Input;
use App\Http\Services\WeChat\Entity\Account;
use App\Http\Services\WeChat\Response\Text;
use Illuminate\Http\Request;

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
        $originalId = Input::getInputOriginalId();
        $openId     = Input::getInputOpenId();

        $text = new Text(new Account($originalId));
        echo $text->to($openId)->content('hello world')->response();
        exit();
    }


}
