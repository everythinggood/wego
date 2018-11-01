<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 6/13/18
 * Time: 11:28 AM
 */

namespace Handler\WX;

use EasyWeChat\OfficialAccount\Application;
use Handler\Entity\TemplateMessageInterFace;
use Handler\WXUtils;

/**
 * Class TemplateMessageHandler
 * @package Handler\WX
 *
 * 依赖easywechat package
 */
class TemplateMessageHandler
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param $touser
     * @param TemplateMessageInterFace $templateMessage
     * @return bool
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function handle($touser, TemplateMessageInterFace $templateMessage)
    {
        $templateMessage->setTouser($touser);
        $data = $templateMessage->toArray();

        $response = $this->app->template_message->send($data);

        if(is_array($response)){

            return WXUtils::checkResponseIsOk($response);
        }
        return false;
    }

    public function setApplication(Application $application){
        $this->app = $application;
    }
}