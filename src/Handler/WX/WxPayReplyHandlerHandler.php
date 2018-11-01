<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 6/4/18
 * Time: 2:07 PM
 */

namespace Handler\WX;


use Handler\WXUtils;

class WXPayReplyHandler
{

    /**
     *
     */
    const RETURN_CODE_SUCCESS = 'SUCCESS';
    /**
     *
     */
    const RETURN_CODE_FAIL = 'FAIL';
    /**
     *
     */
    const RETURN_MSG_OK = 'OK';

    /**
     * @var
     */
    protected $data;


    /**
     * @param $value
     */
    public function setReturnCode($value) {
        $this->data['return_code'] = $value;
    }

    /**
     * @param $value
     */
    public function setReturnMessage($value) {
        $this->data['return_msg'] = $value;
    }

    /**
     * @param
     * @return string
     */
    public function getReturnCode() {
        return $this->data['return_code'];
    }

    /**
     * @param
     * @return string
     */
    public function getReturnMessage() {
        return $this->data['return_msg'];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function __toString()
    {
        return WXUtils::convert_arr_to_xml((array)$this->data);
    }

    /**
     * @return WXPayReplyHandler
     */
    public static function createSuccessReply() {
        $reply = new self();
        $reply->setReturnCode(self::RETURN_CODE_SUCCESS);
        $reply->setReturnMessage(self::RETURN_MSG_OK);
        return $reply;
    }

    /**
     * @param $errorMsg
     * @return WXPayReplyHandler
     */
    public static function createFailReply($errorMsg) {
        $reply = new self();
        $reply->setReturnCode(self::RETURN_CODE_FAIL);
        $reply->setReturnMessage($errorMsg);
        return $reply;
    }
}