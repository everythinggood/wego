<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 6/4/18
 * Time: 7:30 PM
 */

namespace Handler;


use Contract\WxErrorCode;

class WXUtils
{

    /**
     * @param $values
     * @return string
     * @throws \Exception
     */
    public static function convert_arr_to_xml($values)
    {
        if (!is_array($values)
            || count($values) <= 0
        ) {
            throw new \Exception("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * @param $xml
     * @return mixed
     * @throws \Exception
     */
    public static function convert_xml_to_arr($xml)
    {
        if (!$xml) {
            throw new \Exception("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * @param int $length
     * @return string
     */
    public static function generate_nonce_str($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public static function to_url_params(array $values)
    {
        $buff = "";
        foreach ($values as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    public static function signature(array $data, $key)
    {
        ksort($data);
        $string = self::to_url_params($data);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    public static function checkResponseIsOk(array $response){
        if($response['errcode'] == WxErrorCode::IS_OK){
            return true;
        }
        return false;
    }


}