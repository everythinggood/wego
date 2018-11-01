<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 6/4/18
 * Time: 7:27 PM
 */

namespace Handler\WX;


use Handler\WXUtils;

class WXPayNotifyHandler
{
    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    public $sign;

    /**
     * @param $xml
     * @throws \Exception
     */
    public function setFromXml($xml) {

        foreach(WXUtils::convert_xml_to_arr($xml) as $key => $value) {
            switch(true) {
                case $key === 'sign':
                    $this->sign = $value;
                    break;
                default:
                    $this->data[$key] = $value;
            }
        }
    }

    /**
     * @param $key
     * @throws \Exception
     */
    public function checkSign($key) {
        if (WXUtils::signature($this->data, $key) !== $this->sign) {
            throw new \Exception('错误的数字签名！');
        }
    }

    /**
     * @param $xml
     * @param string $key
     * @return WXPayNotifyHandler
     * @throws \Exception
     */
    public static function createFromXml($xml, $key = '') {
        $notify = new self();
        $notify->setFromXml($xml);
        if ($key) $notify->checkSign($key);
        return $notify;
    }

    /**
     * @param int $index index of coupon type
     * @var string
     * 代金券类型
     * @return string
     */
    public function getCouponTypeBy(int $index) {
        return isset($this->data['coupon_type_' . $index]) ? $this->data['coupon_type_' . $index] : '';
    }

    /**
     * @param int $index index of coupon id
     * @var string
     *  代金券ID
     * @return string
     */
    public function getCouponIdBy(int $index) {
        return isset($this->data['coupon_id_' . $index]) ? $this->data['coupon_id_' . $index] : '';
    }

    /**
     * @param int $index index of coupon fee
     * @var string
     * 单个代金券支付金额
     * @return float
     */
    public function getCouponFeeBy(int $index) {
        return isset($this->data['coupon_fee_' . $index]) ? $this->data['coupon_fee_' . $index] : 0;
    }

    /**
     * @var string
     *  返回状态码
     * @return string
     */
    public function getReturnCode() {
        return isset($this->data['return_code']) ? $this->data['return_code'] : '';
    }

    /**
     * @var string
     *  返回信息
     * @return string
     */
    public function getReturnMsg() {
        return isset($this->data['return_msg']) ? $this->data['return_msg'] : '';
    }

    /**
     * @var string
     *  公众账号ID
     * @return string
     */
    public function getAppid() {
        return isset($this->data['appid']) ? $this->data['appid'] : '';
    }

    /**
     * @var string
     *  商户号
     * @return string
     */
    public function getMchId() {
        return isset($this->data['mch_id']) ? $this->data['mch_id'] : '';
    }

    /**
     * @var string
     *  设备号
     * @return string
     */
    public function getDeviceInfo() {
        return isset($this->data['device_info']) ? $this->data['device_info'] : '';
    }

    /**
     * @var string
     *  随机字符串
     * @return string
     */
    public function getNonceStr() {
        return isset($this->data['nonce_str']) ? $this->data['nonce_str'] : '';
    }

    /**
     * @var string
     *  签名
     * @return string
     */
    public function getSign() {
        return isset($this->data['sign']) ? $this->data['sign'] : '';
    }

    /**
     * @var string
     *  签名类型
     * @return string
     */
    public function getSignType() {
        return isset($this->data['sign_type']) ? $this->data['sign_type'] : '';
    }

    /**
     * @var string
     *  业务结果
     * @return string
     */
    public function getResultCode() {
        return isset($this->data['result_code']) ? $this->data['result_code'] : '';
    }

    /**
     * @var string
     *  错误代码
     * @return string
     */
    public function getErrCode() {
        return isset($this->data['err_code']) ? $this->data['err_code'] : '';
    }

    /**
     * @var string
     *  错误代码描述
     * @return string
     */
    public function getErrCodeDes() {
        return isset($this->data['err_code_des']) ? $this->data['err_code_des'] : '';
    }

    /**
     * @var string
     *  用户标识
     * @return string
     */
    public function getOpenid() {
        return isset($this->data['openid']) ? $this->data['openid'] : '';
    }

    /**
     * @var string
     *  是否关注公众账号
     * @return string
     */
    public function getIsSubscribe() {
        return isset($this->data['is_subscribe']) ? $this->data['is_subscribe'] : '';
    }

    /**
     * @var string
     *  交易类型
     * @return string
     */
    public function getTradeType() {
        return isset($this->data['trade_type']) ? $this->data['trade_type'] : '';
    }

    /**
     * @var string
     *  付款银行
     * @return string
     */
    public function getBankType() {
        return isset($this->data['bank_type']) ? $this->data['bank_type'] : '';
    }

    /**
     * @var string
     *  订单金额
     * @return float
     */
    public function getTotalFee() {
        return isset($this->data['total_fee']) ? $this->data['total_fee'] : 0;
    }

    /**
     * @var string
     *  应结订单金额
     * @return float
     */
    public function getSettlementTotalFee() {
        return isset($this->data['settlement_total_fee']) ? $this->data['settlement_total_fee'] : 0;
    }

    /**
     * @var string
     *  货币种类
     * @return string
     */
    public function getFeeType() {
        return isset($this->data['fee_type']) ? $this->data['fee_type'] : '';
    }

    /**
     * @var string
     *  现金支付金额
     * @return float
     */
    public function getCashFee() {
        return isset($this->data['cash_fee']) ? $this->data['cash_fee'] : 0;
    }

    /**
     * @var string
     *  现金支付货币类型
     * @return string
     */
    public function getCashFeeType() {
        return isset($this->data['cash_fee_type']) ? $this->data['cash_fee_type'] : '';
    }

    /**
     * @var string
     *  总代金券金额
     * @return float
     */
    public function getCouponFee() {
        return isset($this->data['coupon_fee']) ? $this->data['coupon_fee'] : 0;
    }

    /**
     * @var string
     *  代金券使用数量
     * @return int
     */
    public function getCouponCount() {
        return isset($this->data['coupon_count']) ? $this->data['coupon_count'] : 0;
    }

    /**
     * @var string
     *  微信支付订单号
     * @return string
     */
    public function getTransactionId() {
        return isset($this->data['transaction_id']) ? $this->data['transaction_id'] : '';
    }

    /**
     * @var string
     *  商户订单号
     * @return string
     */
    public function getOutTradeNo() {
        return isset($this->data['out_trade_no']) ? $this->data['out_trade_no'] : '';
    }

    /**
     * @var string
     *  商家数据包
     * @return string
     */
    public function getAttach() {
        return isset($this->data['attach']) ? $this->data['attach'] : '';
    }

    /**
     * @var string
     *  支付完成时间
     * @return string
     */
    public function getTimeEnd() {
        return isset($this->data['time_end']) ? $this->data['time_end'] : '';
    }


}