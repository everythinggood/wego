<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/17/18
 * Time: 2:08 PM
 */

namespace Handler\WX;


use Contract\Event;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\OfficialAccount\Application;
use Handler\Entity\ReceivePaperTemplateMessageInterFace;

class EventMessageHandler implements EventHandlerInterface
{
    /**
     * @var Application
     */
    private $app;
    /**
     * @var TemplateMessageHandler
     */
    private $templateMessageHandler;

    /**
     * EventMessageHandler constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->templateMessageHandler = new TemplateMessageHandler();
        $this->templateMessageHandler->setApplication($app);
    }

    /**
     * @param null $message
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function handle($message = null)
    {
        //发送方微信号（openid 用户唯一标识）
        $userOpenId = $message['FromUserName'];
        //接收方微信号（公众好ID）
//        $toUser = $message['ToUserName'];

        //订阅事件
        if($message['Event'] == Event::SUBSCRIBE){
            $this->app->logger->addInfo("get subscribe event",$message);
            $this->sendMessage($userOpenId);
            return ;
        }
        //扫描带参数二维码事件
        if(array_key_exists('Ticket',$message)){

            $this->app->logger->addInfo("get scene qrcode event",$message);

//            $scene = str_replace('qrscene','',$message['EventKey']);

            $this->sendMessage($userOpenId);
            return ;
        }

    }


    /**
     * @param $userOpenId
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    private function sendMessage($userOpenId){

        $receivePaperMessage = new ReceivePaperTemplateMessageInterFace();
        $receivePaperMessage->setTitleField("纸巾已经到达出纸巾口，请注意查看！");
        $receivePaperMessage->setProductField("维达纸巾一包");
        $receivePaperMessage->setTimeField();
        $receivePaperMessage->setRemarkField("如纸巾有问题或没有领取到，请电话联系400-001-222");

        if(!$this->templateMessageHandler->handle($userOpenId,$receivePaperMessage)){
            $this->sendPaperText($userOpenId);
        }
    }

    /**
     * @param $userOpenId
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    private function sendPaperText($userOpenId){
        $text = new Text("请点击【<a href=\"http://m.zhiwei99.com/addon/YiKaTong/GuanzhuGzh/up?state=1016\">【免费领取纸巾】</a>]");

        $result = $this->app->customer_service->message($text)->to($userOpenId)->send();

        $this->app->logger->addInfo("customer service send text message to user",$result);

    }

}