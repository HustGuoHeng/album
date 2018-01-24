<?php
namespace App\Http\Services\WeChat\Response;

use App\Http\Services\WeChat\Entity\Account;
use App\Http\Services\WeChat\Libraries\Input;
use App\Http\Services\WeChat\Sdk\WXBizMsgCrypt;
use Illuminate\Support\Facades\Log;

trait ModeTrait
{
    protected $aes = false;

    protected $encryptMsg = '';

    /**
     * @var Account
     */
    protected $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    abstract function reply();

    public function response()
    {
        $replyMsg = $this->reply();
        if (Input::isSafeMode()) {
            $crypt          = new WXBizMsgCrypt(
                $this->account->getToken(),
                $this->account->getEncodingAesKey(),
                $this->account->getAppId());
            $encryptMsg     = '';
            $encryptErrCode = $crypt->encryptMsg($replyMsg, $_GET['timestamp'], $_GET['nonce'], $encryptMsg);
            if ($encryptErrCode == 0) {
                $replyMsg = $encryptMsg;
            } else {
                Log::info($this->account->getOriginalId() . '消息加密错误');
            }
        }
        return $replyMsg;
    }


}