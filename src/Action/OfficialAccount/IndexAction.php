<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 5/15/18
 * Time: 6:31 PM
 */

namespace Action\OfficialAccount;


use Action\ActionInterface;
use Contract\Container;
use Contract\Session;
use EasyWeChat\OfficialAccount\Application;
use Handler\BackedHandler;
use Handler\Entity\User;
use Handler\EntityUtils;
use Handler\WxJsHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Service\BillService;
use Service\WxPaymentService;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use SlimSession\Helper;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class IndexAction implements ActionInterface
{
    /**
     * @var WxJsHandler
     */
    private $wxHandler;
    /**
     * @var Application
     */
    private $app;
    /**
     * @var Helper
     */
    private $sHelper;
    /**
     * @var BackedHandler
     */
    private $backHandler;
    /**
     * @var Twig
     */
    private $view;
    /**
     * @var BillService
     */
    private $billService;
    /**
     * @var Logger
     */
    private $logger;

    /**
     * ActionInterface constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->wxHandler = $container[Container::NAME_HANDLER_WX_JS];
        $this->app = $container[Container::NAME_WX_APP];
        $this->sHelper = $container[Container::NAME_SESSION];
        $this->backHandler = $container[Container::NAME_HANDLER_BACKED];
        $this->logger = $container[Container::NAME_LOGGER];
        $this->view = $container[Container::NAME_VIEW];
        $this->billService = $container[BillService::class];
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {

        $this->logger->addInfo('/wx/index');

        /** @var Request $request */
        /** @var Response $response */

        $machineCode = $request->getParam('machineCode');

        if (!$machineCode) throw new \Exception("require machineCode parameter!");

        $user = $this->sHelper->get(Session::NAME_USER_INFO);

        $this->logger->addInfo("wx/index session user info array", (array)$user);

        //未登陆
        if (empty($user)) {

            $syResponse = $this->app->oauth->redirect();

            $this->logger->addInfo("oauth redirect response if session is not exist", (array)$syResponse);

            $factory = new DiactorosFactory();
            return $factory->createResponse($syResponse);
        }

        //已登陆
        if (count($user) < 0) throw new \Exception('can not get user info!');
        $user = EntityUtils::convertSessionToUser($user);

        $this->logger->addInfo("oauth not need redirect if session is exist", (array)$user);

        return $this->showPage($machineCode, $user, $response);

    }

    /**
     * @param $machineCode
     * @param User $user
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Exception
     */
    private function showPage($machineCode, User $user, ResponseInterface $response)
    {
        /** @var Response $response */

        if (!$this->backHandler->isOk($machineCode))
            return $this->view->render($response, '/wx/offLine.phtml');

        if ($this->backHandler->isFree($user)) {
            //根据机器码和用户 去后台服务拿公众号关注链接
            $this->logger->addInfo("this user is free today!", (array)$user);
            $adID = $this->backHandler->getAdID($machineCode, $user->sex);
            $this->backHandler->saveUserScan(join('_',[$machineCode,$adID]), $user->openid);
            $adUrl = $this->backHandler->getAdUrl($adID);
            //关注公众号二维码页面生成
            return $response->withRedirect('/wx/adInfo.phtml', compact('adUrl'));
        }

        $this->logger->addInfo("this user is not free today!", (array)$user);

        $wxPaymentService = new WxPaymentService($this->app, $this->billService, $this->logger);

        $config = $wxPaymentService->getWxPaymentConfig($user->openid, 0.01);

        return $this->view->render($response, '/wx/payment.phtml', ['config' => $config]);
    }
}