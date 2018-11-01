<?php
/**
 * Created by PhpStorm.
 * User: ycy
 * Date: 7/3/18
 * Time: 10:31 AM
 */

namespace Action\OfficialAccount;


use Action\ActionInterface;
use Contract\Container;
use Contract\Session;
use Handler\BackedHandler;
use Handler\EntityUtils;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Service\AdBIllService;
use Slim\Http\Request;
use Slim\Views\PhpRenderer;
use SlimSession\Helper;

class getPaperAction implements ActionInterface
{
    /**
     * @var PhpRenderer
     */
    private $view;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var Helper
     */
    private $sHelper;
    /**
     * @var BackedHandler
     */
    private $backedHandler;
    /**
     * @var AdBIllService
     */
    private $adBillService;

    /**
     * ActionInterface constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->view = $container[Container::NAME_VIEW];
        $this->logger = $container[Container::NAME_LOGGER];
        $this->sHelper = $container[Container::NAME_SESSION];
        $this->backedHandler = $container[Container::NAME_HANDLER_BACKED];
        $this->adBillService = $container[AdBIllService::class];
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     *
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /** @var Request $request */
        /** @var string $state */
        $state = $request->getParam('state');

        $user = $this->sHelper->get(Session::NAME_USER_INFO);

        $user = EntityUtils::convertSessionToUser($user);

        $wxOpenId = $user->openid;

        $machineCodeAndAd = $this->backedHandler->getMachineAndAdOnUserScan($wxOpenId);

        list($machineCode, $adId) = explode('_', $machineCodeAndAd);

        if ($state != $adId) {
            return $this->view->render($response, '/wx/fail.phtml');
        }

        $machineInfo = $this->backedHandler->getMachineInfo($machineCode);
        $data = array_merge($machineInfo,compact('wxOpenId','adId','machineCode'));

        $result = $this->backedHandler->sendPaper($machineCode);

        if ($result) {

            $adBill = $this->adBillService->createAdBillBy($data);

            $this->logger->addInfo("add adBill Response",(array)$adBill);

            $this->backedHandler->resetAdOnNumber($adId);

            return $this->view->render($response, '/wx/success.phtml');
        }

        return $this->view->render($response, '/wx/fail.phtml');
    }
}