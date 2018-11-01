<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/23/18
 * Time: 12:15 PM
 */

namespace Service;


use EasyWeChat\Payment\Application;
use Monolog\Logger;

class WxPaymentService
{

    /**
     * @var Application
     */
    private $application;

    /**
     * @var BillService
     */
    private $billService;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * WxPaymentService constructor.
     * @param Application $application
     * @param BillService $billService
     * @param Logger $logger
     */
    public function __construct(Application $application, BillService $billService, Logger $logger)
    {
        $this->application = $application;
        $this->billService = $billService;
        $this->logger = $logger;
    }


    public function setLogger(Logger $logger){
        $this->logger = $logger;
    }

    /**
     * @param $openid
     * @param $total_fee
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Exception
     */
    public function getWxPaymentConfig($openid, $total_fee){

        if(!$openid) throw new \Exception("require openid parameters");
        if(!$total_fee) throw new \Exception("require total_fee parameters");

        $out_trade_no = $this->billService->generateBillNo();

        $result = $this->application->order->unify(
            [
                'body' => '购买纸色免费纸巾',
                'out_trade_no' => $out_trade_no,
                'total_fee' => $total_fee,
                'trade_type' => 'JSAPI',
                'openid' => $openid,
            ]
        );

        $this->logger->addInfo('微信预订单：',$result);

        $wxResponse = json_decode($result,true);

        $prepayId = null;

        if($wxResponse['xml']&&$wxResponse['xml']['prepay_id']){
            $prepayId = $wxResponse['xml']['prepay_id'];
        }

        if(!$prepayId){
            throw new \Exception("prepayId is null");
        }

        $this->billService->createBillBy($out_trade_no,$prepayId,$openid,$total_fee);

        $config = $this->application->jssdk->sdkConfig($prepayId);

        $this->logger->addInfo('wx jssdk config',$config);

        return $config;
    }


}