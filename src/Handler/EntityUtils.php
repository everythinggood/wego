<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/15/18
 * Time: 7:01 PM
 */

namespace Handler;


use Handler\Entity\User;

class EntityUtils
{

    public static function convertSessionToUser(array $arrUser){
        $user = new User();
        $arrUser = $arrUser['original'];
        $user->city = $arrUser['city'];
        $user->country = $arrUser['country'];
        $user->headimgurl = $arrUser['headimgurl'];
        $user->nickname = $arrUser['nickname'];
        $user->openid = $arrUser['openid'];
        $user->privilege = $arrUser['privilege'];
        $user->province = $arrUser['province'];
        $user->sex = $arrUser['sex'];
        $user->unionid = $arrUser['unionid'];

        return $user;
    }

}