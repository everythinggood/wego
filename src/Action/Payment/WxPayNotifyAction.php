<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 6/4/18
 * Time: 7:12 PM
 */

namespace Action\Payment;


use Action\ActionInterface;
use Contract\Container;
use Domain\Bill;
use Handler\WX\WXPayNotifyHandler;
use Handler\WX\WXPayReplyHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Service\BillService;

class WxPayNotifyAction implements ActionInterface
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var BillService
     */
    private $billService;
    /**
     * @var array
     */
    private $setting;

    /**
     * WxPayNotifyAction constructor.
     * @param ContainerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container[Container::NAME_LOGGER];
        $this->billService = $container[BillService::class];
        $this->setting = $container->get('settings')['wxPaymentConfig'];
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

        try {
            $notify = WXPayNotifyHandler::createFromXml(strval($request->getBody()->getContents()), $this->setting['key']);

            if ($notify->getReturnCode() === 'SUCCESS' && $notify->getResultCode() === 'SUCCESS') {

                $this->logger->info(implode(' ', [
                        $notify->getOutTradeNo(),
                        $notify->getTotalFee(),
                        $notify->getTransactionId(),
                        $notify->getOpenid()])
                );

                /** @var Bill $bill */
                $bill = $this->billService->findBillBy($notify->getOutTradeNo());

                if ($bill && ($bill->getStatus() === Bill::STATUS_PREPAY)) {

                    $this->logger->info('update bill status: '.$bill->getBillNo());

                    $this->billService->saveBillBy($bill,[
                       'status'=>Bill::STATUS_PAID,
                       'wxPayNo'=>$notify->getTransactionId()
                    ]);

                }

            } else {

                $message = 'wx server notify wrong message!';
                $this->logger->err($message, (array)$notify);

                if ($billId = $notify->getOutTradeNo()) {
                    $bill = $this->billService->findBillBy($billId);

                    $this->billService->saveBillBy($bill,Bill::STATUS_CANCEL);
                }

                throw new \RuntimeException($message, 73864);

            }

            return $response->getBody()->write(strval(WXPayReplyHandler::createSuccessReply()));

        } catch (\Exception $e) {

            $this->logger->err($e->getMessage(), [$request->getHeaders(), 'content' => strval($request->getBody()->getContents())]);
            return $response->getBody()->write(strval(WXPayReplyHandler::createFailReply($e->getMessage())));
        }

    }
}