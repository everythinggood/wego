<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/17/18
 * Time: 6:29 PM
 */

namespace Action\Payment;


use Action\ActionInterface;
use Contract\Container;
use EasyWeChat\Payment\Application;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Service\BillService;
use Slim\Http\Request;
use Slim\Views\Twig;

class WxPaymentAction implements ActionInterface
{

    /**
     * @var Twig
     */
    private $view;
    /**
     * @var Application
     */
    private $application;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var BillService
     */
    private $billService;

    /**
     * ActionInterface constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->view = $container[Container::NAME_VIEW];
        $this->application = $container[Container::NAME_WX_PAYMENT];
        $this->logger = $container[Container::NAME_LOGGER];
        $this->billService = $container[BillService::class];
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /** @var Request $request */
        $openid = $request->getParam('openId');
        $total_fee = $request->getParam('totalFee');

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

        return $this->view->render($response,'/wx/payment.phtml',['config'=>$config]);
    }
}