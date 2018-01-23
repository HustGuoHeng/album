<?php
namespace App\Http\Services\WeChat\Response;

class Text
{
    use ModeTrait;
    /**
     * @var string
     */
    protected $toUserName;

    /**
     * @var string
     */
    protected $content;


    /**
     * @var string
     */
    protected $standerResponse = <<<eot
                    <xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>%d</FuncFlag>
                    </xml>
eot;


    public function to($name)
    {
        $this->toUserName = $name;
        return $this;
    }

    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    public function reply()
    {
        $fromUserName = $this->account->getOriginalId();
        $toUserName   = $this->toUserName;
        $content      = $this->content;

        $replyMsg = sprintf($this->standerResponse, $toUserName, $fromUserName, time(), 'text', $content, 0);
        return $replyMsg;
    }

}