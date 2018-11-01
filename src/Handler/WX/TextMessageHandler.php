<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/17/18
 * Time: 11:48 AM
 */

namespace Handler\WX;


use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\Text;

class TextMessageHandler implements EventHandlerInterface
{

    private $rule = [
        //模糊
        'blur'=>[
            '888'=>"请点击【<a href=\"http://m.zhiwei99.com/addon/YiKaTong/GuanzhuGzh/up?state=1016\">【免费领取纸巾】</a>]",
        ],
        //精确匹配
        'extract'=>[
            '888'=>"请点击【<a href=\"http://m.zhiwei99.com/addon/YiKaTong/GuanzhuGzh/up?state=1016\">【免费领取纸巾】</a>]",
        ]
    ];

    /**
     * @param null $payload
     * @return string
     */
    public function handle($payload = null)
    {
        $receive = $payload['Content'];

        if(strpos($receive,'888') > -1){
            return $this->rule['extract']['888'];
        }

//        foreach ($this->rule['extract'] as $extractPattern=>$content){
//            if($receive === $extractPattern){
//                return $content;
//            }
//        }
//
//        foreach ($this->rule['blur'] as $blurPattern=>$content){
//            if(strpos($receive,$blurPattern) > -1){
//                return $content;
//            }
//        }

        return '欢迎您!';

    }


}
